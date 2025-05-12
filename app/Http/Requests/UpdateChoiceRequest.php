<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Requête de validation pour la mise à jour d'un choix
 * 
 * Cette classe gère la validation des données soumises lors de la modification
 * d'un choix existant, en vérifiant que tous les champs sont conformes
 * aux contraintes définies.
 */
class UpdateChoiceRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     * 
     * @return bool True si l'utilisateur est autorisé, false sinon
     */
    public function authorize(): bool
    {
        return true; // Autorisation gérée via le middleware d'authentification (attention, était à false)
    }

    /**
     * Obtient les règles de validation qui s'appliquent à la requête.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'chapter_id' => 'required|exists:chapters,id', // L'ID du chapitre doit exister dans la table chapters
            'text' => 'required|string|max:255', // Le texte du choix est obligatoire et limité à 255 caractères
            'next_chapter_id' => 'nullable|exists:chapters,id', // L'ID du chapitre suivant est facultatif mais doit exister s'il est fourni
        ];
    }
}
