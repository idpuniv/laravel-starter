<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements
    MustVerifyEmail
{
    /**
     * @use HasFactory<\Database\Factories\UserFactory>
     */
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    /**
     * Guard Spatie (web par défaut)
     */
    protected $guard_name = 'web';

    /**
     * Table physique
     */
    protected $table = 'users';

    /**
     * Attributs assignables
     */
    protected $fillable = [
        'email',
        'username',
        'password',
        'status',
        'team_id',
        'email_verified_at',
    ];

    /**
     * Champs cachés
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /* -----------------------------------------------------------------
     | Relations
     |------------------------------------------------------------------*/

    public function person(): MorphOne
    {
        return $this->morphOne(Person::class, 'personable');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /* -----------------------------------------------------------------
     | Scopes
     |------------------------------------------------------------------*/

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /* -----------------------------------------------------------------
     | Accesseurs métier
     |------------------------------------------------------------------*/

    public function getIsRootAttribute(): bool
    {
        return $this->hasRole('root');
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->hasAnyRole(['admin', 'root']);
    }
}
