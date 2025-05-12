<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Http\Requests\StoreStoryRequest;
use App\Http\Requests\UpdateStoryRequest;
use Illuminate\Http\JsonResponse;

/**
 * Contrôleur de gestion des histoires
 * 
 * Ce contrôleur gère les opérations CRUD pour les histoires interactives.
 * Il permet de récupérer, créer, modifier et supprimer des histoires,
 * ainsi que d'accéder au premier chapitre d'une histoire.
 */
class StoryController extends Controller
{
    /**
     * Récupère toutes les histoires disponibles
     * 
     * @return JsonResponse Liste des histoires au format JSON avec leurs attributs principaux
     */
    public function getStories(): JsonResponse
    {
        $stories = Story::all()->map(function ($story) {
            return [
                'id' => $story->id,
                'title' => $story->title,
                'summary' => $story->summary,
                'author' => $story->author,
                'cover' => $story->cover ?? null,
                'is_published' => $story->is_published,
            ];
        });

        return response()->json($stories);
    }

    /**
     * Récupère une histoire spécifique avec ses chapitres et choix associés
     * 
     * @param int $id Identifiant de l'histoire à récupérer
     * @return JsonResponse Détails de l'histoire ou message d'erreur si non trouvée
     */
    public function getStory(int $id): JsonResponse
    {
        $story = Story::with('chapters.choices')->find($id);
        
        if (!$story) {
            return response()->json(['message' => 'Histoire non trouvée'], 404);
        }
        
        return response()->json($story);
    }

    /**
     * Crée une nouvelle histoire
     * 
     * @param StoreStoryRequest $request Requête validée contenant les données de l'histoire
     * @return JsonResponse L'histoire créée avec code 201 (Created)
     */
    public function createStory(StoreStoryRequest $request): JsonResponse
    {
        $story = Story::create($request->validated());
        return response()->json($story, 201);
    }

    /**
     * Met à jour une histoire existante
     * 
     * @param UpdateStoryRequest $request Requête validée contenant les données à mettre à jour
     * @param Story $story Instance de l'histoire à modifier (injection de modèle)
     * @return JsonResponse L'histoire mise à jour
     */
    public function updateStory(UpdateStoryRequest $request, Story $story): JsonResponse
    {
        $story->update($request->validated());
        return response()->json($story);
    }

    /**
     * Supprime une histoire existante
     * 
     * @param Story $story Instance de l'histoire à supprimer (injection de modèle)
     * @return JsonResponse Message de confirmation
     */
    public function deleteStory(Story $story): JsonResponse
    {
        $story->delete();
        return response()->json(['message' => 'Story deleted successfully']);
    }

    /**
     * Récupère le premier chapitre d'une histoire avec ses choix
     * 
     * @param Story $story Instance de l'histoire (injection de modèle)
     * @return JsonResponse Premier chapitre avec ses choix ou message d'erreur
     */
    public function getFirstChapter(Story $story): JsonResponse
    {
        $chapter = $story->chapters()
            ->with('choices')
            ->orderBy('chapter_number')
            ->first();

        if (!$chapter) {
            return response()->json(['message' => 'Aucun chapitre trouvé'], 404);
        }

        return response()->json([
            'chapter' => [
                'id' => $chapter->id,
                'content' => $chapter->content,
                'image' => $chapter->image,
            ],
            'choices' => $chapter->choices->map(fn($choice) => [
                'id' => $choice->id,
                'text' => $choice->text,
                'next_chapter_id' => $choice->next_chapter_id,
            ])
        ]);
    }
}
