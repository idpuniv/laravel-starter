<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobTracking extends Model
{
    protected $fillable = [
        'job_uuid', 'job_class', 'name', 'queue', 'status',
        'progress', 'progress_detail', 'total_items', 'processed_items',
        'result', 'user_id', 'ip_address', 'error',
        'started_at', 'completed_at'
    ];

    protected $casts = [
        'result' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}