<?php

/**
 * Ce fichier définit toutes les "portes d'entrée" (routes) de l'application.
 * Chaque ligne ici correspond à une URL que l'utilisateur peut taper ou un lien sur lequel il peut cliquer.
 */

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AssuranceController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AutoController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\HabitatController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SinistreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VieController;
use Illuminate\Support\Facades\Route;

// ── Page d'accueil ────────────────────────────────────────────────────────
// C'est le point de départ quand on arrive sur le site (/)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ── Catalogue public ──────────────────────────────────────────────────────
// Ici, on liste les produits (assurances) pour tout le monde, même ceux pas connectés.
Route::get('/assurances', [AssuranceController::class, 'catalog'])->name('assurances.index');
// Quand on clique sur une assurance précise, on arrive ici.
Route::get('/assurances/{assurance}', [AssuranceController::class, 'publicShow'])->name('assurances.show');

// ── Calcul de prime dynamique (public) ────────────────────────────────────
// Ces routes servent à faire des devis rapides. On envoie des infos, et ça nous renvoie un prix.
Route::post('/assurances/auto/prime',    [AutoController::class,    'calculerPrime'])->name('auto.prime');
Route::post('/assurances/habitat/prime', [HabitatController::class, 'calculerPrime'])->name('habitat.prime');
Route::post('/assurances/vie/prime',     [VieController::class,     'calculerPrime'])->name('vie.prime');

// ── Authentification ──────────────────────────────────────────────────────
// Tout ce qui concerne le login (connexion) et l'inscription. 
// Le middleware 'guest' empêche un utilisateur déjà connecté d'y retourner.
Route::middleware('guest')->group(function () {
    Route::get('/inscription',  [AuthController::class, 'showRegister'])->name('register'); // Formulaire d'inscription
    Route::post('/inscription', [AuthController::class, 'register']);                   // Traitement de l'inscription
    Route::get('/connexion',    [AuthController::class, 'showLogin'])->name('login');      // Formulaire de connexion
    Route::post('/connexion',   [AuthController::class, 'login']);                     // Traitement de la connexion
});

// Pour se déconnecter, il faut forcément être déjà connecté (auth).
Route::post('/deconnexion', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ── Profil (tous rôles connectés) ─────────────────────────────────────────
// C'est la zone commune : n'importe qui (agent, client, admin) peut modifier ses propres infos.
Route::middleware('auth')->group(function () {
    Route::get('/profil',          [UserController::class, 'profil'])->name('profil');          // Voir ses infos
    Route::put('/profil',          [UserController::class, 'updateProfil'])->name('profil.update'); // Modifier nom/email
    Route::put('/profil/password', [UserController::class, 'updatePassword'])->name('profil.password'); // Changer de mot de passe
});

// ── Espace Client ─────────────────────────────────────────────────────────
// Les coulisses pour les clients : gérer leurs propres contrats et sinistres.
Route::middleware(['auth', 'role:client'])
    ->prefix('client')  // Toutes les URLs commenceront par /client/...
    ->name('client.')   // Nommage simplifié : client.dashboard, etc.
    ->group(function () {
        Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');

        // CRUD simplifié pour les contrats du client
        Route::resource('contrats',  ContratController::class)
            ->only(['index', 'show', 'create', 'store']);

        // Le client peut voir ses paiements
        Route::resource('payments',  PaymentController::class)
            ->only(['index', 'show', 'store']);

        // Le client peut déclarer et voir ses sinistres (accidents)
        Route::resource('sinistres', SinistreController::class)
            ->only(['index', 'show', 'create', 'store']);
    });

// ── Espace Agent ──────────────────────────────────────────────────────────
// La zone de travail pour les agents : ils gèrent les dossiers des clients.
Route::middleware(['auth', 'role:agent'])
    ->prefix('agent')
    ->name('agent.')
    ->group(function () {
        Route::get('/dashboard', [AgentController::class, 'dashboard'])->name('dashboard');

        // L'agent peut voir et modifier les contrats (ex: changer le statut)
        Route::resource('contrats',  ContratController::class)
            ->only(['index', 'show', 'edit', 'update']);

        // Gestion des sinistres côté agent (expertises, etc.)
        Route::resource('sinistres', SinistreController::class)
            ->only(['index', 'show', 'edit', 'update']);

        // Consulter la liste des gens qui ont souscrit chez cet agent
        Route::get('/clients',        [AgentController::class, 'clientsIndex'])->name('clients.index');
        Route::get('/clients/{client}', [AgentController::class, 'clientShow'])->name('clients.show');
    });

// ── Espace Admin ──────────────────────────────────────────────────────────
// Le panneau de contrôle suprême. L'admin a la main sur tout le système.
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Gestion complète des acteurs et produits
        Route::resource('clients',    ClientController::class)
            ->except(['create', 'store']); // On ne crée pas de clients ici directement

        Route::resource('agents',     AgentController::class);      // CRUD complet des agents (les employés)
        Route::resource('assurances', AssuranceController::class);   // Les offres de base

        Route::resource('contrats',   ContratController::class)
            ->except(['create', 'store']);

        Route::resource('payments',   PaymentController::class)
            ->only(['index', 'show']);

        Route::resource('sinistres',  SinistreController::class)
            ->except(['create', 'store']);
    });