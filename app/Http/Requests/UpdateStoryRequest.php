<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Requête de validation pour la mise à jour d'une histoire
 * 
 * Cette classe gère la validation des données soumises lors de la modification
 * d'une histoire existante, en vérifiant que tous les champs sont conformes
 * aux contraintes définies.
 */
class UpdateStoryRequest extends FormRequest
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
            'title' => 'required|string|max:255', // Le titre est obligatoire et limité à 255 caractères
            'summary' => 'nullable|string', // Le résumé est facultatif
            'author' => 'nullable|string|max:255', // L'auteur est facultatif et limité à 255 caractères
        ];
    }
}
