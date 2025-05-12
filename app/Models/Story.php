<?php

namespace App\Models;

use App\Models\Chapter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle représentant une histoire interactive
 * 
 * Une histoire contient plusieurs chapitres et appartient à un utilisateur.
 * Elle constitue le conteneur principal pour une aventure interactive.
 */
class Story extends Model
{
    /**
     * Attributs pouvant être assignés massivement.
     *
     * @var array<string>
     */
    protected $fillable = ['title', 'summary', 'author', 'is_published', 'user_id'];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Récupère tous les chapitres appartenant à cette histoire.
     * 
     * @return HasMany Relation vers les chapitres de l'histoire
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }
    
    /**
     * Récupère l'utilisateur créateur de cette histoire.
     * 
     * @return BelongsTo Relation vers l'utilisateur créateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Récupère le chapitre initial de l'histoire.
     * 
     * @return Chapter|null Le chapitre initial ou null si non trouvé
     */
    public function getFirstChapter()
    {
        return $this->chapters()->where('is_starting', true)->first();
    }
}
