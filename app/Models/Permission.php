<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    //
    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'label',
        'description',
        'module_id',
        'guard_name'
    ];

    protected static function booted(): void
    {
        static::creating(function (self $role) {
            if (empty($role->label)) {
                $role->label = ucfirst($role->name ?? '');
            }
        });
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
