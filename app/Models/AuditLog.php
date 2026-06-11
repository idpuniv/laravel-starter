<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'actor_id', 'actor_type', 'system_user',
        'event', 'event_outcome',
        'target_type', 'target_id', 'target_identifier',
        'context_type', 'context_id',
        'old_values', 'new_values',
        'ip_address', 'user_agent', 'url', 'http_method', 'referrer'
    ];
    
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime'
    ];
    
    public function actor()
    {
        return $this->morphTo();
    }
    
    public function target()
    {
        return $this->morphTo();
    }
    
    // Scope pour filtrer par date
    public function scopeDateBetween($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }
    
    // Scope pour filtrer par acteur
    public function scopeByActor($query, $actorId, $actorType = null)
    {
        $query->where('actor_id', $actorId);
        if ($actorType) {
            $query->where('actor_type', $actorType);
        }
        return $query;
    }
    
    // Scope pour filtrer par événement
    public function scopeByEvent($query, $event)
    {
        return $query->where('event', $event);
    }
    
    // Scope pour filtrer par cible
    public function scopeByTarget($query, $targetType, $targetId = null)
    {
        $query->where('target_type', $targetType);
        if ($targetId) {
            $query->where('target_id', $targetId);
        }
        return $query;
    }
}