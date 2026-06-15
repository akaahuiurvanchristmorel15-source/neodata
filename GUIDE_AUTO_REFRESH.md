# Guide : Rafraîchissement Automatique des Pages

## 📋 Vue d'ensemble

Votre application a été configurée pour **rafraîchir automatiquement les pages** après chaque action réussie (ajout, modification, suppression de documents, etc.).

---

## 🚀 Comment ça fonctionne

### Flux d'une action

1. **Utilisateur soumet une action** 
   - Ajouter un document
   - Modifier une catégorie
   - Créer un utilisateur
   - Etc.

2. **Traitement par le serveur**
   - Le contrôleur Laravel traite la requête
   - Si succès : redirection avec message flash

3. **Affichage du message**
   - La page affiche le message de succès avec l'attribut `data-flash-type="success"`

4. **Rafraîchissement automatique**
   - Le JavaScript détecte le message
   - Attends 2 secondes (délai configurable)
   - Rafraîchit la page

5. **Résultat**
   - Les données sont à jour sur la page

---

## ⚙️ Configuration

### Localisation
Le script de rafraîchissement se trouve dans : **`resources/views/layouts/app.blade.php`** (à la fin, avant la fermeture des balises)

### Paramètres Configurables

Dans le script, vous pouvez modifier l'objet `AUTO_REFRESH_CONFIG` :

```javascript
const AUTO_REFRESH_CONFIG = {
    ENABLED: true,                    // Activer/désactiver le rafraîchissement
    SUCCESS_DELAY: 2000,              // Délai avant rafraîchissement (ms)
    CHECK_INTERVAL: 100,              // Intervalle de vérification (ms)
    EXCLUDE_ROUTES: ['logout'],       // Routes à exclure
};
```

#### Paramètres :
- **ENABLED** : `true` = activé, `false` = désactivé
- **SUCCESS_DELAY** : Temps d'attente avant rafraîchissement (en millisecondes)
  - 1000 = 1 seconde
  - 2000 = 2 secondes (par défaut)
  - 5000 = 5 secondes
- **CHECK_INTERVAL** : Fréquence de vérification du DOM
- **EXCLUDE_ROUTES** : Routes qui ne doivent pas être rafraîchies

---

## 🎯 Vues Intégrées

Le composant `<x-flash-messages />` a été ajouté aux pages suivantes :

1. **Documents** - `resources/views/documents/index.blade.php`
2. **Catégories** - `resources/views/categories/index.blade.php`
3. **Demandes d'accès** - `resources/views/company_requests/index.blade.php`
4. **Utilisateurs** - `resources/views/users/index.blade.php`

---

## 📝 Composant Flash Messages

**Fichier** : `resources/views/components/flash-messages.blade.php`

Ce composant affiche automatiquement les 4 types de messages :
- ✓ **Success** (succès)
- ✕ **Error** (erreur)
- ⚠ **Warning** (avertissement)
- ℹ **Info** (information)

### Utilisation dans une vue
```blade
<!-- Pour afficher les messages flash -->
<x-flash-messages />
```

### Depuis le contrôleur
```php
// Succès
return redirect()->route('documents.index')->with('success', 'Document ajouté avec succès!');

// Erreur
return redirect()->back()->with('error', 'Une erreur est survenue.');

// Avertissement
return redirect()->route('users.index')->with('warning', 'Action déconseillée!');

// Information
return redirect()->route('categories.index')->with('info', 'Veuillez vérifier les données.');
```

---

## 🔄 Alternative : Utiliser AJAX

Si vous préférez **rafraîchir sans recharger toute la page**, vous pouvez décommenter le code AJAX dans `app.blade.php` :

1. Localisez le commentaire `// Alternative : Intercepte les soumissions...`
2. Décommentez le bloc JavaScript
3. Cela interceptera tous les formulaires POST et les soumettra via AJAX
4. La page se rafraîchira sans rechargement complet

**Avantage** : Plus fluide et rapide
**Inconvénient** : Les assets JavaScript/CSS ne se rechargeront pas

---

## ✅ Vérification

Pour vérifier que le système fonctionne :

1. Allez sur n'importe quelle page (Documents, Catégories, etc.)
2. Effectuez une action (ajout, modification, suppression)
3. Observez le message de succès
4. La page devrait se rafraîchir automatiquement après 2 secondes

---

## 🛠️ Résolution des Problèmes

### Le rafraîchissement ne fonctionne pas

**Cause possible** : Le message flash n'a pas l'attribut `data-flash-type="success"`

**Solution** : 
- Utilisez `<x-flash-messages />` dans votre vue
- Ou ajoutez manuellement `data-flash-type="success"` à votre élément d'alerte

### Je veux désactiver le rafraîchissement
```javascript
// Dans app.blade.php, changez :
ENABLED: false,  // au lieu de true
```

### Je veux changer le délai
```javascript
// Changez SUCCESS_DELAY :
SUCCESS_DELAY: 5000,  // au lieu de 2000 (pour 5 secondes)
```

---

## 📚 Fichiers Modifiés

| Fichier | Modification |
|---------|--------------|
| `resources/views/layouts/app.blade.php` | Ajout du script JavaScript |
| `resources/views/components/flash-messages.blade.php` | **NOUVEAU** - Composant d'alertes |
| `resources/views/documents/index.blade.php` | Utilise `<x-flash-messages />` |
| `resources/views/categories/index.blade.php` | Utilise `<x-flash-messages />` |
| `resources/views/company_requests/index.blade.php` | Utilise `<x-flash-messages />` |
| `resources/views/users/index.blade.php` | Utilise `<x-flash-messages />` |

---

## 💡 Conseils

- Le délai de 2 secondes permet à l'utilisateur de lire le message avant le rafraîchissement
- Le MutationObserver surveille le DOM pour détecter les nouveaux messages
- Les messages d'erreur ne déclenchent pas le rafraîchissement (seulement les succès)
- Vous pouvez tester en mettant `SUCCESS_DELAY: 0` pour un rafraîchissement immédiat

---

## 🎨 Personnalisation du Style

Les styles des alertes se trouvent dans le composant `flash-messages.blade.php`. Vous pouvez les modifier :

```css
.alert-success {
    background-color: #ecfdf5;
    border-color: #10b981;
    color: #047857;
}
```

---

**Besoin d'aide ?** Consultez le code du composant ou du script JavaScript directement.
