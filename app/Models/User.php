<?php

namespace App\Models;

use App\Roles\Roles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use App\Enums\Status;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCodeMail;

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
        'person_id',
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
        'two_factor_expires_at' => 'datetime',
    ];

    /* -----------------------------------------------------------------
     | Relations
     |------------------------------------------------------------------*/

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
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

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeBanned($query)
    {
        return $query->where('status', 'banned');
    }

    /* -----------------------------------------------------------------
     | Accesseurs métier
     |------------------------------------------------------------------*/

    public function getIsRootAttribute(): bool
    {
        return $this->hasRole(Roles::ROOT);
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->hasAnyRole([Roles::ADMIN, Roles::ROOT]);
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === Status::ACTIVE;
    }

    public function redirectRoute(): string
    {
        return $this->is_admin
            ? 'admin.dashboard'
            : 'dashboard';
    }

    public function generateTwoFactorCode(): void
    {
        // Génère un code à 6 chiffres
        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(5); // valable 5 min
        $this->save();

        // Envoie le code par email
        Mail::to($this->email)->send(new TwoFactorCodeMail($this, $this->two_factor_code));
    }

    /**
     * Vérifie que le code 2FA est correct et pas expiré.
     */
    public function verifyTwoFactorCode(string $code): bool
    {
        return $this->two_factor_code === $code
            && $this->two_factor_expires_at
            && $this->two_factor_expires_at->isFuture();
    }

    /**
     * Réinitialise le code 2FA après validation.
     */
    public function resetTwoFactorCode(): void
    {
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    /**
     * Indique si la 2FA est activée pour ce rôle ou cet utilisateur.
     */
    public function requiresTwoFactor(): bool
    {
        // Rôles qui doivent obligatoirement faire 2FA
        $rolesRequiring2FA = ['admin', 'root'];

        // Si le rôle nécessite 2FA ou si l'utilisateur l'a activée volontairement
        return in_array($this->role, $rolesRequiring2FA) || $this->two_factor_enabled;
    }
}
