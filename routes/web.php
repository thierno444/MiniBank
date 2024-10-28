<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DepotController;

// Route d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Routes de connexion
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Routes d'utilisateurs
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/compte', [UserController::class, 'getCompte']);


// Routes de transactions
Route::post('/deposer', [TransactionController::class, 'deposer'])->name('deposer');
Route::post('/retirer', [TransactionController::class, 'retirer'])->name('retirer');
Route::post('/annuler-transaction', [TransactionController::class, 'annulerTransaction'])->name('annuler.transaction');
Route::get('/distributeur-transactions', [TransactionController::class, 'index'])->name('distributeur.transactions');
//Route::get('/transaction/{id}', [TransactionController::class, 'show'])->name('transaction.show');
Route::post('/transferer', [ClientController::class, 'transfer'])->name('transferer');

// Routes pour les transactions clients
Route::middleware('auth')->group(function () {
    Route::get('/client/transactions', [ClientController::class, 'index'])->name('client.transactions');
});

// Tableaux de bord
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/client', [DashboardController::class, 'clientDashboard'])->name('dashboard.client');
    //Route::get('/dashboard/agent', [DashboardController::class, 'agentDashboard'])->name('dashboard.agent');
    Route::get('/dashboard/distributeur', [DashboardController::class, 'distributeurDashboard'])->name('dashboard.distributeur');

    Route::get('/dashboard/agent', [TransactionController::class, 'dashboard'])->name('dashboard.agent1');
    
    Route::get('/Agent/transactions', [TransactionController::class, 'agentTransactions'])->name('transactions');
    Route::get('/transactions/canceled', [TransactionController::class, 'canceledTransactions'])->name('transactions.canceled');
    Route::get('/transaction/check-distributor', [TransactionController::class, 'showTransactionForm'])->name('transaction.checkDistributor');
    Route::get('/transaction/create', [TransactionController::class, 'create'])->name('transaction.create');
    Route::post('/transaction/store', [TransactionController::class, 'store'])->name('transaction.store');
    Route::post('/transaction/cancel/{id}', [TransactionController::class, 'cancel'])->name('transaction.cancel');
    Route::post('/transaction/retrait', [TransactionController::class, 'retirer'])->name('transaction.retrait');

    Route::get('/agent/users', [UserController::class, 'listUsers'])->name('agent.users');
Route::post('/agent/user/{id}/block', [UserController::class, 'blockUser'])->name('user.block');
Route::post('/agent/user/{id}/unblock', [UserController::class, 'unblockUser'])->name('user.unblock');
Route::get('/agent/search-user', [UserController::class, 'searchUser'])->name('agent.searchUser');


    Route::get('/dashboard/data', [UserController::class, 'getDashboardData'])->name('dashboard.data');
    
   
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['verified'])->name('dashboard');

Route::get('/api/dashboard-data/{period}', [DashboardController::class, 'getChartDataByPeriod']);
Route::get('/api/dashboard-data/refresh', [DashboardController::class, 'refreshDashboardData']);


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

// Routes pour les sidebars
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
