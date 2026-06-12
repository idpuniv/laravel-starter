<?php

namespace App\Models;

use App\Roles\Roles;
use App\Enums\UserStatus;
use App\Mail\TwoFactorCodeMail;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\HasMedia;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use SoftDeletes;
     use HasMedia;

    protected $guard_name = 'web';

    protected $table = 'users';

    protected $fillable = [
        'email',
        'username',
        'password',
        'status',
        'team_id',
        'person_id',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_expires_at' => 'datetime',
        'status' => UserStatus::class,
    ];

    /* -----------------------------------------------------------------
     | RELATIONS
     |-----------------------------------------------------------------*/

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /**
     * Avatar : le média courant ayant le rôle "avatar".
     */
    public function avatar(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable')
            ->where('role', 'avatar')
            ->where('is_current', true);
    }

    /* -----------------------------------------------------------------
     | SCOPES (UPDATED -> ENUM)
     |-----------------------------------------------------------------*/

    public function scopeActive($query)
    {
        return $query->where('status', UserStatus::ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', UserStatus::INACTIVE);
    }

    public function scopeBanned($query)
    {
        return $query->where('status', UserStatus::BANNED);
    }

    public function scopePending($query)
    {
        return $query->where('status', UserStatus::PENDING);
    }

    /* -----------------------------------------------------------------
     | ACCESSORS
     |-----------------------------------------------------------------*/

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
        return $this->status === UserStatus::ACTIVE;
    }

    /* -----------------------------------------------------------------
     | ROUTING
     |-----------------------------------------------------------------*/

    public function redirectRoute(): string
    {
        return $this->is_admin
            ? 'admin.dashboard'
            : 'dashboard';
    }

    public function getDashboardRoute(): string
    {
        return $this->redirectRoute();
    }

    /* -----------------------------------------------------------------
     | 2FA
     |-----------------------------------------------------------------*/

    public function generateTwoFactorCode(): void
    {
        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(5);
        $this->save();

        Mail::to($this->email)
            ->send(new TwoFactorCodeMail($this, $this->two_factor_code));
    }

    public function verifyTwoFactorCode(string $code): bool
    {
        return $this->two_factor_code === $code
            && $this->two_factor_expires_at
            && $this->two_factor_expires_at->isFuture();
    }

    public function resetTwoFactorCode(): void
    {
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    public function requiresTwoFactor(): bool
    {
        return $this->hasAnyRole(['admin', 'root'])
            || $this->two_factor_enabled;
    }

    /* -----------------------------------------------------------------
     | NOTIFICATIONS
     |-----------------------------------------------------------------*/

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmail);
    }
}