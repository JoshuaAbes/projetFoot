<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'content',
        'story_id',
        'is_ending',
        'is_starting',
    ];
    
    /**
     * Get the story that owns the chapter.
     */
    public function story()
    {
        return $this->belongsTo(Story::class);
    }
    
    /**
     * Get the choices that originate from this chapter.
     */
    public function choices()
    {
        return $this->hasMany(Choice::class, 'from_chapter_id');
    }
    
    /**
     * Get the choices that lead to this chapter.
     */
    public function incomingChoices()
    {
        return $this->hasMany(Choice::class, 'to_chapter_id');
    }
}