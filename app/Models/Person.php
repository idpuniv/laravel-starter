<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{

    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'last_name',
        'first_name',
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
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the full phone number with country code
     */
    public function getFullPhoneAttribute(): string
    {
        if (!$this->phone) {
            return '';
        }
        
        $phoneCode = $this->phone_code ? '+' . $this->phone_code : '';
        return trim($phoneCode . ' ' . $this->phone);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}