# 🛠️ Guide Technique - NEODATA

**Destiné aux développeurs**  
**Version:** 1.0.0

---

## Table des matières

1. [Architecture Détaillée](#architecture-détaillée)
2. [Cycle de Vie des Demandes d'Accès](#cycle-de-vie-des-demandes-daccès)
3. [Workflow Multi-Tenant](#workflow-multi-tenant)
4. [Intégration des Composants Blade](#intégration-des-composants-blade)
5. [Gestion des Erreurs](#gestion-des-erreurs)
6. [Optimisations et Performances](#optimisations-et-performances)
7. [Sécurité](#sécurité)
8. [Déploiement](#déploiement)

---

## Architecture Détaillée

### Flux de Requête Utilisateur

```
1. Requête HTTP
   ↓
2. Middleware (auth, CheckRole)
   ├── Vérifier si authentifié
   ├── Vérifier le rôle/permissions
   └── Vérifier l'isolation multi-tenant
   ↓
3. Route → Contrôleur
   ├── Valider les données (FormRequest)
   ├── Appliquer les permissions
   └── Exécuter la logique métier
   ↓
4. Modèle (Eloquent)
   ├── Vérifier les relations
   ├── Appliquer les filtres (company_id)
   └── Accéder la BD
   ↓
5. Vue Blade
   ├── Composer les données
   ├── Rendre le HTML
   └── Répondre au client
```

### Pattern MVC Appliqué

```
Model (Modèle):
  ├── app/Models/User.php
  ├── app/Models/Company.php
  ├── app/Models/Document.php
  └── Logique de données/relations

View (Vue):
  ├── resources/views/users/
  ├── resources/views/documents/
  └── resources/views/layouts/
     
Controller (Contrôleur):
  ├── app/Http/Controllers/UserController.php
  ├── app/Http/Controllers/DocumentController.php
  └── Logique métier
```

---

## Cycle de Vie des Demandes d'Accès

### Diagramme d'État

```
┌─────────────────────────────────────────┐
│    Demande d'accès créée                │
│    Status: PENDING                       │
│    (Visiteur remplissant le formulaire) │
└──────────────┬──────────────────────────┘
               │
        ┌──────▼──────┐
        │   Attente   │
        │   Admin     │
        └──────┬──────┘
        ┌──────▼──────┬──────────────────┐
        │             │                  │
  Admin appelle  Admin rejette    Délai d'inaction
   l'approuver    la demande     (ou vérif manuelle)
        │             │                  │
        ▼             ▼                  │
   ┌────────┐    ┌────────┐             │
   │APPROVED│    │REJECTED│             │
   └────┬───┘    └────────┘             │
        │                               │
        │ Company::createWithRootAccount()
        │         ├─ CREATE Company
        │         ├─ CREATE Category (root)
        │         └─ CREATE User (admin root)
        │
        ▼
   ┌──────────────────┐
   │Entreprise active │
   │Prête à utiliser  │
   └──────────────────┘
```

### Code du Contrôleur

```php
// app/Http/Controllers/CompanyAccessRequestController.php

public function approve(CompanyAccessRequest $request)
{
    // Vérifier permission admin
    if (!auth()->user()->isAdmin()) {
        abort(403);
    }
    
    try {
        // Créer la compagnie avec transaction atomique
        $company = Company::createWithRootAccount(
            [
                'nom' => $request->company_name,
                'email' => $request->company_email,
                'telephone' => $request->telephone,
                'adresse' => $request->adresse,
            ],
            [
                'name' => $request->contact_name,
                'email' => $request->contact_email,
                'role' => 'admin',
                'password' => Str::random(16), // Mot de passe aléatoire
            ]
        );
        
        // Mettre à jour la demande
        $request->update([
            'status' => 'approved',
            'company_id' => $company->id,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        
        // Envoyer l'email de bienvenue
        Mail::send(new CompanyApprovedNotification($request, $company));
        
        return redirect()->route('company-requests.index')
                        ->with('success', 'Demande approuvée!');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Erreur lors de l\'approbation']);
    }
}
```

---

## Workflow Multi-Tenant

### Création d'une Compagnie

```php
// app/Models/Company.php

public static function createWithRootAccount(
    array $companyData,
    array $rootUserData,
    bool $passwordIsHashed = false
) {
    // Valider les données requises
    if (empty($companyData['nom']) || empty($companyData['email'])) {
        throw new InvalidArgumentException(
            'Le nom et l\'email de l\'entreprise sont obligatoires.'
        );
    }
    
    if (empty($rootUserData['name']) || 
        empty($rootUserData['email']) || 
        empty($rootUserData['password'])) {
        throw new InvalidArgumentException(
            'Le nom, l\'email et le mot de passe de l\'utilisateur racine sont obligatoires.'
        );
    }
    
    // Transaction ACID - Tout ou rien
    return DB::transaction(function () use ($companyData, $rootUserData, $passwordIsHashed) {
        // 1. Créer l'entreprise
        $company = self::create($companyData);
        
        // 2. Créer la catégorie racine
        $rootCategory = Category::create([
            'company_id' => $company->id,
            'name' => 'Racine',
            'description' => 'Catégorie racine de ' . $company->nom,
            'parent_id' => null, // Pas de parent pour la racine
        ]);
        
        // 3. Créer l'utilisateur admin racine
        $rootUser = User::create([
            'company_id' => $company->id,
            'name' => $rootUserData['name'],
            'email' => $rootUserData['email'],
            'password' => $passwordIsHashed 
                ? $rootUserData['password'] 
                : Hash::make($rootUserData['password']),
            'role' => 'admin',
        ]);
        
        return $company;
    });
}
```

### Filtrage Multi-Tenant dans les Requêtes

```php
// Toujours inclure le filtre company_id!

// ❌ MAUVAIS - Pas de filtre
$documents = Document::where('title', 'important')->get();

// ✅ BON - Filtre multi-tenant
$documents = Document::where('company_id', auth()->user()->company_id)
                      ->where('title', 'important')
                      ->get();

// ✅ MIEUX - Utiliser une scope
class Document extends Model {
    public function scopeForCurrentCompany($query) {
        return $query->where('company_id', auth()->user()->company_id);
    }
}

// Utiliser:
$documents = Document::forCurrentCompany()
                      ->where('title', 'important')
                      ->get();
```

### Middleware de Vérification

```php
// app/Http/Middleware/CheckRole.php

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!auth()->check()) {
            return redirect('/login');
        }
        
        // Vérifier le rôle
        $userRole = auth()->user()->role;
        if (!in_array($userRole, $roles)) {
            abort(403, 'Accès non autorisé');
        }
        
        // Vérifier que l'utilisateur a une compagnie
        if (!auth()->user()->company_id) {
            abort(403, 'Utilisateur sans compagnie associée');
        }
        
        return $next($request);
    }
}

// Utiliser dans les routes:
Route::post('/documents', 'DocumentController@store')
    ->middleware('CheckRole:admin,direction');
```

---

## Intégration des Composants Blade

### Notification Pop-up (20 secondes)

Implémentation dans `resources/views/layouts/app.blade.php`:

```blade
@if(auth()->check() && auth()->user()->isAdmin())
    @php 
        $pendingCount = \App\Models\CompanyAccessRequest::where('status', 'pending')
                        ->count(); 
    @endphp
    
    @if($pendingCount > 0)
        <div id="notifPending" 
             class="fixed top-20 right-8 z-50 bg-amber-50 border-2 border-amber-400 
                    rounded-lg p-4 shadow-lg animate-slideIn">
            
            <!-- Contenu du pop-up -->
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🔔</span>
                    <div>
                        <h3 class="font-bold text-amber-900">Demandes d'accès en attente</h3>
                        <p class="text-sm text-amber-700">
                            <strong>{{ $pendingCount }}</strong> demande(s) à examiner
                        </p>
                        <a href="{{ route('company-requests.index') }}" 
                           class="text-sm text-amber-600 hover:underline mt-1 inline-block">
                            Voir demandes →
                        </a>
                    </div>
                </div>
                
                <!-- Bouton fermer -->
                <button onclick="closeNotif()" 
                        class="text-amber-600 hover:text-amber-900 font-bold">
                    ✕
                </button>
            </div>
        </div>
        
        <!-- JavaScript pour auto-fermeture -->
        <script>
            function closeNotif() {
                const notif = document.getElementById('notifPending');
                notif.style.opacity = '0';
                notif.style.transition = 'opacity 0.3s ease';
                setTimeout(() => {
                    notif.style.display = 'none';
                }, 300);
            }
            
            // Auto-fermer après 20 secondes
            setTimeout(() => closeNotif(), 20000);
        </script>
    @endif
@endif
```

### CSS pour Animation

```css
/* resources/css/app.css */

@keyframes slideIn {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.animate-slideIn {
    animation: slideIn 0.3s ease-out;
}
```

---

## Gestion des Erreurs

### Exceptions Personnalisées

```php
// app/Exceptions/CompanyAccessDeniedException.php

namespace App\Exceptions;

use Exception;

class CompanyAccessDeniedException extends Exception
{
    public function __construct($message = "Accès refusé à cette compagnie")
    {
        parent::__construct($message);
    }
    
    public function render($request)
    {
        return response()->view('errors.403', [], 403);
    }
}

// Utiliser:
throw new CompanyAccessDeniedException(
    "L'utilisateur n'appartient pas à cette compagnie"
);
```

### Gestion Global des Erreurs

```php
// app/Exceptions/Handler.php

public function render($request, Throwable $exception)
{
    // Erreur 404 - Pas trouvé
    if ($exception instanceof ModelNotFoundException) {
        return response()->view('errors.404', [], 404);
    }
    
    // Erreur 403 - Accès interdit
    if ($exception instanceof AuthorizationException) {
        return response()->view('errors.403', [], 403);
    }
    
    // Erreur 405 - Méthode non autorisée
    if ($exception instanceof MethodNotAllowedHttpException) {
        return response()->json([
            'error' => 'Méthode HTTP non autorisée'
        ], 405);
    }
    
    return parent::render($request, $exception);
}
```

---

## Optimisations et Performances

### Eager Loading (N+1 Problem)

```php
// ❌ MAUVAIS - Génère N+1 requêtes
$documents = Document::all();
foreach ($documents as $doc) {
    echo $doc->category->name; // Une requête par document!
}
// Total: 1 requête + N requêtes = N+1

// ✅ BON - Une seule requête
$documents = Document::with('category')
                      ->forCurrentCompany()
                      ->get();
foreach ($documents as $doc) {
    echo $doc->category->name; // Déjà chargé en mémoire
}
// Total: 2 requêtes
```

### Pagination

```php
// ✅ Paginer les résultats pour ne pas charger tout en mémoire
$documents = Document::forCurrentCompany()
                      ->paginate(15); // 15 résultats par page

// Dans la vue:
{{ $documents->links() }}
```

### Cache

```php
// Cacher le count des demandes en attente
$pendingCount = Cache::remember('pending_requests_count', 
    60, // 60 secondes
    function () {
        return CompanyAccessRequest::where('status', 'pending')->count();
    }
);

// Invalider le cache après approbation
Cache::forget('pending_requests_count');
```

### Indexes de Base de Données

```php
// Dans les migrations, ajouter des indexes
Schema::create('documents', function (Blueprint $table) {
    $table->id();
    $table->foreignId('company_id')->constrained();
    $table->foreignId('category_id')->constrained();
    $table->string('title');
    $table->timestamps();
    
    // Index pour les requêtes fréquentes
    $table->index(['company_id', 'category_id']);
    $table->index('created_at');
});
```

---

## Sécurité

### Validation des Entrées

```php
// app/Http/Requests/StoreDocumentRequest.php

class StoreDocumentRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }
    
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => 'required|file|max:100000', // 100MB max
            'category_id' => 'required|exists:categories,id',
        ];
    }
    
    public function messages()
    {
        return [
            'title.required' => 'Le titre est obligatoire',
            'file.max' => 'Le fichier ne doit pas dépasser 100MB',
        ];
    }
}

// Utiliser dans le contrôleur:
public function store(StoreDocumentRequest $request)
{
    // Les données sont déjà validées
    $document = Document::create($request->validated());
}
```

### Protection CSRF

```blade
<!-- Chaque formulaire doit incluire un token CSRF -->
<form method="POST" action="/documents">
    @csrf
    
    <input type="text" name="title">
    <button type="submit">Envoyer</button>
</form>
```

### Authentification

```php
// Vérifier le mot de passe avant d'accorder l'accès
if (!Hash::check($password, $user->password)) {
    throw ValidationException::withMessages([
        'email' => ['Les identifiants fournis sont incorrects.'],
    ]);
}

// Ne JAMAIS stocker les mots de passe en clair
// Toujours utiliser Hash::make() ou password_hash()
```

### Chiffrement des Documents

```php
// app/Models/Document.php

public function encryptFile($fileContent)
{
    // Utiliser la clé de chiffrement de la compagnie
    $key = base64_encode(hash('sha256', $this->company->id . 'SECRET_KEY'));
    
    $encryptor = new Encrypter($key, 'AES-256-CBC');
    return $encryptor->encrypt($fileContent);
}

public function decryptFile($encryptedContent)
{
    $key = base64_encode(hash('sha256', $this->company->id . 'SECRET_KEY'));
    
    $decryptor = new Encrypter($key, 'AES-256-CBC');
    return $decryptor->decrypt($encryptedContent);
}
```

---

## Déploiement

### Sur un Serveur Production

#### 1. Préparer l'Environnement

```bash
# SSH sur le serveur
ssh user@production-server.com

# Cloner le repository
git clone <repository-url> /var/www/neodata
cd /var/www/neodata

# Installer les dépendances
composer install --no-dev --optimize-autoloader
npm install --production
npm run build
```

#### 2. Configurer .env

```bash
# Copier et éditer le .env
cp .env.example .env

# Générer la clé
php artisan key:generate

# Éditer les variables importantes:
# - APP_ENV=production
# - DB_HOST, DB_USERNAME, DB_PASSWORD
# - MAIL_HOST, MAIL_USERNAME, MAIL_PASSWORD
# - APP_DEBUG=false (IMPORTANT!)

nano .env
```

#### 3. Permissions et Ownership

```bash
# Changement de propriétaire
chown -R www-data:www-data /var/www/neodata

# Permissions
chmod -R 755 /var/www/neodata
chmod -R 775 /var/www/neodata/storage
chmod -R 775 /var/www/neodata/bootstrap/cache
```

#### 4. Base de Données

```bash
# Créer la base de données
mysql -u root -p
> CREATE DATABASE neodata;
> EXIT;

# Migrer le schéma
php artisan migrate --force

# (Optionnel) Seeder les données initiales
php artisan db:seed --force
```

#### 5. Configurer le Serveur Web

**Nginx:**

```nginx
server {
    listen 80;
    server_name neodata.example.com;
    root /var/www/neodata/public;
    
    index index.php;
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.ht {
        deny all;
    }
}
```

#### 6. HTTPS et SSL

```bash
# Avec Certbot (Let's Encrypt)
sudo certbot certonly --nginx -d neodata.example.com
```

#### 7. Déploiement Automatisé (CI/CD)

```yaml
# .github/workflows/deploy.yml

name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Deploy via SSH
        run: |
          mkdir -p ~/.ssh
          echo "${{ secrets.SSH_KEY }}" > ~/.ssh/id_rsa
          chmod 600 ~/.ssh/id_rsa
          ssh -o StrictHostKeyChecking=no user@server.com << 'EOF'
            cd /var/www/neodata
            git pull origin main
            composer install --no-dev
            npm run build
            php artisan migrate --force
            php artisan cache:clear
          EOF
```

### Monitoring et Logs

```bash
# Afficher les logs en temps réel
tail -f storage/logs/laravel.log

# Archiver les logs anciens
logrotate -f /etc/logrotate.d/neodata

# Configurer les alertes (via Sentry, DataDog, etc.)
```

---

**📄 Dernière mise à jour: 29 Mai 2026**

*Documentation technique pour développeurs - Maintenir à jour avec les modifications du code.*
