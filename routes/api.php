<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ChoiceController;

/**
 * Routes API de l'application
 * 
 * Ce fichier définit toutes les routes API de l'application suivant une architecture RESTful.
 * Les routes sont versionnées (/api/v1/...) pour assurer la compatibilité future.
 * 
 * Note: Ces routes sont incluses dans web.php et préfixées avec /api
 * L'URL complète serait donc: /api/v1/[resource]
 */

// Groupe de routes API v1
Route::prefix('v1')->group(function () {

    // Routes pour les histoires (stories)
    // GET /api/v1/stories - Liste toutes les histoires
    // GET /api/v1/stories/{id} - Récupère une histoire spécifique
    Route::get('/stories', [StoryController::class, 'getStories']);
    Route::get('/stories/{id}', [StoryController::class, 'getStory']);
    Route::post('/stories', [StoryController::class, 'createStory']);
    Route::put('/stories/{id}', [StoryController::class, 'updateStory']);
    Route::delete('/stories/{id}', [StoryController::class, 'deleteStory']);

    // Routes pour les chapitres (chapters)
    // La route first-chapter est un cas particulier pour obtenir le premier chapitre d'une histoire
    Route::get('/stories/{story}/first-chapter', [StoryController::class, 'getFirstChapter']);
    Route::get('/chapters', [ChapterController::class, 'getChapters']);
    Route::get('/chapters/{id}', [ChapterController::class, 'getChapter']);
    Route::post('/chapters', [ChapterController::class, 'createChapter']);
    Route::put('/chapters/{id}', [ChapterController::class, 'updateChapter']);
    Route::delete('/chapters/{id}', [ChapterController::class, 'deleteChapter']);

    // Routes pour les choix (choices)
    // Ces routes permettent de gérer les options de navigation entre les chapitres
    Route::get('/choices', [ChoiceController::class, 'getChoices']);
    Route::get('/choices/{id}', [ChoiceController::class, 'getChoice']);
    Route::post('/choices', [ChoiceController::class, 'createChoice']);
    Route::put('/choices/{id}', [ChoiceController::class, 'updateChoice']);
    Route::delete('/choices/{id}', [ChoiceController::class, 'deleteChoice']);

    // Note: Les opérations de création/modification/suppression devraient être protégées
    // par un middleware d'authentification admin dans un environnement de production
});
