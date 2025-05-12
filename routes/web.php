<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminAuthMiddleware;

Route::middleware(['web'])->group(function () {
    // Routes d'administration - Gestion des accès et authentification
    Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

    // Routes protégées par le middleware d'authentification admin
    Route::middleware([AdminAuthMiddleware::class])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    });

    // Route pour la page d'accueil principale
    Route::get('/', function () {
        return view('test');
    });

    // Routes API - importées depuis api.php
    Route::prefix('api')->group(function () {
        require_once __DIR__ . '/api.php';
    });

    // Route catch-all pour le frontend Vue.js (toutes les URLs sauf celles commençant par "api")
    Route::get('/{any}', function () {
        return view('test');
    })->where('any', '^(?!api).*$');
});