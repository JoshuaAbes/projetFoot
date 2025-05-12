<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle représentant un chapitre d'une histoire
 * 
 * Un chapitre contient du contenu textuel et des choix qui permettent 
 * aux lecteurs de naviguer entre les différents chapitres d'une histoire.
 */
class Chapter extends Model
{
    /**
     * Attributs pouvant être assignés massivement.
     *
     * @var array<string>
     */
    protected $fillable = ['title', 'content', 'chapter_number', 'story_id', 'image', 'is_ending', 'is_starting'];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_ending' => 'boolean',
        'is_starting' => 'boolean',
    ];

    /**
     * Récupère l'histoire à laquelle appartient ce chapitre.
     * 
     * @return BelongsTo Relation vers l'histoire parente
     */
    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    /**
     * Récupère les choix disponibles pour ce chapitre.
     * 
     * @return HasMany Relation vers les choix du chapitre
     */
    public function choices(): HasMany
    {
        return $this->hasMany(Choice::class);
    }

    /**
     * Récupère les choix qui mènent à ce chapitre.
     * 
     * @return HasMany Relation vers les choix menant à ce chapitre
     */
    public function incomingChoices()
    {
        return $this->hasMany(Choice::class, 'next_chapter_id');
    }
}
