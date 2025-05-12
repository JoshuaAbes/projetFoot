<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle représentant un choix dans un chapitre
 * 
 * Un choix permet au lecteur de naviguer d'un chapitre à un autre,
 * créant ainsi une histoire interactive avec différents chemins possibles.
 */
class Choice extends Model
{
    /**
     * Attributs pouvant être assignés massivement.
     *
     * @var array<string>
     */
    protected $fillable = ['text', 'chapter_id', 'next_chapter_id', 'traits'];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'traits' => 'array',
    ];

    /**
     * Récupère le chapitre auquel ce choix appartient.
     * 
     * @return BelongsTo Relation vers le chapitre parent
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * Récupère le chapitre vers lequel ce choix mène.
     * 
     * @return BelongsTo Relation vers le chapitre de destination
     */
    public function nextChapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class, 'next_chapter_id');
    }
}
