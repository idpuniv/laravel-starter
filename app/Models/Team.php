<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Team extends Model
{
    protected $fillable = [
        'name',
        'label',
        'description',
        'status',
        'icon',
    ];

    protected static function booted(): void
    {
        static::creating(function (Team $team) {
            if (blank($team->name) && filled($team->label)) {
                $team->name = Str::slug($team->label);
            }
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user');
    }

    public function scopeAvailable(Builder $query){
        $query->where('status', Status::ACTIVE);
    }
}