<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Country extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'iso3',
        'iso2',
        'phone_code',
        'capital',
        'currency',
        'region',
        'subregion',
        'timezones'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Ajout du scope global
        static::addGlobalScope('filterPhoneCode', function (Builder $builder) {
            $builder->where('phone_code', '!=', null);
        });
    }
}
