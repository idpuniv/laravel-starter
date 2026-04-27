<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Person extends Model
{

    protected $fillable = [
        'nom',
        'prenom',
        'phone',
        'phone_code',
        'country_id',
        'gender'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }


    public function getFullNameAttribute(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}