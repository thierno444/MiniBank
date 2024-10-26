<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


Route::get('/', function () {
    return view('welcome');
});

// Tableaux de bord
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/client', [DashboardController::class, 'clientDashboard'])->name('dashboard.client');
    Route::get('/dashboard/agent', [DashboardController::class, 'agentDashboard'])->name('dashboard.agent');
    Route::get('/dashboard/distributeur', [DashboardController::class, 'distributeurDashboard'])->name('dashboard.distributeur');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
