<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'description',
        'cover_image',
        'user_id',
        'is_published',
    ];
    
    /**
     * Get the user who owns the story.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the chapters for the story.
     */
    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
    
    /**
     * Get the starting chapter for the story.
     */
    public function startingChapter()
    {
        return $this->chapters()->where('is_starting', true)->first();
    }
    
    /**
     * Get user progress for this story.
     */
    public function progress()
    {
        return $this->hasMany(Progress::class);
    }
}