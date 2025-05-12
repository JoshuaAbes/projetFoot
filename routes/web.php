<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminAuthMiddleware;

/**
 * Routes Web de l'application
 * 
 * Ce fichier définit toutes les routes web de l'application, incluant:
 * - Les routes d'administration et d'authentification
 * - La route pour la page d'accueil
 * - L'importation des routes API
 * - Une route catch-all pour le frontend Vue.js (SPA)
 */

// Groupe principal avec middleware web (sessions, CSRF, cookies)
Route::middleware(['web'])->group(function () {
    // Routes d'administration - Gestion des accès et authentification
    Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

    // Routes protégées par le middleware d'authentification admin
    Route::middleware([AdminAuthMiddleware::class])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // Autres routes d'administration protégées peuvent être ajoutées ici
    });

    // Route pour la page d'accueil principale
    Route::get('/', function () {
        return view('test');
    });

    // Routes API - importées depuis api.php et préfixées avec /api
    Route::prefix('api')->group(function () {
        require_once __DIR__ . '/api.php';
    });

    // Route catch-all pour le frontend Vue.js (SPA)
    // Toutes les URLs sauf celles commençant par "api" seront dirigées vers la vue principale
    // Cela permet à Vue Router de gérer les routes côté client
    Route::get('/{any}', function () {
        return view('test');
    })->where('any', '^(?!api).*$');
});