<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'story_id',
        'current_chapter_id',
        'visited_chapters',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'visited_chapters' => 'array',
    ];
    
    /**
     * Get the user for this progress.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the story for this progress.
     */
    public function story()
    {
        return $this->belongsTo(Story::class);
    }
    
    /**
     * Get the current chapter for this progress.
     */
    public function currentChapter()
    {
        return $this->belongsTo(Chapter::class, 'current_chapter_id');
    }
}