# 🚀 NEODATA - Système de Gestion d'Archives Sécurisées

[![Laravel](https://img.shields.io/badge/Laravel-11.0-red?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=flat-square&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-Proprietary-orange?style=flat-square)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Production%20Ready-brightgreen?style=flat-square)](docs)

**Une plateforme web sécurisée de gestion et d'archivage de documents numériques avec architecture multi-tenant.**

---

## 📖 Documentation

> **🇫🇷 Documentation complète en français disponible!**

- **[📘 DOCUMENTATION_FR.md](DOCUMENTATION_FR.md)** - Guide complet du projet
  - Vue d'ensemble, architecture, installation
  - Guide d'utilisation pour users, admins et super-admins
  - Système multi-tenant et gestion des rôles

- **[⚙️ GUIDE_TECHNIQUE.md](GUIDE_TECHNIQUE.md)** - Guide technique pour développeurs
  - Architecture détaillée et patterns MVC
  - Cycle de vie des demandes d'accès
  - Optimisations et performances
  - Sécurité et déploiement

- **[📡 API_DOCUMENTATION.md](API_DOCUMENTATION.md)** - Documentation complète des APIs
  - Toutes les routes et endpoints
  - Exemples de requêtes/réponses
  - Codes d'erreur et gestion d'authentification
  - Guides de test (cURL, Postman, Thunder Client)

---

## ⚡ Quick Start

### Prérequis
- PHP 8.2+
- MySQL 5.7+
- Node.js 18+
- Composer 2.0+

### Installation (5 minutes)

```bash
# 1. Cloner et installer
git clone <repository-url>
cd neodata
composer install
npm install

# 2. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 3. Base de données
# Éditer .env avec vos credentials MySQL
php artisan migrate --seed

# 4. Compiler les assets
npm run build

# 5. Démarrer le serveur
php artisan serve
```

Accédez à: **http://127.0.0.1:8000**

### Comptes de Test

```
Super-Admin (Gestion des entreprises):
  Email: neodata@ya-consulting.com
  Password: password123

Entreprise Test:
  Email: test2@test.com
  Password: RootPass2024!
```

---

## 🎯 Fonctionnalités Principales

✨ **Multi-Tenant** - Chaque entreprise complètement isolée

🔐 **Chiffrement AES-256** - Tous les documents sécurisés

📁 **Organisation Hiérarchique** - Catégories et sous-dossiers

👥 **Gestion des Rôles** - Admin, Direction, Utilisateur

📊 **Traçabilité Complète** - Audits logs de toutes les actions

🔔 **Notifications** - Pop-up 20s pour demandes d'accès

📱 **PWA Compatible** - Installation mobile possible

🌐 **Responsive Design** - Fonctionne sur tout appareil

---

## 📁 Structure du Projet

```
neodata/
├── app/                    # Code applicatif
│   ├── Models/            # Modèles Eloquent
│   ├── Http/Controllers/  # Contrôleurs
│   ├── Http/Middleware/   # Middlewares
│   └── Notifications/     # Emails
├── resources/views/       # Vues Blade
├── routes/                # Définitions des routes
├── database/
│   ├── migrations/        # Schéma BD
│   └── seeders/          # Données de test
├── public/                # Web root (accès public)
├── tests/                 # Tests Pest
└── storage/               # Logs, cache, uploads
```

---

## 🔄 Workflow Principal

### Pour une Nouvelle Entreprise

```
1. Entreprise remplit formulaire public
   ↓
2. Demande enregistrée (status: pending)
   ↓
3. Super-Admin reçoit notification pop-up 20s
   ↓
4. Super-Admin approuve la demande
   ↓
5. Création automatique:
   - Compagnie
   - Catégorie racine
   - Utilisateur admin racine
   ↓
6. Email d'accès envoyé au contact
   ↓
7. Entreprise peut se connecter et gérer ses documents
```

---

## 📊 Architecture Multi-Tenant

Chaque entreprise est complètement isolée:

- **Données Isolées**: Accès uniquement à ses documents/catégories
- **Utilisateurs Isolés**: Chaque compagnie gère ses propres users
- **Traçabilité Isolée**: Logs séparés par entreprise
- **Sécurité Garantie**: Middleware vérifie les permissions

```
NEODATA (Système Central)
├── Compagnie 1 (ya-consulting)
│   ├── 3 Utilisateurs
│   ├── 1 Catégorie racine
│   └── 0 Documents
├── Compagnie 2 (BGTC)
│   ├── 2 Utilisateurs
│   ├── 1 Catégorie racine
│   └── 0 Documents
└── Compagnie 3 (Test Company 2)
    ├── 1 Utilisateur admin
    ├── 1 Catégorie racine
    └── 0 Documents
```

---

## 🛠️ Commandes Utiles

```bash
# Développement
npm run dev              # Hot reload (CSS/JS)
php artisan serve       # Serveur local
php artisan test        # Lancer les tests

# Base de données
php artisan migrate     # Exécuter migrations
php artisan migrate:fresh --seed   # Reset complet
php artisan tinker      # Console PHP interactive

# Maintenance
php artisan cache:clear      # Vider le cache
php artisan view:clear       # Vider les vues compilées
php artisan route:list       # Voir toutes les routes

# Production
npm run build           # Compiler les assets
php artisan migrate --force  # Migrer en production
```

---

## 🔐 Sécurité

- ✅ Validation stricte des entrées
- ✅ Protection CSRF sur tous les formulaires
- ✅ Hachage des mots de passe (bcrypt)
- ✅ Chiffrement AES-256 des documents
- ✅ Audit logs complets
- ✅ Isolation multi-tenant garantie
- ✅ Permissions par rôles
- ✅ Rate limiting optionnel

---

## 📊 Modèles de Données

### User
- Appartient à une Company
- Rôle: admin | direction | user
- Peut créer/consulter/télécharger documents

### Company
- Représente une entreprise
- Isolée des autres companies
- Possède Users, Categories, Documents

### Category
- Hiérarchie de dossiers
- Support des sous-catégories (parent_id)
- Isolée par Company

### Document
- Stocké chiffré (AES-256)
- Hachage MD5 pour intégrité
- Traçabilité des téléchargements

### CompanyAccessRequest
- Demande d'accès d'une entreprise
- Status: pending | approved | rejected
- Crée automatiquement Company + root User

### LogAction
- Enregistre chaque action (create, update, delete, download, view)
- Utile pour audits et traçabilité

---

## 🧪 Tests

Utilise **Pest** pour les tests:

```bash
# Lancer tous les tests
php artisan test

# Tests spécifiques
php artisan test tests/Feature/DocumentTest.php

# Avec couverture
php artisan test --coverage
```

---

## 🌐 Déploiement

### Sur Heroku

```bash
heroku create neodata-app
git push heroku main
heroku run php artisan migrate --force
```

### Sur un VPS (Nginx)

```bash
# SSH sur le serveur
ssh user@server.com

# Cloner et installer
git clone <url> /var/www/neodata
cd /var/www/neodata
composer install --no-dev
npm run build

# Configurer Nginx
# Voir GUIDE_TECHNIQUE.md pour la configuration
```

---

## 📞 Support & Contact

- **Documentation**: Voir fichiers .md du projet
- **Équipe Dev**: development@yaconsulting.com
- **Support Technique**: support@neodata.local
- **Issues**: [GitHub Issues](https://github.com/...)

---

## 📜 License

Proprietary © 2026 YA Consulting. Tous droits réservés.

---

## 📝 Changelog

### v1.0.0 (29 Mai 2026)
- ✅ Système multi-tenant fonctionnel
- ✅ Gestion des demandes d'accès
- ✅ Notifications pop-up 20s
- ✅ Chiffrement des documents
- ✅ Traçabilité complète
- ✅ Gestion des rôles et permissions
- ✅ Documentation complète en français

---

## 🙏 Remerciements

- Laravel team pour l'excellent framework
- Tailwind CSS pour le design
- Pest pour les tests
- Et tous les contributeurs!

---

**✨ Fait avec ❤️ par l'équipe YA Consulting**

*Dernière mise à jour: 29 Mai 2026*
