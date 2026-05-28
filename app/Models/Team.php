<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'label',
        'description',
        'status',
        'icon',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user');
    }
}
