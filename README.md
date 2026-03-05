# 🏥 Gestion Assurance - Système de Gestion d'Assurances

![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php)
![Vite](https://img.shields.io/badge/Vite-6.0-646CFF?style=for-the-badge&logo=vite)

## 📝 Présentation du Projet

**Gestion Assurance** est une application web robuste développée avec le framework Laravel, conçue pour simplifier et automatiser la gestion des contrats d'assurance, des sinistres et des paiements. Le système offre une plateforme centralisée pour les administrateurs, les agents et les clients, garantissant une transparence totale et une efficacité accrue dans le traitement des dossiers d'assurance.

## ✨ Fonctionnalités Clés

### 🛡️ Gestion des Utilisateurs & Rôles
- **Administrateur**: Contrôle total du système, gestion des niveaux d'accès.
- **Agent**: Gestion de son portefeuille client, suivi des commissions.
- **Client**: Consultation des contrats souscrits, déclaration de sinistres, suivi des remboursements.

### 📄 Gestion des Contrats (STI - Single Table Inheritance)
Le système prend en charge plusieurs types d'assurances :
- 🚗 **Assurance Auto**: Puissance fiscale, catégorie d'usage, bonus/malus.
- 🏠 **Assurance Habitat**: Nombre de pièces, valeur du contenu.
- 👨‍👩‍👧‍👦 **Assurance Vie**: Désignation des bénéficiaires.

### 💸 Flux Financier & Sinistres
- **Paiements**: Suivi rigoureux des règlements par contrat.
- **Sinistres**: Déclaration et suivi de l'état des sinistres.
- **Remboursements**: Gestion des règlements après validation des sinistres.

## 🚀 Installation & Configuration

Suivez ces étapes pour installer le projet localement :

### Prérequis
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL ou PostgreSQL

### Étapes d'installation

1. **Cloner le projet**
   ```bash
   git clone <url-du-depot>
   cd Gestion_assurance
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Configurer l'environnement**
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```
   *Note : Configurez vos accès à la base de données dans le fichier `.env`.*

4. **Migrations et Seeders**
   ```bash
   php artisan migrate --seed
   ```

5. **Installer les dépendances Frontend**
   ```bash
   npm install
   npm run build
   ```

6. **Lancer le serveur**
   ```bash
   php artisan serve
   ```

## 📊 Architecture de la Base de Données

Le projet utilise une architecture orientée objet avec des relations complexes présentées ci-dessous :

- **STI (Single Table Inheritance)** : Utilisé pour la hiérarchie des types d'assurances (`Auto`, `Habitat`, `Vie`).
- **Relations** :
    - Un `Client` peut souscrire à plusieurs `Contrats`.
    - Un `Contrat` est lié à un type d' `Assurance`.
    - Un `Contrat` peut avoir plusieurs `Paiements` et `Sinistres`.

> [!NOTE]
> Un diagramme de classe Mermaid est disponible dans le fichier `classe.mmd` et une image correspondante `Png_diagramme_classe.png` est présente à la racine du projet.

## 🛠️ Stack Technique
- **Backend**: Laravel 12.x
- **Frontend**: Blade, Vite, AlpineJS / Tailwind CSS
- **Base de données**: Relationnelle (SQL)
- **Outils**: Composer, NPM, Artisan CLI

## 📜 Licence
Ce projet est sous licence MIT.
