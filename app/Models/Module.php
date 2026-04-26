<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Champs autorisés en assignation massive
     */
    protected $fillable = [
        'name',
        'slug',
        'label',
        'description',
        'icon',
        'order',
        'is_active'
    ];

    /**
     * Casts
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Relation : Module -> Permissions
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * Scope : modules actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope : tri par ordre
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Accessor : nom d'affichage propre
     */
    public function getDisplayNameAttribute()
    {
        return $this->label ?? $this->name;
    }
}