<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nom',
        'prenom',
        'phone',
        'phone_code',
        'country_id',
        'user_id',
        'gender'
    ];

    public function personable()
    {
        return $this->morphTo();
    }
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
