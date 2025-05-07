<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Choice extends Model
{
    protected $fillable = ['text', 'chapter_id', 'next_chapter_id', 'traits'];

    protected $casts = [
        'traits' => 'array',
    ];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function nextChapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class, 'next_chapter_id');
    }
}
