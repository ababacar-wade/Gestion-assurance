<?php

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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/assurances', [AssuranceController::class, 'catalog'])->name('assurances.index');

// ──────────────────────────────────────────────────────────────────────────────
// Authentification (visiteurs non connectés)
// ──────────────────────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/inscription',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register']);

    Route::get('/connexion',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion',   [AuthController::class, 'login']);
});

Route::post('/deconnexion', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ──────────────────────────────────────────────────────────────────────────────
// Espace Client
// ──────────────────────────────────────────────────────────────────────────────

Route::middleware(['auth', 'role:client'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {

        Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');

        // Contrats du client connecté
        Route::resource('contrats', ContratController::class)
            ->only(['index', 'show', 'create', 'store']);

        // Paiements du client connecté
        Route::resource('payments', PaymentController::class)
            ->only(['index', 'show', 'store']);

        // Sinistres du client connecté
        Route::resource('sinistres', SinistreController::class)
            ->only(['index', 'show', 'create', 'store']);
    });

// ──────────────────────────────────────────────────────────────────────────────
// Espace Agent
// ──────────────────────────────────────────────────────────────────────────────

Route::middleware(['auth', 'role:agent'])
    ->prefix('agent')
    ->name('agent.')
    ->group(function () {

        Route::get('/dashboard', [AgentController::class, 'dashboard'])->name('dashboard');

        // Un agent gère les contrats et sinistres de ses clients
        Route::resource('contrats',  ContratController::class)
            ->only(['index', 'show', 'edit', 'update']);

        Route::resource('sinistres', SinistreController::class)
            ->only(['index', 'show', 'edit', 'update']);

        Route::resource('clients',   ClientController::class)
            ->only(['index', 'show']);
    });

// ──────────────────────────────────────────────────────────────────────────────
// Espace Admin
// ──────────────────────────────────────────────────────────────────────────────

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // CRUD complet sur tout
        Route::resource('clients',    ClientController::class);
        Route::resource('agents',     AgentController::class);
        Route::resource('assurances', AssuranceController::class);
        Route::resource('contrats',   ContratController::class);
        Route::resource('payments',   PaymentController::class);
        Route::resource('sinistres',  SinistreController::class);
    });

    // Calcul de prime dynamique (public)
Route::post('/assurances/auto/prime',    [AutoController::class,    'calculerPrime'])->name('auto.prime');
Route::post('/assurances/habitat/prime', [HabitatController::class, 'calculerPrime'])->name('habitat.prime');
Route::post('/assurances/vie/prime',     [VieController::class,     'calculerPrime'])->name('vie.prime');

// Profil utilisateur (tous rôles connectés)
Route::middleware('auth')->group(function () {
    Route::get('/profil',            [UserController::class, 'profil'])->name('profil');
    Route::put('/profil',            [UserController::class, 'updateProfil'])->name('profil.update');
    Route::put('/profil/password',   [UserController::class, 'updatePassword'])->name('profil.password');
});