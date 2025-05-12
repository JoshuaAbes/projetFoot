<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Requête de validation pour la création d'un chapitre
 * 
 * Cette classe gère la validation des données soumises lors de la création
 * d'un nouveau chapitre, en vérifiant que tous les champs requis sont présents
 * et conformes aux contraintes définies.
 */
class StoreChapterRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     * 
     * @return bool True si l'utilisateur est autorisé, false sinon
     */
    public function authorize(): bool
    {
        return true; // Autorisation gérée via le middleware d'authentification
    }

    /**
     * Obtient les règles de validation qui s'appliquent à la requête.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'story_id' => 'required|exists:stories,id', // L'ID de l'histoire doit exister dans la table stories
            'content' => 'required|string', // Le contenu du chapitre est obligatoire et doit être une chaîne
            'chapter_number' => 'required|integer|min:1', // Le numéro de chapitre est obligatoire et doit être un entier positif
        ];
    }
}
