<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ClientController;

// Route d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Routes de connexion
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Routes d'utilisateurs
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/compte', [UserController::class, 'getCompte']);

// Routes de transactions
Route::post('/deposer', [TransactionController::class, 'deposer'])->name('deposer');
Route::post('/retirer', [TransactionController::class, 'retirer'])->name('retirer');
Route::post('/annuler-transaction', [TransactionController::class, 'annulerTransaction'])->name('annuler.transaction');
Route::get('/distributeur-transactions', [TransactionController::class, 'index'])->name('distributeur.transactions');
Route::get('/transaction/{id}', [TransactionController::class, 'show'])->name('transaction.show');

// Routes pour les transactions clients
Route::middleware('auth')->group(function () {
    Route::get('/client/transactions', [ClientController::class, 'index'])->name('client.transactions');
});

// Tableaux de bord
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/client', [DashboardController::class, 'clientDashboard'])->name('dashboard.client');
    Route::get('/dashboard/agent', [DashboardController::class, 'agentDashboard'])->name('dashboard.agent');
    Route::get('/dashboard/distributeur', [DashboardController::class, 'distributeurDashboard'])->name('dashboard.distributeur');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');
});

// Routes de profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes de recherche de client
Route::get('/client/search', [ClientController::class, 'search'])->name('client.search');

// Autres routes API
Route::get('/api/get-account-info/{accountNumber}', [TransactionController::class, 'getAccountInfo']);
Route::get('/transactions/{client_id}', [TransactionController::class, 'getTransactions']);

// Route pour générer un QR code
Route::get('/generate-qr-code', [UserController::class, 'generateQrCode'])->middleware('auth');

// Routes pour les sidebars (si nécessaires)
Route::get('/side-nav', function () {
    return view('layouts.sidebar-navbar'); // affichage sidebar et navbar
});
Route::get('/side-navC', function () {
    return view('layouts.sidebar-navbarC'); // affichage sidebar et navbar
});
Route::get('/side-navA', function () {
    return view('layouts.sidebar-navbarA'); // affichage sidebar et navbar
});
Route::get('/side-navD', function () {
    return view('layouts.sidebar-navbarD'); // affichage sidebar et navbar
});

// Auth routes
require __DIR__.'/auth.php';
