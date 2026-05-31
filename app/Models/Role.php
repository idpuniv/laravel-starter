<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'guard_name',
        'team_id',
        'label',
        'description',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $role): void {

            if (blank($role->name) && filled($role->label)) {
                $role->name = Str::slug($role->label);
            }

            if (blank($role->label) && filled($role->name)) {
                $role->label = Str::headline($role->name);
            }
        });
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}