<?php

namespace App\Models;

use App\Models\Chapter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Story extends Model
{
    protected $fillable = ['title', 'summary', 'description', 'author', 'cover_image', 'is_published'];

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }
}
