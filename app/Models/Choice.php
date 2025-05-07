<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'text',
        'from_chapter_id',
        'to_chapter_id',
    ];
    
    /**
     * Get the chapter this choice leads from.
     */
    public function fromChapter()
    {
        return $this->belongsTo(Chapter::class, 'from_chapter_id');
    }
    
    /**
     * Get the chapter this choice leads to.
     */
    public function toChapter()
    {
        return $this->belongsTo(Chapter::class, 'to_chapter_id');
    }
}