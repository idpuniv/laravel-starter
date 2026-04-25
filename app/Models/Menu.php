<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $table = 'menus';

    protected $fillable = [

        // identification
        'slug',
        'label',
        'icon',

        // structure
        'type',
        'menu_type',

        // navigation
        'route',
        'url',

        // hiérarchie
        'parent_id',
        'order',

        // sécurité
        'permission',

        // état
        'is_active',
        'is_visible',

        // extras
        'position',
        'options',
        'badge',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_visible' => 'boolean',
        'options'    => 'array',
        'badge'      => 'array',
    ];

    /**
     * Parent menu
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Children menus
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('order');
    }

    /**
     * Scope: sidebar
     */
    public function scopeSidebar($query)
    {
        return $query->where('type', 'sidebar');
    }

    /**
     * Scope: navbar
     */
    public function scopeNavbar($query)
    {
        return $query->where('type', 'navbar');
    }

    /**
     * Scope: actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: visibles
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }
}