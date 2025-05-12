<?php
// filepath: c:\Users\abess\Desktop\projetFoot\projetFoot\routes\web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminAuthMiddleware;

Route::middleware(['web'])->group(function () {
    // Routes Admin
    Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::middleware([AdminAuthMiddleware::class])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    });

    // Route de base pour la page d'accueil
    Route::get('/', function () {
        return view('test');
    });

    // Routes API
    Route::prefix('api')->group(function () {
        require_once __DIR__ . '/api.php';
    });

    // Route catch-all pour le frontend Vue.js (exclut les routes API)
    Route::get('/{any}', function () {
        return view('test');
    })->where('any', '^(?!api).*$');
});