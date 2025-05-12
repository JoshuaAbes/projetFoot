<?php

namespace App\Http\Controllers;

use App\Models\Choice;
use App\Http\Requests\StoreChoiceRequest;
use App\Http\Requests\UpdateChoiceRequest;
use Illuminate\Http\JsonResponse;

/**
 * Contrôleur de gestion des choix
 * 
 * Ce contrôleur gère les opérations CRUD pour les choix disponibles
 * dans les chapitres de l'histoire interactive. Ces choix permettent
 * aux utilisateurs de naviguer entre les différents chapitres.
 */
class ChoiceController extends Controller
{
    /**
     * Récupère tous les choix avec leurs relations
     * 
     * @return JsonResponse Liste de tous les choix disponibles
     */
    public function getChoices(): JsonResponse
    {
        $choices = Choice::with(['chapter', 'nextChapter'])->get();
        return response()->json($choices);
    }

    /**
     * Récupère un choix spécifique avec ses relations
     * 
     * @param Choice $choice Instance du choix à récupérer (injection de modèle)
     * @return JsonResponse Détails du choix avec ses chapitres associés
     */
    public function getChoice(Choice $choice): JsonResponse
    {
        $choice->load(['chapter', 'nextChapter']);
        return response()->json($choice);
    }

    /**
     * Crée un nouveau choix
     * 
     * @param StoreChoiceRequest $request Requête validée contenant les données du choix
     * @return JsonResponse Le choix créé avec code 201 (Created)
     */
    public function createChoice(StoreChoiceRequest $request): JsonResponse
    {
        $choice = Choice::create($request->validated());
        return response()->json($choice, 201);
    }

    /**
     * Met à jour un choix existant
     * 
     * @param UpdateChoiceRequest $request Requête validée contenant les données à mettre à jour
     * @param Choice $choice Instance du choix à modifier (injection de modèle)
     * @return JsonResponse Le choix mis à jour
     */
    public function updateChoice(UpdateChoiceRequest $request, Choice $choice): JsonResponse
    {
        $choice->update($request->validated());
        return response()->json($choice);
    }

    /**
     * Supprime un choix existant
     * 
     * @param Choice $choice Instance du choix à supprimer (injection de modèle)
     * @return JsonResponse Message de confirmation
     */
    public function deleteChoice(Choice $choice): JsonResponse
    {
        $choice->delete();
        return response()->json(['message' => 'Choice deleted successfully']);
    }
}
