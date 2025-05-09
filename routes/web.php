<?php
// filepath: c:\Users\abess\Desktop\projetFoot\projetFoot\routes\web.php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('test');
});

// Ajouter le préfixe 'api' ici
Route::prefix('api')->group(function () {
    // L'inclusion reste la même
    require_once __DIR__ . '/api.php';
});