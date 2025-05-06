<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/')->group(function () {
  Route::get('/test', function () {
    return response()->json(['message' => 'Hello, World from api!']);
  });

  Route::delete('/test', function () {
    return response()->json(['message' => 'Deleting']);
  });

  Route::post('/time', function () {
    $timeClient = request()->input('timeClient', 0);
    sleep(2); // Simulate a long-running process
    return response()->json(['timeClient' => $timeClient, 'timeServer' => now()]);
  });
});