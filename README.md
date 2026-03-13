# 🛡️ SunuKarangué — Plateforme de Gestion d'Assurance

> Application web Laravel de gestion d'assurance multi-rôles (Admin / Agent / Client)  
> avec paiement simulé, déclaration de sinistres et catalogue d'offres en ligne.

---

## 📋 Sommaire

1. [Présentation](#présentation)
2. [Stack technique](#stack-technique)
3. [Prérequis](#prérequis)
4. [Installation](#installation)
5. [Configuration](#configuration)
6. [Structure du projet](#structure-du-projet)
7. [Architecture STI (Single Table Inheritance)](#architecture-sti)
8. [Rôles et permissions](#rôles-et-permissions)
9. [Fonctionnalités détaillées](#fonctionnalités-détaillées)
10. [Routes](#routes)
11. [Modèles et relations](#modèles-et-relations)
12. [Controllers](#controllers)
13. [Vues Blade](#vues-blade)
14. [Base de données](#base-de-données)
15. [Comptes de test](#comptes-de-test)
16. [Design & Charte graphique](#design--charte-graphique)
17. [Bugs connus et solutions](#bugs-connus-et-solutions)
18. [Commandes utiles](#commandes-utiles)

---

## Présentation

**SunuKarangué** (« Notre Protection » en Wolof) est une plateforme de gestion d'assurance développée avec **Laravel 11** en rendu serveur (Blade). Elle permet à trois types d'utilisateurs de gérer l'ensemble du cycle de vie d'un contrat d'assurance : souscription, paiement, déclaration de sinistre et instruction.

### Cas d'usage principaux

| Acteur | Ce qu'il peut faire |
|--------|---------------------|
| **Client** | S'inscrire, souscrire une assurance, payer ses primes, déclarer des sinistres, suivre ses dossiers |
| **Agent** | Gérer ses contrats assignés, instruire les sinistres, suivre ses clients |
| **Admin** | Gérer tous les utilisateurs, le catalogue d'assurances, les contrats, paiements et sinistres |

---

## Stack technique

| Composant | Technologie | Version |
|-----------|-------------|---------|
| Backend | Laravel | 11.x |
| Base de données | PostgreSQL | 15+ |
| Frontend | Blade (SSR) | — |
| CSS Framework | Bootstrap | 5.3.2 |
| Icônes | Bootstrap Icons | 1.11.3 |
| Typographie | Plus Jakarta Sans + Inter | Google Fonts |
| Avatars | UI Avatars API | — |

---

## Prérequis

Avant d'installer le projet, assurez-vous d'avoir :

- **PHP** >= 8.2 avec les extensions : `pdo_pgsql`, `mbstring`, `xml`, `bcmath`, `fileinfo`
- **Composer** >= 2.x
- **PostgreSQL** >= 14
- **Node.js** >= 18 (pour les assets si nécessaire)
- **Git**

---

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/ababacar-wade/Gestion-assurance.git
cd Gestion-assurance
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Copier le fichier d'environnement

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurer la base de données

Créer la base PostgreSQL :

```sql
CREATE DATABASE assurances_laravel;
```

Modifier le fichier `.env` :

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=assurances_laravel
DB_USERNAME=postgres
DB_PASSWORD=votre_mot_de_passe
```

### 5. Lancer les migrations et le seeder

```bash
php artisan migrate:fresh --seed
```

### 6. Créer le lien symbolique pour le stockage

```bash
php artisan storage:link
```

### 7. Lancer le serveur

```bash
php artisan serve
```

L'application est accessible sur **http://127.0.0.1:8000**

---

## Configuration

### Variables d'environnement importantes

```env
APP_NAME=SunuKarangué
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Base de données
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=assurances_laravel
DB_USERNAME=postgres
DB_PASSWORD=

# Stockage fichiers (documents sinistres, photos profil)
FILESYSTEM_DISK=public
```

---

## Structure du projet

```
Gestion-assurance/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AgentController.php       # Tableau de bord agent + clients
│   │   │   ├── AssuranceController.php   # Catalogue + CRUD admin
│   │   │   ├── ContratController.php     # Souscription + gestion
│   │   │   ├── PaymentController.php     # Paiements simulés
│   │   │   └── SinistreController.php    # Déclaration + instruction
│   │   └── Middleware/
│   │       └── RoleMiddleware.php        # Contrôle d'accès par rôle
│   │
│   └── Models/
│       ├── User.php        # Modèle parent STI (contient newFromBuilder)
│       ├── Admin.php       # Sous-modèle STI type=admin
│       ├── Agent.php       # Sous-modèle STI type=agent
│       ├── Client.php      # Sous-modèle STI type=client
│       ├── Assurance.php   # Modèle parent assurances
│       ├── Auto.php        # Sous-modèle STI type=auto
│       ├── Habitat.php     # Sous-modèle STI type=habitat
│       ├── Vie.php         # Sous-modèle STI type=vie
│       ├── Contrat.php     # Contrats d'assurance
│       ├── Payment.php     # Paiements
│       └── Sinistre.php    # Déclarations de sinistres
│
├── database/
│   ├── migrations/         # Migrations des tables
│   └── seeders/
│       └── DatabaseSeeder.php   # Données de test
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php         # Layout public (catalogue)
│       │   ├── auth.blade.php        # Layout authentification
│       │   └── dashboard.blade.php   # Layout dashboard (sidebar)
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── assurances/
│       │   ├── index.blade.php       # Catalogue public
│       │   └── show.blade.php        # Détail offre publique
│       ├── client/
│       │   ├── dashboard.blade.php
│       │   ├── contrats/  (index, show, create)
│       │   ├── payments/  (index, show)
│       │   └── sinistres/ (index, show, create)
│       ├── agent/
│       │   ├── dashboard.blade.php
│       │   ├── contrats/  (index, show, edit)
│       │   ├── sinistres/ (index, show, edit)
│       │   └── clients/   (index, show)
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   ├── clients/    (index, show, edit)
│       │   ├── agents/     (index, show, edit, create)
│       │   ├── assurances/ (index, show, edit, create)
│       │   ├── contrats/   (index, show, edit)
│       │   ├── payments/   (index, show)
│       │   └── sinistres/  (index, show, edit)
│       ├── profil.blade.php
│       └── welcome.blade.php
│
├── routes/
│   └── web.php             # Toutes les routes de l'application
│
└── README.md
```

---

## Architecture STI

Le projet utilise le pattern **Single Table Inheritance (STI)** pour les utilisateurs et les assurances. Cela signifie qu'une seule table stocke plusieurs types d'entités, distingués par une colonne `type`.

### STI Utilisateurs — table `users`

```
users
├── type = 'admin'   → instancié comme App\Models\Admin
├── type = 'agent'   → instancié comme App\Models\Agent
└── type = 'client'  → instancié comme App\Models\Client
```

#### Comment ça marche

La méthode clé est `newFromBuilder()` dans `User.php`. Elle intercepte chaque ligne lue depuis la base et retourne l'instance du bon sous-modèle :

```php
// User.php — SEUL endroit où cette méthode existe
protected static array $stiMap = [
    'admin'  => Admin::class,
    'agent'  => Agent::class,
    'client' => Client::class,
];

public function newFromBuilder($attributes = [], $connection = null)
{
    // (array) obligatoire : PostgreSQL renvoie un stdClass, pas un tableau
    $attrs = (array) $attributes;
    $type  = $attrs['type'] ?? null;
    $class = static::$stiMap[$type] ?? static::class;

    $model = new $class();
    $model->exists = true;
    $model->setRawAttributes($attrs, true);
    $model->setConnection($connection ?? $this->getConnectionName());
    $model->fireModelEvent('retrieved', false);

    return $model;
}
```

#### Règles critiques STI

> ⚠️ Ces règles doivent absolument être respectées pour éviter des bugs silencieux.

1. **`newFromBuilder()` uniquement dans `User`** — jamais dans `Admin`, `Agent`, ou `Client`
2. **`protected $table = 'users'`** dans chaque sous-classe — sinon Laravel cherche une table `admins`, `agents`, etc.
3. **`(array) $attributes`** dans `newFromBuilder()` — PostgreSQL retourne un `stdClass`, pas un tableau
4. **GlobalScope dans `booted()`** — chaque sous-modèle filtre automatiquement sur son type :

```php
// Agent.php
protected static function booted(): void
{
    static::addGlobalScope('agent', fn (Builder $q) => $q->where('type', 'agent'));
    static::creating(function (Agent $model) {
        $model->type = 'agent'; // type auto-assigné à la création
    });
}
```

### STI Assurances — table `assurances`

```
assurances
├── type = 'auto'    → App\Models\Auto
├── type = 'habitat' → App\Models\Habitat
└── type = 'vie'     → App\Models\Vie
```

> ⚠️ **Important** : Pour éviter les bugs de `groupBy` avec les GlobalScopes, toujours passer par `DB::table('assurances')` dans `AssuranceController` au lieu des Eloquent models, puis filtrer manuellement en PHP.

---

## Rôles et permissions

L'accès aux routes est contrôlé par le middleware `RoleMiddleware` enregistré sous l'alias `role`.

```php
// routes/web.php
Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')
    ->group(function () { ... });

Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')
    ->group(function () { ... });

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')
    ->group(function () { ... });
```

### Matrice des permissions

| Fonctionnalité | Client | Agent | Admin |
|----------------|--------|-------|-------|
| Voir catalogue assurances | ✅ | ✅ | ✅ |
| Souscrire un contrat | ✅ | ❌ | ❌ |
| Payer une prime | ✅ | ❌ | ❌ |
| Déclarer un sinistre | ✅ | ❌ | ❌ |
| Voir ses propres contrats | ✅ | ✅ | ✅ |
| Instruire un sinistre | ❌ | ✅ | ✅ |
| Modifier statut contrat | ❌ | ✅ | ✅ |
| Créer/modifier une assurance | ❌ | ❌ | ✅ |
| Gérer les agents | ❌ | ❌ | ✅ |
| Voir tous les clients | ❌ | ✅ (ses clients) | ✅ (tous) |

---

## Fonctionnalités détaillées

### 🔐 Authentification

- Inscription avec rôle `client` par défaut
- Connexion / Déconnexion
- Redirection automatique vers le dashboard selon le rôle

### 📋 Gestion des contrats

- **Souscription** : le client choisit une assurance, la périodicité (mensuelle/annuelle), la date de début
- **Calcul automatique** : prime selon la périodicité, date de fin = date de début + durée
- **Assignation d'agent** : l'agent avec le moins de contrats actifs est automatiquement assigné
- **Statuts** : `en_attente` → `actif` → `suspendu` / `résilié` / `expiré`
- Numéro de contrat généré automatiquement (`CTR-XXXXXXXX`)

### 💳 Paiements (simulation)

- Le paiement est **simulé** (pas de passerelle réelle)
- Méthodes disponibles : Wave, Orange Money, Free Money, Simulation
- Taux de succès de la simulation : **90%**
- Référence de paiement générée automatiquement (`PAY-XXXXXXXX`)
- Historique complet accessible par le client

### ⚠️ Sinistres

- Déclaration avec : contrat concerné, date, lieu, description, montant réclamé, documents joints
- Upload de fichiers (JPG, PNG, PDF — max 5 MB)
- Numéro de sinistre généré automatiquement (`SIN-XXXXXXXX`)
- **Cycle d'instruction** :
  1. `declare` → Client soumet
  2. `en_instruction` → Agent examine
  3. `accepte` / `refuse` → Décision avec montant accordé et notes
  4. `indemnise` → Clôture après virement

### 🛡️ Catalogue d'assurances

Trois types d'assurances disponibles :

| Type | Description |
|------|-------------|
| **Auto** | Tiers simple ou tous risques pour véhicules |
| **Habitat** | Protection logement (essentiel ou premium) |
| **Vie** | Assurance vie avec capital garanti |

---

## Routes

### Routes publiques

```
GET  /                          → Page d'accueil
GET  /assurances                → Catalogue des assurances
GET  /assurances/{id}           → Détail d'une assurance
GET  /connexion                 → Formulaire login
POST /connexion                 → Traitement login
GET  /inscription               → Formulaire inscription
POST /inscription               → Traitement inscription
POST /deconnexion               → Déconnexion
```

### Routes Client (`/client/*`)

```
GET  /client/dashboard          → Tableau de bord client
GET  /client/contrats           → Liste des contrats
GET  /client/contrats/create    → Formulaire souscription
POST /client/contrats           → Soumettre la souscription
GET  /client/contrats/{id}      → Détail d'un contrat
GET  /client/payments           → Historique paiements
GET  /client/payments/{id}      → Détail d'un paiement
POST /client/payments           → Effectuer un paiement
GET  /client/sinistres          → Liste des sinistres
GET  /client/sinistres/create   → Formulaire déclaration
POST /client/sinistres          → Soumettre une déclaration
GET  /client/sinistres/{id}     → Détail d'un sinistre
```

### Routes Agent (`/agent/*`)

```
GET  /agent/dashboard           → Tableau de bord agent
GET  /agent/contrats            → Contrats assignés
GET  /agent/contrats/{id}       → Détail contrat
PUT  /agent/contrats/{id}       → Modifier statut contrat
GET  /agent/sinistres           → Sinistres à instruire
GET  /agent/sinistres/{id}      → Détail sinistre
PUT  /agent/sinistres/{id}      → Instruction (décision + montant)
GET  /agent/clients             → Clients de l'agent
GET  /agent/clients/{id}        → Fiche client
```

### Routes Admin (`/admin/*`)

```
GET  /admin/dashboard           → Tableau de bord admin
# CRUD complet sur :
/admin/clients/*                → Gestion clients
/admin/agents/*                 → Gestion agents
/admin/assurances/*             → Gestion catalogue
/admin/contrats/*               → Gestion contrats
/admin/payments/*               → Suivi paiements
/admin/sinistres/*              → Gestion sinistres
```

---

## Modèles et relations

### User (parent STI)

```php
// Helpers disponibles dans toutes les vues
Auth::user()->isAdmin()    // bool
Auth::user()->isAgent()    // bool
Auth::user()->isClient()   // bool
Auth::user()->nom_complet  // "Prénom Nom"
Auth::user()->photo_url    // URL photo ou avatar généré
```

### Client

```php
$client->contrats()   // HasMany → Contrat
$client->payments()   // HasMany → Payment
$client->sinistres()  // HasMany → Sinistre
```

### Agent

```php
$agent->contrats()         // HasMany → Contrat
$agent->sinistres()        // HasMany → Sinistre
$agent->nb_contrats_actifs // Accesseur (int)
```

### Contrat

```php
$contrat->client()    // BelongsTo → Client
$contrat->agent()     // BelongsTo → Agent
$contrat->assurance() // BelongsTo → Assurance
$contrat->payments()  // HasMany → Payment
$contrat->sinistres() // HasMany → Sinistre
```

### Sinistre

```php
$sinistre->client()  // BelongsTo → Client
$sinistre->agent()   // BelongsTo → Agent
$sinistre->contrat() // BelongsTo → Contrat
```

### Assurance

```php
$assurance->contrats()    // HasMany → Contrat
$assurance->badge_type    // "🚗 Auto" / "🏠 Habitat" / "❤️ Vie"
```

---

## Controllers

### ContratController

Méthodes principales :
- `index()` — liste selon le rôle (client voit les siens, agent voit les assignés, admin voit tout)
- `create()` — formulaire de souscription avec la liste des assurances actives
- `store()` — validation, calcul prime, calcul date_fin, assignation automatique d'agent
- `show()` — détail avec paiements et sinistres liés
- `update()` — modification du statut (agent/admin)

**Assignation automatique d'agent :**
```php
// L'agent avec le moins de contrats actifs est sélectionné
$agent = Agent::actifs()
    ->withCount(['contrats' => fn($q) => $q->where('statut', 'actif')])
    ->orderBy('contrats_count')
    ->first();
```

### SinistreController

- `store()` — génère le numéro, sauvegarde les documents uploadés, notifie l'agent
- `update()` — instruction : statut + montant accordé + notes agent (rôle agent/admin)

### PaymentController

- `store()` — simule le paiement (90% succès), crée l'enregistrement, met à jour le statut du contrat si premier paiement réussi

### AssuranceController

- `catalog()` — **utilise `DB::table()`** (pas Eloquent) pour éviter les conflits STI/GlobalScope, puis filtre manuellement par type en PHP
- `publicShow()` — détail pour la page publique
- `index()` — vue admin avec regroupement par type

### AgentController

- `clientsIndex()` — liste les clients dont l'agent gère au moins un contrat
- `clientShow()` — fiche complète d'un client avec ses contrats et sinistres

---

## Vues Blade

### Layouts

**`dashboard.blade.php`** — Layout principal des espaces connectés
- Sidebar fixe (250px) avec navigation contextuelle selon le rôle
- Topbar sticky avec titre de page, notifications et avatar
- Variables CSS personnalisées pour la cohérence du design
- Responsive mobile avec toggle sidebar
- Bouton déconnexion avec reset complet des styles Bootstrap

**`app.blade.php`** — Layout des pages publiques (catalogue)

**`auth.blade.php`** — Layout épuré pour login/register

### Sections Blade disponibles

```blade
@section('title', 'Titre de l\'onglet')
@section('page-title', 'Titre affiché dans la topbar')

{{-- ⚠️ Concaténation PHP obligatoire dans @section() --}}
@section('page-subtitle', 'Bonjour ' . Auth::user()->prenom . ' !')

@section('content')
    {{-- Contenu de la page --}}
@endsection

@push('scripts')
    {{-- Scripts JS spécifiques à la page --}}
@endpush
```

---

## Base de données

### Table `users`

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | bigint PK | — |
| `type` | varchar | `admin`, `agent`, `client` ← **colonne STI** |
| `nom` | varchar | — |
| `prenom` | varchar | — |
| `email` | varchar unique | — |
| `password` | varchar | bcrypt |
| `telephone` | varchar nullable | — |
| `adresse` | text nullable | — |
| `photo` | varchar nullable | chemin dans storage |
| `is_active` | boolean | default true |
| `date_naissance` | date nullable | clients |
| `cin` | varchar nullable | clients |
| `profession` | varchar nullable | clients |
| `matricule` | varchar nullable | agents (ex: AGT-001) |
| `zone_couverte` | varchar nullable | agents |

### Table `assurances`

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | bigint PK | — |
| `type` | varchar | `auto`, `habitat`, `vie` ← **colonne STI** |
| `nom` | varchar | Ex: "Auto Tiers Simple" |
| `description` | text | — |
| `prix_mensuel` | decimal(10,2) | — |
| `prix_annuel` | decimal(10,2) | — |
| `montant_couverture` | decimal(12,2) | — |
| `garanties` | json | Tableau de strings |
| `is_active` | boolean | — |

### Table `contrats`

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | bigint PK | — |
| `numero_contrat` | varchar unique | CTR-XXXXXXXX |
| `client_id` | FK → users | — |
| `agent_id` | FK → users | — |
| `assurance_id` | FK → assurances | — |
| `statut` | varchar | `en_attente`, `actif`, `suspendu`, `resilié`, `expiré` |
| `date_debut` | date | — |
| `date_fin` | date | — |
| `periodicite` | varchar | `mensuel`, `annuel` |
| `prime` | decimal(10,2) | Calculée automatiquement |
| `notes` | text nullable | Notes internes |

### Table `payments`

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | bigint PK | — |
| `reference` | varchar unique | PAY-XXXXXXXX |
| `contrat_id` | FK → contrats | — |
| `montant` | decimal(10,2) | — |
| `statut` | varchar | `en_attente`, `succes`, `echec` |
| `methode` | varchar | `wave`, `orange_money`, `free_money`, `simulation` |
| `transaction_id` | varchar nullable | ID retourné par la simulation |
| `paye_le` | timestamp nullable | — |
| `payload_retour` | json nullable | Données de retour simulation |

### Table `sinistres`

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | bigint PK | — |
| `numero_sinistre` | varchar unique | SIN-XXXXXXXX |
| `contrat_id` | FK → contrats | — |
| `client_id` | FK → users | — |
| `agent_id` | FK → users nullable | Assigné après réception |
| `statut` | varchar | `declare`, `en_instruction`, `accepte`, `refuse`, `indemnise` |
| `date_sinistre` | date | — |
| `lieu` | varchar nullable | — |
| `description` | text | — |
| `montant_reclame` | decimal(12,2) nullable | — |
| `montant_accorde` | decimal(12,2) nullable | — |
| `documents` | json nullable | Chemins des fichiers uploadés |
| `notes_agent` | text nullable | — |

---

## Comptes de test

Après `php artisan migrate:fresh --seed`, les comptes suivants sont disponibles :

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| **Admin** | admin@assurance.sn | password |
| **Agent 1** | agent@assurance.sn | password |
| **Agent 2** | agent2@assurance.sn | password |
| **Client 1** | client@test.sn | password |
| **Client 2** | client2@test.sn | password |

### Données de démonstration incluses

- 5 assurances (2 Auto, 2 Habitat, 1 Vie)
- 3 contrats avec statuts variés
- 2 paiements
- 1 sinistre en cours d'instruction

---

## Design & Charte graphique

### Variables CSS

```css
:root {
    --orange:        #FF6B2B;   /* Couleur principale */
    --orange-dark:   #E55A1F;   /* Hover boutons */
    --orange-light:  #FFF0EA;   /* Fond badges, hover liens */
    --sidebar-bg:    #1A1A2E;   /* Fond sidebar */
    --sidebar-hover: #16213E;   /* Hover items sidebar */
    --gray-bg:       #F4F6F9;   /* Fond général */
    --gray-border:   #E9ECEF;   /* Bordures */
    --text-dark:     #2D3748;   /* Texte principal */
    --text-muted:    #718096;   /* Texte secondaire */
    --card-shadow:   0 2px 12px rgba(0,0,0,.06);
}
```

### Typographie

- **Titres** : Plus Jakarta Sans (400, 500, 600, 700, 800)
- **Corps** : Inter (400, 500, 600)

### Composants custom

- `.card-sunu` — Carte blanche avec bordure arrondie et ombre légère
- `.btn-orange` — Bouton principal orange
- `.sidebar-link` — Lien de navigation sidebar avec état actif
- `.table-sunu` — Table stylisée avec hover discret
- `.topbar-avatar` — Avatar carré arrondi orange

---

## Bugs connus et solutions

### Bug 1 — `insert into "admins"` (table inexistante)
**Cause** : Les sous-modèles STI n'avaient pas `protected $table = 'users'`  
**Solution** : Ajouter `protected $table = 'users'` dans `Admin`, `Agent`, `Client`

### Bug 2 — `Property [type] does not exist on collection`
**Cause** : `groupBy('type')` sur des modèles STI avec GlobalScopes actifs  
**Solution** : Utiliser `DB::table('assurances')` + filtrage manuel en PHP dans l'AssuranceController

### Bug 3 — `Return value must be of type Agent, Client returned`
**Cause** : `newFromBuilder()` défini dans les sous-classes avec le type de retour `static`  
**Solution** : Supprimer `newFromBuilder()` des sous-classes, le garder uniquement dans `User`

### Bug 4 — `Cannot use object of type stdClass as array`
**Cause** : PostgreSQL passe un objet `stdClass` à `newFromBuilder()`, pas un tableau  
**Solution** : `$attrs = (array) $attributes` avant d'accéder à `$attrs['type']`

### Bug 5 — Nom non affiché dans le sous-titre topbar
**Cause** : `{{ }}` dans `@section()` n'est pas interprété par Blade  
**Solution** : Utiliser la concaténation PHP : `'Bonjour ' . Auth::user()->prenom`

### Bug 6 — Bouton déconnexion sans couleur
**Cause** : Bootstrap 5 impose `color: var(--bs-body-color)` sur les `<button>`  
**Solution** : Reset complet dans `.sidebar-logout` : `background:none; border:none; color:rgba(255,255,255,.45)`

### Bug 7 — Colonne droite déborde hors de l'écran
**Cause** : `.main-content` sans `width: calc(100% - 250px)` ni `overflow-x: hidden`  
**Solution** : Ajouter ces deux propriétés + `min-width: 0` sur `.main-content` et `.page-content`

---

## Commandes utiles

```bash
# Réinitialiser la base + re-seeder
php artisan migrate:fresh --seed

# Vider tous les caches
php artisan optimize:clear

# Créer le lien storage
php artisan storage:link

# Lancer le serveur de développement
php artisan serve

# Voir toutes les routes
php artisan route:list

# Voir les routes d'un groupe
php artisan route:list --path=client
php artisan route:list --path=agent
php artisan route:list --path=admin

# Vider le cache de vues Blade
php artisan view:clear

# Vider le cache de configuration
php artisan config:clear
```

---

## Auteur

Développé par **Ababacar WADE**  
Repo : [github.com/ababacar-wade/Gestion-assurance](https://github.com/ababacar-wade/Gestion-assurance)

---

*SunuKarangué — Votre protection, notre priorité. 🛡️*