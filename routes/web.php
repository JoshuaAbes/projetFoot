<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('test');
});

// Instead of using Sanctum (or something similar) for API authentication,
// we are using the built-in Laravel session authentication system.
require_once __DIR__ . '/api.php';