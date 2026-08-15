<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProduitController;

// Pages Client
Route::get('/', [ClientController::class, 'index'])->name('index');
Route::get('/apropos', [ClientController::class, 'apropos'])->name('apropos');
Route::get('/contact', [ClientController::class, 'contact'])->name('contact');

// Authentification
Route::get('/connexion', [AuthController::class, 'loginForm'])->name('login');
Route::post('/connexion', [AuthController::class, 'login']);
Route::get('/inscription', [AuthController::class, 'registerForm'])->name('register');
Route::post('/inscription', [AuthController::class, 'register']);
Route::get('/inscription_livreur', [AuthController::class, 'registerLivreurForm'])->name('register.livreur');
Route::post('/inscription_livreur', [AuthController::class, 'registerLivreur']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Produits et Promos
Route::get('/produits', [ProduitController::class, 'index'])->name('produits.index');
Route::get('/promos', [ProduitController::class, 'promos'])->name('promos.index');

// Profils (Dashboard utilisateur)
Route::group([], function () {
    Route::get('/profil', [DashboardController::class, 'profil'])->name('profil');
    Route::get('/livreur', [DashboardController::class, 'livreur'])->name('livreur');
    Route::get('/vendeur', [DashboardController::class, 'vendeur'])->name('vendeur');
    Route::get('/vip', [DashboardController::class, 'vip'])->name('vip');
});

// Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/produits', [AdminController::class, 'produits'])->name('produits.index');
    Route::get('/promos', [AdminController::class, 'promos'])->name('promos.index');
    Route::get('/stats', [AdminController::class, 'stats'])->name('stats');
    Route::get('/ajouter-admin', [AdminController::class, 'addAdmin'])->name('ajouter_admin');
});
