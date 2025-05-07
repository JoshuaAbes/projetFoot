<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ChoiceController;

Route::prefix('v1')->group(function () {

    // Routes pour les histoires
    Route::get('/stories', [StoryController::class, 'getStories']);
    Route::get('/stories/{id}', [StoryController::class, 'getStory']);
    Route::post('/stories', [StoryController::class, 'createStory']);
    Route::put('/stories/{id}', [StoryController::class, 'updateStory']);
    Route::delete('/stories/{id}', [StoryController::class, 'deleteStory']);

    // Routes pour les chapitres
    Route::get('/stories/{story}/first-chapter', [StoryController::class, 'getFirstChapter']);
    Route::get('/chapters', [ChapterController::class, 'getChapters']);
    Route::get('/chapters/{id}', [ChapterController::class, 'getChapter']);
    Route::post('/chapters', [ChapterController::class, 'createChapter']);
    Route::put('/chapters/{id}', [ChapterController::class, 'updateChapter']);
    Route::delete('/chapters/{id}', [ChapterController::class, 'deleteChapter']);

    // Routes pour les choix
    Route::get('/choices', [ChoiceController::class, 'getChoices']);
    Route::get('/choices/{id}', [ChoiceController::class, 'getChoice']);
    Route::post('/choices', [ChoiceController::class, 'createChoice']);
    Route::put('/choices/{id}', [ChoiceController::class, 'updateChoice']);
    Route::delete('/choices/{id}', [ChoiceController::class, 'deleteChoice']);
});
