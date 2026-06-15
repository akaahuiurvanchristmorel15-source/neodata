# 📡 Guide des API & Routes - NEODATA

**Version:** 1.0.0  
**Format:** RESTful API avec Laravel

---

## 🔗 Table des Routes

### Routes Publiques

| Méthode | Route | Description | Auth |
|---------|-------|-------------|------|
| `GET` | `/` | Page d'accueil | ❌ |
| `GET` | `/login` | Formulaire connexion | ❌ |
| `POST` | `/login` | Traiter la connexion | ❌ |
| `POST` | `/logout` | Déconnexion | ✅ |
| `GET` | `/register` | Formulaire demande accès | ❌ |
| `POST` | `/register` | Créer demande accès | ❌ |

### Routes Authentifiées (Tous les utilisateurs)

#### Dashboard & Navigation

```
GET  /dashboard                 Tableau de bord principal
```

#### Gestion des Documents

```
GET    /documents               Liste des documents (avec pagination)
POST   /documents               Créer un nouveau document
GET    /documents/{id}          Afficher un document
PUT    /documents/{id}          Modifier un document
DELETE /documents/{id}          Supprimer un document (soft-delete)
GET    /documents/{id}/download Télécharger un document
GET    /documents/search        Rechercher des documents
```

#### Gestion des Catégories

```
GET    /categories              Liste des catégories
POST   /categories              Créer une catégorie
GET    /categories/{id}         Afficher une catégorie
PUT    /categories/{id}         Modifier une catégorie
DELETE /categories/{id}         Supprimer une catégorie
```

#### Traçabilité & Audits

```
GET    /audit-logs              Voir tous les audit logs de la compagnie
GET    /audit-logs/{id}         Détail d'un audit log
```

### Routes Admin de Compagnie

#### Gestion des Utilisateurs (Admin seul)

```
GET    /users                   Liste les utilisateurs de la compagnie
POST   /users                   Créer un nouvel utilisateur
GET    /users/{id}              Afficher un utilisateur
PUT    /users/{id}              Modifier un utilisateur
DELETE /users/{id}              Supprimer un utilisateur
```

### Routes Super-Admin (Admin Principal)

#### Gestion des Demandes d'Accès

```
GET    /company-requests        Liste toutes les demandes
GET    /company-requests/{id}   Détail d'une demande
POST   /company-requests/{id}/approve  Approuver une demande
POST   /company-requests/{id}/reject   Rejeter une demande
```

---

## 📝 Exemples de Requêtes

### 1. Connexion

**Endpoint:**
```
POST /login
```

**Body (JSON ou Form):**
```json
{
  "email": "test2@test.com",
  "password": "RootPass2024!"
}
```

**Réponse (200 OK):**
```json
{
  "user": {
    "id": 1,
    "name": "Test User 2",
    "email": "test2@test.com",
    "company_id": 1,
    "role": "admin"
  },
  "redirect": "/dashboard"
}
```

**Erreur (401):**
```json
{
  "error": "Les identifiants fournis sont incorrects."
}
```

---

### 2. Créer un Document

**Endpoint:**
```
POST /documents
Headers:
  - Authorization: Bearer {token}
  - Content-Type: multipart/form-data
```

**Body (Form-Data):**
```
title: "Contrat important 2024"
description: "Contrat signé avec acheteur"
category_id: 5
file: <fichier.pdf>
```

**Réponse (201 Created):**
```json
{
  "id": 42,
  "title": "Contrat important 2024",
  "description": "Contrat signé avec acheteur",
  "category_id": 5,
  "company_id": 1,
  "file_hash": "5d41402abc4b2a76b9719d911017c592",
  "created_at": "2026-05-29T15:30:00Z",
  "created_by": 1,
  "size_bytes": 245620
}
```

**Erreur (422 Unprocessable):**
```json
{
  "errors": {
    "title": ["Le titre est obligatoire"],
    "file": ["Le fichier doit être inférieur à 100MB"]
  }
}
```

---

### 3. Lister les Documents

**Endpoint:**
```
GET /documents?page=1&per_page=15&sort=created_at&order=desc
```

**Réponse (200 OK):**
```json
{
  "data": [
    {
      "id": 42,
      "title": "Contrat 2024",
      "category_id": 5,
      "category_name": "Contrats",
      "created_at": "2026-05-29T15:30:00Z",
      "downloads": 3,
      "size_bytes": 245620
    },
    {
      "id": 41,
      "title": "Facture mai",
      "category_id": 8,
      "category_name": "Factures",
      "created_at": "2026-05-28T10:15:00Z",
      "downloads": 1,
      "size_bytes": 125000
    }
  ],
  "pagination": {
    "total": 47,
    "per_page": 15,
    "current_page": 1,
    "last_page": 4,
    "from": 1,
    "to": 15
  },
  "links": {
    "first": "/documents?page=1",
    "last": "/documents?page=4",
    "next": "/documents?page=2"
  }
}
```

---

### 4. Rechercher des Documents

**Endpoint:**
```
GET /documents/search?q=contrat&category_id=5
```

**Réponse (200 OK):**
```json
{
  "query": "contrat",
  "results": [
    {
      "id": 42,
      "title": "Contrat important 2024",
      "description": "Contrat signé avec acheteur",
      "category": "Contrats",
      "relevance": 0.95
    },
    {
      "id": 15,
      "title": "Modèle de contrat standard",
      "description": "Template pour nouveaux contrats",
      "category": "Templates",
      "relevance": 0.87
    }
  ],
  "count": 2
}
```

---

### 5. Télécharger un Document

**Endpoint:**
```
GET /documents/{id}/download
```

**Réponse (200 OK):**
```
Content-Type: application/pdf
Content-Disposition: attachment; filename="contrat-2024.pdf"
Content-Length: 245620

[Contenu binaire du fichier déchiffré]
```

**Effet secondaire:**
- ✅ Enregistre un audit log (action: `downloaded`)
- ✅ Incrémente le compteur de téléchargements

**Erreur (404):**
```json
{
  "error": "Document non trouvé"
}
```

---

### 6. Créer une Catégorie

**Endpoint:**
```
POST /categories
```

**Body (JSON):**
```json
{
  "name": "Contrats 2026",
  "description": "Tous les contrats de 2026",
  "parent_id": null
}
```

**Réponse (201 Created):**
```json
{
  "id": 12,
  "company_id": 1,
  "name": "Contrats 2026",
  "description": "Tous les contrats de 2026",
  "parent_id": null,
  "created_at": "2026-05-29T15:35:00Z"
}
```

---

### 7. Créer une Sous-catégorie

**Endpoint:**
```
POST /categories
```

**Body (JSON):**
```json
{
  "name": "Contrats clients",
  "description": "Contrats avec nos clients",
  "parent_id": 12
}
```

**Réponse (201 Created):**
```json
{
  "id": 13,
  "company_id": 1,
  "name": "Contrats clients",
  "parent_id": 12,
  "path": "Contrats 2026 / Contrats clients"
}
```

---

### 8. Lister les Utilisateurs (Admin seul)

**Endpoint:**
```
GET /users
Headers:
  - Authorization required
  - Role: admin
```

**Réponse (200 OK):**
```json
{
  "data": [
    {
      "id": 5,
      "name": "Test User 2",
      "email": "test2@test.com",
      "role": "admin",
      "created_at": "2026-05-29T14:00:00Z",
      "last_login": "2026-05-29T15:45:00Z"
    },
    {
      "id": 6,
      "name": "Alice Dupont",
      "email": "alice@company.com",
      "role": "direction",
      "created_at": "2026-05-28T10:00:00Z",
      "last_login": "2026-05-29T09:30:00Z"
    }
  ],
  "total": 2
}
```

**Erreur (403 Forbidden):**
```json
{
  "error": "Vous n'avez pas l'autorisation d'accéder à cette ressource"
}
```

---

### 9. Créer un Utilisateur (Admin seul)

**Endpoint:**
```
POST /users
```

**Body (JSON):**
```json
{
  "name": "Bob Martin",
  "email": "bob@company.com",
  "password": "SecurePass123!",
  "role": "direction"
}
```

**Réponse (201 Created):**
```json
{
  "id": 7,
  "name": "Bob Martin",
  "email": "bob@company.com",
  "company_id": 1,
  "role": "direction",
  "created_at": "2026-05-29T15:50:00Z"
}
```

---

### 10. Voir les Audit Logs

**Endpoint:**
```
GET /audit-logs?action=downloaded&per_page=20
```

**Réponse (200 OK):**
```json
{
  "data": [
    {
      "id": 1,
      "user": "Alice Dupont",
      "action": "downloaded",
      "action_label": "Téléchargé",
      "subject": "Contrat 2024",
      "subject_type": "document",
      "timestamp": "2026-05-29T15:45:00Z",
      "ip_address": "192.168.1.100"
    },
    {
      "id": 2,
      "user": "Bob Martin",
      "action": "created",
      "action_label": "Créé",
      "subject": "Catégorie: Factures",
      "subject_type": "category",
      "timestamp": "2026-05-29T14:20:00Z",
      "ip_address": "192.168.1.101"
    }
  ],
  "total": 247
}
```

---

### 11. Lister les Demandes d'Accès (Super-Admin)

**Endpoint:**
```
GET /company-requests?status=pending
Headers:
  - Authorization required
  - Role: admin (Super-Admin)
```

**Réponse (200 OK):**
```json
{
  "data": [
    {
      "id": 1,
      "company_name": "Test Company 2",
      "company_email": "contact@test2.com",
      "contact_name": "Test User 2",
      "contact_email": "test2@test.com",
      "telephone": "0612345678",
      "adresse": "123 Rue Test",
      "reason": "Test pending request",
      "status": "pending",
      "created_at": "2026-05-29T15:06:00Z",
      "approved_at": null
    },
    {
      "id": 2,
      "company_name": "BGTC",
      "company_email": "bgpconsul@gmail.com",
      "contact_name": "messie",
      "status": "pending",
      "created_at": "2026-05-29T15:05:00Z"
    }
  ],
  "count": 2
}
```

---

### 12. Approuver une Demande d'Accès (Super-Admin)

**Endpoint:**
```
POST /company-requests/{id}/approve
```

**Body:** Aucun body requis (ou optionnel pour ajouter des notes)

**Réponse (200 OK):**
```json
{
  "message": "Demande approuvée avec succès",
  "company": {
    "id": 5,
    "nom": "Test Company 2",
    "email": "contact@test2.com"
  },
  "root_user": {
    "email": "test2@test.com",
    "temporary_password": "GeneratedPass2024!"
  },
  "redirect": "/company-requests"
}
```

**Email envoyé au contact:**
```
Sujet: Votre accès à NEODATA - Test Company 2

Bienvenue,

Votre demande d'accès à NEODATA a été approuvée!

Vous pouvez vous connecter avec:
- Email: test2@test.com
- Mot de passe temporaire: GeneratedPass2024!

Accès: https://neodata.example.com/login

Nous vous recommandons de changer votre mot de passe dès la première connexion.

Cordialement,
Équipe NEODATA
```

---

### 13. Rejeter une Demande d'Accès (Super-Admin)

**Endpoint:**
```
POST /company-requests/{id}/reject
```

**Body (JSON):**
```json
{
  "reason": "Informations incomplètes"
}
```

**Réponse (200 OK):**
```json
{
  "message": "Demande rejetée",
  "status": "rejected"
}
```

---

## 🔐 Authentification

### Méthodes Supportées

1. **Session Laravel** (Par défaut)
   - Basée sur les cookies
   - CSRF protection automatique
   - Idéale pour applications web

2. **Token API** (Optionnel)
   - Pour consommation API
   - Token Bearer dans header Authorization
   - À implémenter avec Laravel Sanctum

### Headers Requis

```
GET /protected-resource HTTP/1.1
Host: neodata.example.com
Authorization: Bearer {token}
X-CSRF-TOKEN: {csrf_token}
Accept: application/json
```

### Gestion des Sessions

```php
// Connaître l'utilisateur courant
auth()->user()          // Objet User complet
auth()->id()            // ID de l'utilisateur
auth()->user()->company_id  // Compagnie de l'utilisateur

// Vérifier l'authentification
auth()->check()         // true/false
auth()->guest()         // true si pas authentifié

// Vérifier le rôle
auth()->user()->isAdmin()       // true/false
auth()->user()->isDirection()   // true/false
auth()->user()->isUser()        // true/false
```

---

## 🚨 Codes d'Erreur

| Code | Signification | Exemple |
|------|---------------|---------|
| **200** | OK | Requête réussie |
| **201** | Created | Ressource créée |
| **204** | No Content | Suppression réussie |
| **400** | Bad Request | Données invalides |
| **401** | Unauthorized | Authentification requise |
| **403** | Forbidden | Pas d'autorisation |
| **404** | Not Found | Ressource non trouvée |
| **405** | Method Not Allowed | Méthode HTTP non autorisée |
| **422** | Unprocessable Entity | Validation échouée |
| **500** | Server Error | Erreur serveur |

### Format des Erreurs

```json
// Erreur simple (401)
{
  "message": "Unauthenticated"
}

// Erreur de validation (422)
{
  "message": "The given data was invalid",
  "errors": {
    "email": ["L'email doit être valide"],
    "password": ["Le mot de passe doit avoir au moins 8 caractères"]
  }
}

// Erreur serveur (500)
{
  "message": "Server error",
  "error": "Exception message",
  "line": 42,
  "file": "app/Models/Document.php"
}
```

---

## 📊 Pagination

Toutes les listes supportent la pagination:

```
GET /documents?page=2&per_page=20
```

**Réponse:**
```json
{
  "data": [...],
  "pagination": {
    "total": 145,
    "per_page": 20,
    "current_page": 2,
    "last_page": 8,
    "from": 21,
    "to": 40
  },
  "links": {
    "first": "...",
    "last": "...",
    "next": "...",
    "prev": "..."
  }
}
```

---

## 🔄 Filtrage et Tri

### Exemples de Filtres

```
GET /documents?
  category_id=5&
  created_after=2026-05-01&
  created_before=2026-05-31&
  sort=created_at&
  order=desc&
  page=1
```

### Paramètres de Tri

```
sort=created_at     Trier par date de création
sort=title          Trier par titre
sort=size           Trier par taille

order=asc           Ordre croissant
order=desc          Ordre décroissant
```

---

## 🧪 Tester les APIs

### Avec cURL

```bash
# Login
curl -X POST http://127.0.0.1:8000/login \
  -d "email=test2@test.com&password=RootPass2024!"

# Lister les documents
curl -X GET http://127.0.0.1:8000/documents \
  -H "Authorization: Bearer {token}"

# Créer un document
curl -X POST http://127.0.0.1:8000/documents \
  -H "Authorization: Bearer {token}" \
  -F "title=Mon document" \
  -F "file=@/path/to/file.pdf"
```

### Avec Postman

1. Importer la collection depuis `docs/postman-collection.json`
2. Configurer les variables d'environnement:
   - `base_url`: http://127.0.0.1:8000
   - `token`: (défini automatiquement après login)
3. Exécuter les requêtes

### Avec Thunder Client (VS Code)

Fichier: `requests.http`

```http
### Login
POST http://127.0.0.1:8000/login
Content-Type: application/json

{
  "email": "test2@test.com",
  "password": "RootPass2024!"
}

### Get Documents
@token = eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
GET http://127.0.0.1:8000/documents
Authorization: Bearer @token

### Create Document
POST http://127.0.0.1:8000/documents
Authorization: Bearer @token
Content-Type: multipart/form-data; boundary=----WebKitFormBoundary

----WebKitFormBoundary
Content-Disposition: form-data; name="title"

Mon Document
----WebKitFormBoundary--
```

---

**📄 Dernière mise à jour: 29 Mai 2026**

*Documentation des API toujours à jour avec les dernières versions.*
