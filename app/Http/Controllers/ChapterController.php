<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Http\Requests\StoreChapterRequest;
use App\Http\Requests\UpdateChapterRequest;
use Illuminate\Http\JsonResponse;

/**
 * Contrôleur de gestion des chapitres
 * 
 * Ce contrôleur gère les opérations CRUD pour les chapitres de l'histoire
 * et permet la récupération des données nécessaires pour l'affichage
 * des chapitres avec leurs choix associés.
 */
class ChapterController extends Controller
{
    /**
     * Récupère tous les chapitres avec leurs choix associés
     * 
     * @return JsonResponse Liste des chapitres au format JSON
     */
    public function index(): JsonResponse
    {
        $chapters = Chapter::with('choices')->get();

        return response()->json($chapters);
    }

    /**
     * Crée un nouveau chapitre
     * 
     * @param StoreChapterRequest $request Requête contenant les données validées du chapitre
     * @return JsonResponse Le chapitre créé avec code 201 (Created)
     */
    public function store(StoreChapterRequest $request): JsonResponse
    {
        $chapter = Chapter::create($request->validated());

        return response()->json($chapter, 201);
    }

    /**
     * Récupère un chapitre spécifique avec ses choix
     * 
     * @param int $id Identifiant du chapitre à récupérer
     * @return JsonResponse Détails du chapitre ou message d'erreur si non trouvé
     */
    public function getChapter(int $id): JsonResponse
    {
        $chapter = Chapter::with('choices')->find($id);

        if (!$chapter) {
            return response()->json(['message' => 'Chapitre introuvable.'], 404);
        }

        // Retourne une structure JSON formatée pour le frontend
        return response()->json([
            'id' => $chapter->id,
            'chapter_number' => $chapter->chapter_number,
            'content' => $chapter->content,
            'image' => $chapter->image,
            'is_ending' => $chapter->is_ending,
            'is_chest_room' => $chapter->is_chest_room,
            'choices' => $chapter->choices->map(fn($choice) => [
                'id' => $choice->id,
                'text' => $choice->text,
                'next_chapter_id' => $choice->next_chapter_id,
            ])
        ]);
    }

    /**
     * Récupère tous les chapitres avec leurs choix (méthode alternative)
     * 
     * @return JsonResponse Liste complète des chapitres
     */
    public function getChapters(): JsonResponse
    {
        $chapters = Chapter::with('choices')->get();
        return response()->json($chapters);
    }

    /**
     * Met à jour un chapitre existant
     * 
     * @param UpdateChapterRequest $request Requête contenant les données validées
     * @param Chapter $chapter Instance du chapitre à modifier
     * @return JsonResponse Le chapitre mis à jour
     */
    public function update(UpdateChapterRequest $request, Chapter $chapter): JsonResponse
    {
        $chapter->update($request->validated());

        return response()->json($chapter);
    }

    /**
     * Supprime un chapitre
     * 
     * @param Chapter $chapter Instance du chapitre à supprimer
     * @return JsonResponse Message de confirmation
     */
    public function destroy(Chapter $chapter): JsonResponse
    {
        $chapter->delete();

        return response()->json(['message' => 'Chapter deleted successfully']);
    }
}
