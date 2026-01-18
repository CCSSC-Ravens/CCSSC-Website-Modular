<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostGallery extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'post_id',
        'image_path',
        'order',
    ];

    /**
     * Get the post that owns the gallery image.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
