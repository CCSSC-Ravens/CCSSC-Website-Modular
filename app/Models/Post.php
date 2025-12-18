<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'content',
        'organization_user_id',
    ];

    /**
     * Get the organization user that owns the post.
     */
    public function organizationUser(): BelongsTo
    {
        return $this->belongsTo(OrganizationUser::class);
    }
}
