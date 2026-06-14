<?php

namespace App\Models;

use App\Enums\PostStatus;
use App\Traits\Auditable;
use App\Traits\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Auditable;
    use HasMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'summary',
        'content',
        'status',
        'published_at',
        'views_count',
    ];

    protected $casts = [
        'status' => PostStatus::class,
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    /* -----------------------------------------------------------------
     | RELATIONS
     |-----------------------------------------------------------------*/

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Tous les médias rattachés à l'article (image à la une, galerie, etc.).
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /**
     * Galerie de l'article (médias regroupés dans la collection "gallery").
     */
    public function gallery(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable')
            ->where('collection', 'gallery')
            ->orderBy('sort_order');
    }

    /**
     * Image à la une : le média courant ayant le rôle "cover".
     */
    public function featuredImage(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable')
            ->where('role', 'cover')
            ->where('is_current', true);
    }

    /* -----------------------------------------------------------------
     | SCOPES
     |-----------------------------------------------------------------*/

    public function scopePublished($query)
    {
        return $query->where('status', PostStatus::PUBLISHED);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', PostStatus::DRAFT);
    }

    /* -----------------------------------------------------------------
     | ACCESSORS
     |-----------------------------------------------------------------*/

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === PostStatus::PUBLISHED;
    }
}
