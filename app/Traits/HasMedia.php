<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasMedia
{
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function avatar(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable')
            ->where('role', 'avatar');
    }

    public function cover(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable')
            ->where('role', 'cover');
    }

    public function getMediaByRole(string $role): ?Media
    {
        return $this->media()->where('role', $role)->first();
    }

    public function __get($key)
    {
        $relation = $this->getRelationValue($key);

        if ($relation) {
            return $relation;
        }

        return parent::__get($key);
    }
}