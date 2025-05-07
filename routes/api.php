<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\ChapterController;
use App\Http\Controllers\API\V1\ChoiceController;
use App\Http\Controllers\API\V1\ProgressController;
use App\Http\Controllers\API\V1\StoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Version 1 API routes
Route::prefix('v1')->group(function () {
    // Public routes
    Route::get('/stories', [StoryController::class, 'index']);
    Route::get('/stories/{id}', [StoryController::class, 'show']);
    Route::get('/stories/{storyId}/chapters', [ChapterController::class, 'index']);
    Route::get('/stories/{storyId}/chapters/{chapterId}', [ChapterController::class, 'show']);
    Route::get('/chapters/{chapterId}/choices', [ChoiceController::class, 'index']);
    Route::get('/choices/{id}', [ChoiceController::class, 'show']);
    
    // Authentication routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
    
    // Protected routes
    Route::middleware('auth')->group(function () {
        // User info route
        Route::get('/user', [AuthController::class, 'user']);
        
        // My stories routes
        Route::get('/my-stories', [StoryController::class, 'myStories']);
        
        // Story management routes
        Route::post('/stories', [StoryController::class, 'store']);
        Route::put('/stories/{id}', [StoryController::class, 'update']);
        Route::delete('/stories/{id}', [StoryController::class, 'destroy']);
        
        // Chapter management routes
        Route::post('/stories/{storyId}/chapters', [ChapterController::class, 'store']);
        Route::put('/stories/{storyId}/chapters/{chapterId}', [ChapterController::class, 'update']);
        Route::delete('/stories/{storyId}/chapters/{chapterId}', [ChapterController::class, 'destroy']);
        
        // Choice management routes
        Route::post('/choices', [ChoiceController::class, 'store']);
        Route::put('/choices/{id}', [ChoiceController::class, 'update']);
        Route::delete('/choices/{id}', [ChoiceController::class, 'destroy']);
        
        // Progress routes
        Route::get('/progress', [ProgressController::class, 'index']);
        Route::post('/progress', [ProgressController::class, 'store']);
        Route::get('/progress/{storyId}', [ProgressController::class, 'show']);
        Route::delete('/progress/{storyId}', [ProgressController::class, 'destroy']);
    });
});

// Fallback route for undefined API routes
Route::fallback(function () {
    return response()->json([
        'message' => 'API endpoint not found'
    ], 404);
});