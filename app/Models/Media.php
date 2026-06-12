<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'role',
        'collection',// champ manquant
        'type',
        'disk',
        'path',
        'thumb_path',// champ manquant
        'filename',
        'extension',
        'mime_type',
        'size',// champ manquant
        'is_current',
        'sort_order',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_current' => 'boolean',
    ];

    public const SINGLE_ROLES = [
        'avatar',
        'logo',
        'cover',
        'favicon',
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }
}