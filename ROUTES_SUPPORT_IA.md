# 🗺️ Carte des Routes - Support Technique IA

## Routes Disponibles

### **Groupe Middleware : guest** (Non-connectés)

```
┌─────────────────────────────────────────────────────────┐
│           SUPPORT TECHNIQUE IA - ROUTES                 │
└─────────────────────────────────────────────────────────┘

PRÉFIXE: /password-reset
NOM: password-reset.*

├── 📋 ÉTAPE 1 : EMAIL
│   ├─ GET   /password-reset/
│   │        → password-reset.email
│   │        → PasswordResetController::showEmailForm()
│   │        → Affiche: resources/views/auth/password-reset/email.blade.php
│   │
│   └─ POST  /password-reset/validate-email
│            → password-reset.validate-email
│            → PasswordResetController::validateEmail()
│            → Vérifie l'email et redirige vers les questions
│
├── ❓ ÉTAPE 2 : QUESTIONS
│   ├─ GET   /password-reset/questions
│   │        → password-reset.questions
│   │        → PasswordResetController::showQuestions()
│   │        → Affiche: resources/views/auth/password-reset/questions.blade.php
│   │        → Montre 3 questions de vérification
│   │
│   └─ POST  /password-reset/verify-answers
│            → password-reset.verify-answers
│            → PasswordResetController::verifyAnswers()
│            → Vérifie les réponses et redirige vers le formulaire MDP
│
├── 🔐 ÉTAPE 3 : NOUVEAU MOT DE PASSE
│   ├─ GET   /password-reset/new-password
│   │        → password-reset.new-password
│   │        → PasswordResetController::showNewPasswordForm()
│   │        → Affiche: resources/views/auth/password-reset/new-password.blade.php
│   │
│   └─ POST  /password-reset/update-password
│            → password-reset.update-password
│            → PasswordResetController::updatePassword()
│            → Mettre à jour le mot de passe
│            → Redirige vers login avec succès
│
└── ❌ ANNULATION
    └─ GET   /password-reset/cancel
             → password-reset.cancel
             → PasswordResetController::cancel()
             → Nettoie la session et redirige vers login

```

---

## Flux de Navigation

```
┌──────────────────┐
│  LOGIN PAGE      │
│  (login)         │
└────────┬─────────┘
         │
         │ Clique "Support Technique IA"
         │ route('password-reset.email')
         ↓
┌──────────────────────────────────┐
│  ÉTAPE 1 : EMAIL                 │
│  /password-reset/                │
│  email.blade.php                 │
└────────┬─────────────────────────┘
         │
         │ POST /validate-email
         │ Validation email + vérif company_id
         │
         ├─ ✗ Erreur: Email non trouvé
         │  └─ Reste sur la même page avec erreur
         │
         └─ ✓ Succès: Email valide
            └─ Session: password_reset_email
               ↓
┌──────────────────────────────────┐
│  ÉTAPE 2 : QUESTIONS             │
│  /password-reset/questions       │
│  questions.blade.php             │
│                                  │
│  Q1: Nom entreprise              │
│  Q2: Téléphone entreprise        │
│  Q3: Email entreprise            │
└────────┬─────────────────────────┘
         │
         │ POST /verify-answers
         │ Vérification des 3 réponses
         │
         ├─ ✗ Erreur: Une/plusieurs réponses incorrectes
         │  └─ Reste sur la même page avec erreur
         │
         └─ ✓ Succès: Toutes les réponses correctes
            └─ Session: password_reset_verified = true
               ↓
┌──────────────────────────────────┐
│  ÉTAPE 3 : NOUVEAU MOT DE PASSE  │
│  /password-reset/new-password    │
│  new-password.blade.php          │
│                                  │
│  Nouveau MDP (min 8 caractères)  │
│  Confirmation MDP                │
└────────┬─────────────────────────┘
         │
         │ POST /update-password
         │ Mettre à jour le mot de passe
         │
         ├─ ✗ Erreur: MDP trop court ou non confirmé
         │  └─ Reste sur la même page avec erreur
         │
         └─ ✓ Succès: MDP réinitialisé
            └─ Nettoie la session
               ↓
┌──────────────────┐
│  REDIRECT LOGIN  │
│  (login)         │
│  + Message succès│
└──────────────────┘
         │
         ↓
    Utilisateur peut
   se connecter avec
    nouveau mot de passe
```

---

## Données de Session

### **session['password_reset_email']**
```
Stocke : l'adresse email de l'utilisateur
Durée : Entre les étapes 1 et 3
Effacé : Après réinitialisation réussie ou annulation
```

### **session['password_reset_verified']**
```
Stocke : true/false (vérification complétée)
Durée : Entre les étapes 2 et 3
Effacé : Après réinitialisation réussie ou annulation
```

---

## Contrôleur - Méthodes

```php
PasswordResetController
│
├─ showEmailForm()
│  └─ Affiche le formulaire d'email
│
├─ validateEmail(Request $request)
│  └─ Valide l'email et crée la session
│
├─ showQuestions()
│  └─ Charge et affiche les 3 questions
│     └─ Questions basées sur Company data
│
├─ verifyAnswers(Request $request)
│  └─ Vérifie les réponses
│     └─ Normalise et compare
│
├─ showNewPasswordForm()
│  └─ Affiche le formulaire de nouveau MDP
│
├─ updatePassword(Request $request)
│  └─ Met à jour le mot de passe
│     └─ Hash + validation
│
└─ cancel()
   └─ Nettoie la session
```

---

## Validations

### Email (Étape 1)
```
✓ Requis
✓ Format email valide
✓ Existe dans users table
✓ Utilisateur associé à une company_id
```

### Réponses (Étape 2)
```
✓ Toutes les 3 requises
✓ Normalisation (minuscules, trim)
✓ Comparaison avec Company data
✓ Toutes les 3 doivent être correctes
```

### Mot de Passe (Étape 3)
```
✓ Requis
✓ Minimum 8 caractères
✓ Confirmation doit correspondre
✓ Hash sécurisé (Illuminate\Support\Facades\Hash)
```

---

## Fichiers Vue

### email.blade.php
- Formulaire pour entrer l'email
- Affiche un guide "Comment ça fonctionne"
- Boutons: Continuer / Retour à la connexion

### questions.blade.php
- Affiche les 3 questions
- Barre de progression (étape 2/3)
- Conseils: "Réponses non sensibles à la casse"
- Boutons: Vérifier / Annuler

### new-password.blade.php
- Formulaire nouveau mot de passe
- Confirmtion mot de passe
- Barre de progression (étape 3/3)
- Conseils de sécurité
- Boutons: Réinitialiser / Annuler

---

## Points d'Intégration

### Page Login
- Lien "Support Technique IA" → route('password-reset.email')
- Situé dans la section "remember-row"
- Styling: .forgot-password

### Composant Flash Messages
- Utilise `<x-flash-messages />` pour les alertes
- Erreurs de validation
- Messages de succès

### Base de Données
- Utilise uniquement: users, companies tables
- Pas de migration nécessaire
- Aucune table additionnelle requise

---

## Sécurité - Vérifications

✅ **Middleware 'guest'** - Accessible qu'aux non-connectés  
✅ **Vérification session** - Check à chaque étape  
✅ **Email valide** - Existence en BD  
✅ **Réponses strictes** - Toutes les 3 correctes  
✅ **Hash mot de passe** - Illuminate Hash  
✅ **Session temporaire** - Nettoie après succès  
✅ **Redirection sécurisée** - Vers login  

---

## Variables d'Environnement

Aucune configuration requise. Utilise :
- `config('session.lifetime')` pour la durée de session
- `Hash::make()` pour le hashing

---

## Troubleshooting Routes

```
Erreur 404 sur /password-reset/
→ Vérifier que les routes sont bien enregistrées en web.php

Erreur 500 sur POST /validate-email
→ Vérifier que PasswordResetController existe

Session perdue entre les étapes
→ Vérifier la configuration de session dans config/session.php

Email ne valide pas
→ Vérifier que User.email existe en BD
→ Vérifier que User.company_id n'est pas null
```

---

**Version** : 1.0  
**Date** : 1 juin 2026  
**Complétude** : 100%
