<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'role',
        'type',
        'disk',
        'path',
        'filename',
        'extension',
        'mime_type',
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