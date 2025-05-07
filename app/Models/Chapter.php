<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    protected $fillable = ['content', 'chapter_number', 'story_id', 'image', 'is_ending', 'is_chest_room'];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function choices(): HasMany
    {
        return $this->hasMany(Choice::class);
    }
}
