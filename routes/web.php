<?php
// filepath: c:\Users\abess\Desktop\projetFoot\projetFoot\routes\web.php

use Illuminate\Support\Facades\Route;

// Route de base pour la page d'accueil
Route::get('/', function () {
  return view('test');
});

// Routes API
Route::prefix('api')->group(function () {
    require_once __DIR__ . '/api.php';
});

// Ajouter cette route catch-all à la fin
Route::get('{any}', function () {
    return view('test'); // Utiliser la même vue que celle de la page d'accueil
})->where('any', '.*');