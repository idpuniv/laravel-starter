<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Support\Str;

class Role extends SpatieRole
{
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'guard_name',
        'team_id',
        'label',
        'description'
    ];



    protected static function booted(): void
    {
        static::creating(function (self $role) {

            if (empty($role->name) && !empty($role->label)) {
                $role->name = Str::slug($role->label);
            }
            if (empty($role->label) && !empty($role->name)) {
                $role->label = ucfirst(str_replace('-', ' ', $role->name));
            }
        });

        static::updating(function (self $role) {

            if ($role->isDirty('name')) {
                $role->name = $role->getOriginal('name');
            }
        });
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public static function findAll(array $data = []): Builder
    {
        return self::query()
            ->where('group_id', $data['group_id'])
            ->where('name', '!=', 'root');
    }
}
