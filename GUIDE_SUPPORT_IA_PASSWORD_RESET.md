# Guide : Support Technique IA - Réinitialisation de Mot de Passe

## 📋 Vue d'ensemble

Le **Support Technique IA** est un système sécurisé de réinitialisation de mots de passe qui utilise la vérification par questions-réponses basées sur les informations de l'entreprise de l'utilisateur.

---

## 🎯 Flux Complet

### **Étape 1 : Accès au système**
1. L'utilisateur clique sur **"Support Technique IA"** sur la page de connexion
2. URL : `/password-reset/`

### **Étape 2 : Saisie de l'email**
- L'utilisateur entre son adresse email de connexion
- Le système vérifie que l'email existe dans la base de données
- Le système vérifie que l'utilisateur est associé à une entreprise

### **Étape 3 : Vérification de l'identité (3 Questions)**
L'utilisateur doit répondre à **3 questions** basées sur les informations de son entreprise :

1. **Quel est le nom de votre entreprise ?**
   - Répond doit correspondre à : `Company.nom`

2. **Quel est le numéro de téléphone de votre entreprise ?**
   - Réponse doit correspondre à : `Company.telephone`

3. **Quel est l'adresse email de votre entreprise ?**
   - Réponse doit correspondre à : `Company.email`

**Important :** Les réponses ne sont pas sensibles à la casse (majuscules/minuscules n'ont pas d'importance).

### **Étape 4 : Réinitialisation du mot de passe**
Si toutes les réponses sont correctes :
- L'utilisateur accède au formulaire de nouveau mot de passe
- Crée un nouveau mot de passe sécurisé
- Minimum 8 caractères

### **Étape 5 : Redirection**
- Après succès, redirection automatique vers la page de **login**
- Le mot de passe est maintenant réinitialisé
- L'utilisateur peut se connecter avec le nouveau mot de passe

---

## 🔐 Sécurité

### Mesures de sécurité implémentées :

1. **Vérification multi-étapes**
   - Email valide et existant
   - Réponses correctes aux 3 questions
   - Nouveau mot de passe confirmation

2. **Session temporaire**
   - Les données de réinitialisation sont stockées en session
   - Session détruite après réinitialisation
   - Expiration de session en cas d'abandon

3. **Exclusion de Super Admin**
   - Les Super Admins (sans company_id) ne peuvent pas utiliser ce système
   - Ils doivent contacter l'administrateur système

4. **Validation stricte**
   - Les réponses doivent correspondre exactement
   - Les espaces blancs sont ignorés
   - La casse n'a pas d'importance

---

## 📁 Fichiers Créés/Modifiés

| Fichier | Action | Description |
|---------|--------|-------------|
| `app/Http/Controllers/PasswordResetController.php` | ✨ **CRÉÉ** | Contrôleur principal |
| `routes/web.php` | 📝 **MODIFIÉ** | Ajout des routes |
| `resources/views/auth/password-reset/email.blade.php` | ✨ **CRÉÉ** | Étape 1 : Saisie email |
| `resources/views/auth/password-reset/questions.blade.php` | ✨ **CRÉÉ** | Étape 2 : Questions |
| `resources/views/auth/password-reset/new-password.blade.php` | ✨ **CRÉÉ** | Étape 3 : Nouveau MDP |
| `resources/views/auth/login.blade.php` | 📝 **MODIFIÉ** | Ajout du lien Support IA |

---

## 🛣️ Routes Disponibles

```
GET  /password-reset/                      - Formulaire d'email
POST /password-reset/validate-email        - Validation email
GET  /password-reset/questions             - Affichage des questions
POST /password-reset/verify-answers        - Vérification des réponses
GET  /password-reset/new-password          - Formulaire nouveau MDP
POST /password-reset/update-password       - Mise à jour du MDP
GET  /password-reset/cancel                - Annulation
```

---

## 📝 Exemple d'utilisation

### **Scénario : Jean oublie son mot de passe**

1. **Jean accède au login**
   ```
   https://neodata.local/login
   ```

2. **Jean clique sur "Support Technique IA"**
   ```
   https://neodata.local/password-reset/
   ```

3. **Jean entre son email**
   ```
   jean@neodata.local
   ```

4. **Le système pose 3 questions**
   ```
   Q1: Nom de l'entreprise = "Acme Corp"
   Q2: Numéro téléphone = "+33612345678"
   Q3: Email entreprise = "info@acmecorp.fr"
   ```

5. **Jean répond correctement**
   ```
   ✓ Toutes les réponses sont valides !
   ```

6. **Jean crée un nouveau mot de passe**
   ```
   Nouveau MDP: MySecure!Pass123
   ```

7. **Redirection vers login**
   ```
   Jean peut maintenant se connecter avec le nouveau mot de passe
   ```

---

## 🎨 Personnalisation

### Modifier les questions

Pour changer les questions de vérification, modifiez le contrôleur :

**Fichier** : `app/Http/Controllers/PasswordResetController.php`

**Méthode** : `showQuestions()`

```php
$questions = [
    [
        'id' => 1,
        'question' => 'Votre nouvelle question',
        'answer' => $company->votre_champ,
        'placeholder' => 'Placeholder personnalisé'
    ],
    // ... etc
];
```

### Modifier le message de succès

Le message s'affiche après réinitialisation réussie :

```php
// Dans updatePassword()
return redirect()->route('login')
    ->with('success', 'Votre message personnalisé');
```

---

## ⚙️ Configuration

### Durée de session

Par défaut, la session dure **2 heures**. Pour modifier :

**Fichier** : `config/session.php`

```php
'lifetime' => 120, // en minutes
```

### Minimum de caractères du mot de passe

Par défaut : **8 caractères**

**Fichier** : `app/Http/Controllers/PasswordResetController.php`

```php
'password' => 'required|string|min:8|confirmed', // Changez le 8
```

---

## ❌ Cas d'erreur

### Email non trouvé
```
✕ Cette adresse email n'est pas trouvée dans nos registres.
```
**Cause** : L'email n'existe pas ou n'est pas actif  
**Solution** : Vérifier l'orthographe de l'email

### Utilisateur sans entreprise
```
✕ Erreur : Cet utilisateur n'est pas associé à une entreprise.
```
**Cause** : C'est un Super Admin  
**Solution** : Contacter l'administrateur système

### Réponses incorrectes
```
✕ Une ou plusieurs réponses sont incorrectes. Veuillez réessayer.
```
**Cause** : Une des 3 réponses ne correspond pas  
**Solution** : Vérifier les informations de l'entreprise

### Session expirée
```
✕ Session expirée. Veuillez recommencer.
```
**Cause** : Trop de temps écoulé entre les étapes  
**Solution** : Recommencer le processus

---

## 🧪 Test

Pour tester le système :

1. **Créer un utilisateur test**
   ```
   Email: test@neodata.local
   Password: Test123456
   Entreprise: Acme Corp
   Téléphone: +33612345678
   Email entreprise: info@acmecorp.fr
   ```

2. **Accéder au formulaire**
   ```
   https://neodata.local/password-reset/
   ```

3. **Suivre le flux complet**

4. **Vérifier la redirection vers login**

---

## 📊 Logging et Audit

Le système ne logging pas les tentatives de réinitialisation par défaut.

Pour ajouter du logging, modifiez le contrôleur :

```php
\Log::info('Tentative de réinitialisation', ['email' => $email]);
```

---

## 🔍 FAQ

### **Q: Que se passe-t-il si je me trompe dans les réponses ?**
R: Vous pouvez réessayer autant de fois que vous le souhaitez. Pas de limite de tentatives.

### **Q: Mes données seront-elles perdues ?**
R: Non, seul votre mot de passe est réinitialisé. Toutes vos données restent intactes.

### **Q: Combien de temps la session dure-t-elle ?**
R: Par défaut, 2 heures. Après ce délai, il faut recommencer depuis le début.

### **Q: Puis-je annuler le processus ?**
R: Oui, il y a un bouton "Annuler" sur chaque étape. Vous serez redirigé vers le login.

### **Q: Qui peut voir mon email et mes réponses ?**
R: Personne. Les réponses sont vérifiées localement et ne sont pas envoyées par email.

### **Q: Que faire si j'oublie même les réponses ?**
R: Contactez votre administrateur système qui pourra réinitialiser manuellement.

---

## 🚀 Améliorations Futures Possibles

- [ ] Envoyer un email de confirmation après réinitialisation
- [ ] Ajouter un captcha pour éviter les attaques
- [ ] Implémenter 2FA (authentification à deux facteurs)
- [ ] Enregistrer l'historique des tentatives
- [ ] Questions personnalisées par utilisateur
- [ ] Intégration avec un système d'email OTP

---

## 📞 Support

En cas de problème, contactez l'administrateur système avec les informations suivantes :
- Votre email
- L'étape où vous êtes bloqué
- Le message d'erreur exact

---

**Version** : 1.0  
**Date** : 1 juin 2026  
**Système** : NEODATA PRO
