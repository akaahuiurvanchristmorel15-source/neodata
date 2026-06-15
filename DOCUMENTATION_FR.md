# 📚 Documentation NEODATA - Système de Gestion d'Archives Sécurisées

**Version:** 1.0.0  
**Date:** 29 Mai 2026  
**Langue:** Français

---

## 📖 Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Architecture](#architecture)
3. [Installation et Configuration](#installation-et-configuration)
4. [Fonctionnalités](#fonctionnalités)
5. [Guide d'utilisation](#guide-dutilisation)
6. [Système Multi-Tenant](#système-multi-tenant)
7. [API et Routes](#api-et-routes)
8. [Gestion des Rôles et Permissions](#gestion-des-rôles-et-permissions)
9. [Guide du Développeur](#guide-du-développeur)
10. [Dépannage](#dépannage)

---

## Vue d'ensemble

**NEODATA** est une plateforme web sécurisée de gestion et d'archivage de documents numériques. Le système permet aux entreprises de:

- 📁 **Organiser** leurs documents dans une structure hiérarchique de catégories
- 🔐 **Chiffrer** tous les documents avec AES-256
- 🔍 **Rechercher** et consulter les archives rapidement
- 📊 **Suivre** l'accès aux documents (traçabilité complète)
- 👥 **Gérer** les utilisateurs par entreprise
- ✅ **Demander** l'accès via un formulaire public

### Caractéristiques principales

- ✅ Architecture multi-tenant (plusieurs entreprises isolées)
- ✅ Système de rôles (Admin, Direction, Utilisateur)
- ✅ Chiffrement AES-256 des documents
- ✅ Traçabilité complète des actions (audit logs)
- ✅ Notifications pour admins (pop-up 20s)
- ✅ Interface intuitive et responsive
- ✅ PWA compatible (installation mobile)

---

## Architecture

### Stack Technologique

```
Frontend:
  - Blade (templating Laravel)
  - Tailwind CSS (styling)
  - JavaScript vanilla
  - Playwright (tests)

Backend:
  - Laravel 11 (framework PHP)
  - MySQL/SQLite (base de données)
  - Eloquent ORM (gestion des modèles)
  - Laravel Migrations (schéma BD)

Infrastructure:
  - PHP 8.2+
  - Composer (gestionnaire de dépendances)
  - Node.js + npm (assets)
  - Vite (bundler)
```

### Structure des Répertoires

```
neodata/
├── app/
│   ├── Console/              # Commandes Artisan
│   ├── Http/
│   │   ├── Controllers/      # Contrôleurs (logique métier)
│   │   ├── Middleware/       # Middlewares (permissions, etc.)
│   │   ├── Observers/        # Observers (événements modèles)
│   │   └── Requests/         # Formulaires de validation
│   ├── Models/               # Modèles Eloquent
│   ├── Notifications/        # Notifications par email
│   ├── Providers/            # Fournisseurs de services
│   └── View/Components/      # Composants Blade réutilisables
├── bootstrap/                # Bootstrap Laravel
├── config/                   # Fichiers de configuration
├── database/
│   ├── factories/            # Factories de données de test
│   ├── migrations/           # Migrations de schéma BD
│   └── seeders/              # Seeders (données initiales)
├── public/                   # Fichiers statiques (Web root)
├── resources/
│   ├── css/                  # Styles CSS
│   ├── js/                   # JavaScript
│   └── views/                # Vues Blade
├── routes/                   # Définitions des routes
├── storage/                  # Fichiers générés (logs, cache, uploads)
├── tests/                    # Tests automatisés (Pest)
└── vendor/                   # Dépendances Composer
```

### Diagramme des Modèles

```
User
├── company_id (FK)
├── role (admin, direction, user)
├── documents (relation HasMany)
└── auditLogs (relation HasMany)

Company
├── users (relation HasMany)
├── categories (relation HasMany)
└── documents (relation HasMany via category)

Category
├── company_id (FK)
├── parent_id (auto-référence pour hiérarchie)
├── documents (relation HasMany)
└── children (relation HasMany pour sous-dossiers)

Document
├── category_id (FK)
├── company_id (FK)
├── created_by (FK vers User)
├── file_hash (hash du fichier chiffré)
└── soft_deleted (pour archivage)

CompanyAccessRequest
├── status (pending, approved, rejected)
├── company_id (FK après approbation)
├── approved_by (FK vers User admin)
└── root_password_hash (mot de passe chiffré de l'admin root)

LogAction
├── user_id (FK)
├── company_id (FK)
├── actionable_id (ID du document/catégorie)
├── action (created, updated, deleted, viewed, downloaded)
└── timestamp
```

---

## Installation et Configuration

### Prérequis

- PHP 8.2 ou supérieur
- MySQL 5.7+ ou SQLite
- Node.js 18+ et npm
- Composer 2.0+
- Git

### Étapes d'installation

#### 1. Cloner le repository

```bash
git clone <repository-url>
cd neodata
```

#### 2. Installer les dépendances

```bash
# Dépendances PHP
composer install

# Dépendances JavaScript
npm install
```

#### 3. Configurer l'environnement

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé de l'application
php artisan key:generate
```

#### 4. Configurer la base de données

Éditer `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=neodata
DB_USERNAME=root
DB_PASSWORD=
```

#### 5. Exécuter les migrations

```bash
# Créer les tables
php artisan migrate

# (Optionnel) Remplir avec des données de test
php artisan db:seed
```

#### 6. Compiler les assets

```bash
# Mode développement (avec hot reload)
npm run dev

# Mode production
npm run build
```

#### 7. Démarrer le serveur

```bash
php artisan serve
```

Le site est accessible à: `http://127.0.0.1:8000`

### Configuration .env importante

```env
# Authentification
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Cache
CACHE_DRIVER=file

# Queue
QUEUE_CONNECTION=sync

# Mail (pour notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...

# Chiffrement
APP_CIPHER=AES-256-CBC

# Stockage des fichiers
FILESYSTEM_DISK=local
```

---

## Fonctionnalités

### 1. 📝 Gestion des Demandes d'Accès

Les entreprises peuvent demander l'accès à la plateforme via un formulaire public.

**Processus:**
1. Visiteur accède à `/register`
2. Remplit le formulaire avec infos entreprise et contact
3. Demande enregistrée avec statut `pending`
4. Admin reçoit notification pop-up 20s
5. Admin approuve ou rejette la demande

**Données collectées:**
- Nom de l'entreprise
- Email de l'entreprise
- Contact (nom + email)
- Téléphone et adresse
- Motif de la demande

### 2. 🏢 Système Multi-Tenant

Chaque entreprise approuvée est complètement isolée:

- **Données isolées**: Chaque compagnie n'accède qu'à ses propres documents/catégories
- **Utilisateurs isolés**: Les utilisateurs d'une compagnie ne voient que leurs collègues
- **Catégories isolées**: Chaque compagnie a sa hiérarchie de dossiers
- **Traçabilité isolée**: Logs séparés par compagnie

**Création automatique lors de l'approbation:**
- Compagnie créée dans la BD
- Catégorie racine créée
- Utilisateur admin racine créé avec mot de passe chiffré

### 3. 👥 Gestion des Utilisateurs

Trois rôles disponibles par entreprise:

| Rôle | Permissions |
|------|-------------|
| **Admin** | Gère tous les utilisateurs, approuve demandes, voit tous les documents |
| **Direction** | Crée des catégories, gère des documents, accès en lecture-écriture |
| **Utilisateur** | Accès lecture-écriture limitée, ne peut pas gérer autres utilisateurs |

### 4. 🔐 Chiffrement des Documents

- Tous les documents sont chiffés avec **AES-256**
- La clé de chiffrement est dérivée de la compagnie
- Les fichiers sont stockés de manière binaire
- Hachage MD5 du fichier conservé pour intégrité

### 5. 📊 Traçabilité Complète (Audit Logs)

Chaque action est enregistrée:
- **Création** de document/catégorie
- **Modification** de document/catégorie
- **Suppression** (soft-delete)
- **Consultation** de document
- **Téléchargement** de document
- **Accès** à catégorie

Visible dans: Admin → Traçabilité & Audits

### 6. 🔔 Notifications Pop-up

Notification affichée 20 secondes pour:
- **Admin principal** uniquement
- Affiche nombre de demandes en attente
- Lien direct vers la page de gestion
- Fermeture automatique après 20s
- Bouton de fermeture manuel

---

## Guide d'utilisation

### Pour un Super-Admin

#### Connexion

1. Accéder à `http://127.0.0.1:8000/login`
2. Entrer les credentials admin
3. Accès au dashboard

#### Gestion des Demandes

1. Aller à **Demandes d'accès** dans le menu
2. Voir la liste des demandes en attente
3. Cliquer **Voir** sur une demande
4. Cliquer **Approuver** ou **Rejeter**
5. Si approuvé:
   - Compagnie créée automatiquement
   - Email d'accès envoyé au contact
   - Utilisateur peut se connecter avec credentials fournis

#### Gestion des Utilisateurs

1. Aller à **Gestion des utilisateurs**
2. Sélectionner une compagnie
3. Ajouter/modifier/supprimer des utilisateurs
4. Assigner les rôles (Admin, Direction, Utilisateur)

### Pour un Administrateur d'Entreprise

#### Première Connexion

1. Recevoir email avec credentials
2. Accéder au lien de connexion
3. Se connecter avec email et mot de passe fournis
4. Changer le mot de passe de préférence

#### Gestion des Documents

1. Aller à **Tableau de bord**
2. Voir les catégories disponibles
3. **Créer une catégorie**:
   - Cliquer "Nouvelle catégorie"
   - Entrer le nom et description
   - Sélectionner parent (optionnel pour sous-dossiers)
   - Cliquer "Créer"

4. **Ajouter un document**:
   - Naviguer dans une catégorie
   - Cliquer "Ajouter un document"
   - Sélectionner le fichier
   - Entrer titre et description
   - Cliquer "Télécharger"

5. **Rechercher un document**:
   - Aller à **Recherche**
   - Entrer mots-clés
   - Résultats affichés avec compagnie et catégorie

### Pour un Utilisateur

#### Accès aux Documents

1. Tableau de bord affiche les catégories accessibles
2. Naviguer dans la structure des dossiers
3. Cliquer sur un document pour le consulter
4. Cliquer "Télécharger" pour le récupérer

#### Limitations

- Ne peut pas créer/modifier des catégories
- Ne peut pas supprimer des documents
- Accès en lecture-écriture selon permissions

---

## Système Multi-Tenant

### Concept

NEODATA est une plateforme **SaaS multi-tenant** où:
- Un seul système supporte plusieurs entreprises
- Chaque entreprise a ses données complètement isolées
- Les données d'une compagnie sont inaccessibles à une autre
- Les costs sont partagés mais les données sont séparées

### Isolation des Données

#### Par Compagnie dans les Modèles

```php
// Exemple: Accéder uniquement aux documents de sa compagnie
$myDocuments = Document::where('company_id', auth()->user()->company_id)->get();

// Exemple: Tous les utilisateurs de ma compagnie
$colleagues = User::where('company_id', auth()->user()->company_id)->get();
```

#### Dans les Requêtes SQL

```sql
-- Documents de la compagnie 1
SELECT * FROM documents WHERE company_id = 1;

-- Utilisateurs de la compagnie 1
SELECT * FROM users WHERE company_id = 1;

-- Catégories de la compagnie 1
SELECT * FROM categories WHERE company_id = 1;
```

### Workflow de Création Multi-Tenant

Quand un admin approuve une demande d'accès:

```
1. CompanyAccessRequest::approve()
   ↓
2. Company::createWithRootAccount()
   ↓
3. DB::transaction() - Atomicité garantie
   ├── Créer la compagnie
   ├── Créer la catégorie racine
   └── Créer l'utilisateur admin racine
   ↓
4. Compagnie prête à l'emploi
```

### Sécurité Multi-Tenant

- ✅ Middleware `CheckRole` vérifie les permissions
- ✅ Filtre `company_id` sur toutes les requêtes
- ✅ Foreign keys garantissent l'intégrité
- ✅ Audits logs par compagnie
- ✅ Pas d'accès cross-company possible

---

## API et Routes

### Routes Publiques (Sans Authentification)

```
GET  /                          → Page d'accueil
GET  /login                     → Formulaire connexion
POST /login                     → Traiter connexion
GET  /register                  → Formulaire demande d'accès
POST /register                  → Créer demande d'accès
```

### Routes Authentifiées (Utilisateurs)

```
GET  /dashboard                 → Tableau de bord
GET  /documents                 → Liste documents
GET  /documents/search          → Recherche documents
GET  /documents/{id}            → Détail document
GET  /documents/{id}/download   → Télécharger document
POST /documents                 → Créer document
PUT  /documents/{id}            → Modifier document
DELETE /documents/{id}          → Supprimer document

GET  /categories                → Liste catégories
POST /categories                → Créer catégorie
PUT  /categories/{id}           → Modifier catégorie
DELETE /categories/{id}         → Supprimer catégorie

GET  /audit-logs                → Traçabilité & Audits
```

### Routes Admin (Administrateur de Compagnie)

```
GET  /users                     → Gestion utilisateurs
POST /users                     → Créer utilisateur
PUT  /users/{id}                → Modifier utilisateur
DELETE /users/{id}              → Supprimer utilisateur
```

### Routes Super-Admin (Admin Principal)

```
GET  /company-requests          → Liste demandes d'accès
GET  /company-requests/{id}     → Détail demande
POST /company-requests/{id}/approve → Approuver demande
POST /company-requests/{id}/reject  → Rejeter demande

GET  /admin/companies           → Liste toutes compagnies
GET  /admin/users               → Tous les utilisateurs
```

---

## Gestion des Rôles et Permissions

### Middleware: CheckRole

Contrôle l'accès selon le rôle:

```php
// Seul admin peut accéder
Route::post('/approve', 'ApproveController@store')
    ->middleware('CheckRole:admin');

// Rôles multiples autorisés
Route::post('/documents', 'DocumentController@store')
    ->middleware('CheckRole:admin,direction');
```

### Modèle User - Méthodes

```php
$user->isAdmin()      // → true/false
$user->isDirection()  // → true/false
$user->isUser()       // → true/false
$user->getRole()      // → "admin" / "direction" / "user"
```

### Permissions Par Rôle

| Action | Admin | Direction | User |
|--------|-------|-----------|------|
| Créer catégories | ✅ | ✅ | ❌ |
| Ajouter documents | ✅ | ✅ | ✅* |
| Modifier documents | ✅ | ✅ | ✅* |
| Supprimer documents | ✅ | ✅ | ❌ |
| Gérer utilisateurs | ✅ | ❌ | ❌ |
| Approuver demandes | Seul Super-Admin | ❌ | ❌ |

*Limité aux documents propres

---

## Guide du Développeur

### Créer un Nouveau Modèle

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyModel extends Model
{
    protected $fillable = ['company_id', 'name', 'description'];
    
    // Relation avec la compagnie
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
```

### Migration pour Nouveau Modèle

```php
php artisan make:migration create_mymodels_table
```

Dans le fichier créé:

```php
Schema::create('mymodels', function (Blueprint $table) {
    $table->id();
    $table->foreignId('company_id')->constrained('companies');
    $table->string('name');
    $table->text('description')->nullable();
    $table->timestamps();
});
```

### Créer un Contrôleur

```bash
php artisan make:controller MyModelController
```

Exemple:

```php
namespace App\Http\Controllers;

use App\Models\MyModel;

class MyModelController extends Controller
{
    public function index()
    {
        // Filtrer par compagnie
        $items = MyModel::where('company_id', auth()->user()->company_id)
                        ->get();
        return view('mymodels.index', compact('items'));
    }
    
    public function store()
    {
        // Créer en attachant la compagnie
        $item = MyModel::create([
            'company_id' => auth()->user()->company_id,
            'name' => request('name'),
        ]);
        return redirect()->route('mymodels.show', $item);
    }
}
```

### Vues Blade - Composants Réutilisables

Créer `resources/views/components/my-button.blade.php`:

```blade
<button {{ $attributes->merge(['class' => 'btn btn-primary']) }}>
    {{ $slot }}
</button>
```

Utiliser:

```blade
<x-my-button class="btn-lg">Cliquez moi</x-my-button>
```

### Tests avec Pest

Créer un test:

```bash
php artisan pest:test Feature/MyModelTest
```

Exemple:

```php
test('utilisateur peut créer un document', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($user)
        ->post('/documents', ['title' => 'Test'])
        ->assertRedirect();
    
    $this->assertDatabaseHas('documents', ['title' => 'Test']);
});
```

Lancer les tests:

```bash
php artisan test
```

### Utiliser Tinker pour Tester

Accéder à la console PHP interactive:

```bash
php artisan tinker
```

Exemples:

```php
// Créer un utilisateur
$user = \App\Models\User::create(['name' => 'John', 'email' => 'john@test.com']);

// Lister tous les utilisateurs
\App\Models\User::all();

// Filtrer par compagnie
\App\Models\Document::where('company_id', 1)->count();

// Mettre à jour
$user->update(['name' => 'Jane']);
```

### Utiliser Laravel Artisan

Commandes utiles:

```bash
# Voir toutes les routes
php artisan route:list

# Créer une migration
php artisan make:migration <nom>

# Exécuter les migrations
php artisan migrate

# Annuler la dernière migration
php artisan migrate:rollback

# Réinitialiser complètement
php artisan migrate:fresh --seed

# Vider le cache des vues compilées
php artisan view:clear

# Vider tous les caches
php artisan cache:clear

# Générer une clé secrète
php artisan key:generate
```

---

## Dépannage

### Erreur 500 - Undefined variable $slot

**Cause**: Blade n'a pas accès à la variable `$slot`

**Solution**:

```blade
@isset($slot)
    {{ $slot }}
@else
    @yield('content')
@endisset
```

**Puis vider le cache:**
```bash
php artisan view:clear
```

### Erreur 405 - Method Not Allowed

**Cause**: Route non définie pour la méthode HTTP utilisée (GET, POST, etc.)

**Vérifier**: Vérifier que la route existe et utilise la bonne méthode

```bash
php artisan route:list | grep "votre-route"
```

### Erreur "No query results for model"

**Cause**: Tentative d'accéder à un modèle qui n'existe pas

**Solution**:

```php
// ❌ Génère une erreur si pas trouvé
$user = User::findOrFail($id);

// ✅ Retourne null si pas trouvé
$user = User::find($id);

// ✅ Retourne une erreur 404 automatiquement
$user = User::findOrFail($id);
```

### Problème de Permissions Multi-Tenant

**Symptôme**: Un utilisateur d'une compagnie accède aux données d'une autre

**Solution**: Vérifier que la requête filtre par `company_id`:

```php
// ❌ MAUVAIS - Accès à tous les documents
$docs = Document::where('title', 'test')->get();

// ✅ BON - Filtrer par compagnie
$docs = Document::where('company_id', auth()->user()->company_id)
                ->where('title', 'test')
                ->get();
```

### Base de Données - Connexion Refusée

**Cause**: Infos de connexion BD incorrectes ou serveur BD arrêté

**Vérifier**:
```bash
# Tester la connexion MySQL
mysql -h 127.0.0.1 -u root -p

# Vérifier les paramètres .env
cat .env | grep DB_
```

### Fichiers de Log

Fichiers utiles pour déboguer:

```
storage/logs/laravel.log          → Logs applicatifs
storage/logs/laravel-YYYY-MM-DD.log → Log du jour
```

Consulter les logs:

```bash
# Voir les 100 dernières lignes
tail -100 storage/logs/laravel.log

# Suivre les logs en temps réel (Linux/Mac)
tail -f storage/logs/laravel.log
```

### Réinitialiser la Base de Données

⚠️ **Attention: Supprimer toutes les données!**

```bash
php artisan migrate:fresh        # Reset + remigrer
php artisan migrate:fresh --seed # Reset + remigrer + données de test
```

---

## Support et Ressources

### Documentation Externe

- [Laravel Official Docs](https://laravel.com/docs)
- [Blade Templating](https://laravel.com/docs/blade)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [Migrations](https://laravel.com/docs/migrations)

### Contacts Projet

- **Équipe Développement**: development@yaconsulting.com
- **Support Technique**: support@neodata.local
- **Issues Repository**: [GitHub Issues](https://github.com/...)

### Changelog

#### Version 1.0.0 (29 Mai 2026)
- ✅ Système multi-tenant fonctionnel
- ✅ Gestion des demandes d'accès
- ✅ Notifications pop-up 20s
- ✅ Chiffrement AES-256
- ✅ Traçabilité complète (audit logs)
- ✅ Gestion des rôles et permissions

---

**📄 Dernière mise à jour: 29 Mai 2026**

*Cette documentation est maintenue à jour avec le code source. Pour toute question ou correction, veuillez contacter l'équipe de développement.*
