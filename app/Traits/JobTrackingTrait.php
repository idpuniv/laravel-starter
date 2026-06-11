<?php

namespace App\Traits;

use App\Models\JobTracking;
use Illuminate\Support\Str;

trait JobTrackingTrait
{
    public $trackingId;

    public function trackDispatch($name = null)
    {
        $this->trackingId = (string) Str::uuid();

        JobTracking::create([
            'job_uuid' => $this->trackingId,
            'job_class' => static::class,
            'name' => $name ?? static::class,
            'queue' => $this->queue ?? 'default',
            'status' => 'pending',
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
        ]);

        return $this->trackingId;
    }

    public function startTracking()
    {
        JobTracking::where('job_uuid', $this->trackingId)->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);
    }

    public function updateProgress($processed, $total, $detail = null)
    {
        $progress = $total > 0 ? round(($processed / $total) * 100) : 0;

        JobTracking::where('job_uuid', $this->trackingId)->update([
            'processed_items' => $processed,
            'total_items' => $total,
            'progress' => $progress,
            'progress_detail' => $detail,
        ]);
    }

    public function completeTracking($result = null)
    {
        JobTracking::where('job_uuid', $this->trackingId)->update([
            'status' => 'completed',
            'result' => $result,
            'completed_at' => now(),
            'progress' => 100,
        ]);
    }

    public function failTracking($error)
    {
        JobTracking::where('job_uuid', $this->trackingId)->update([
            'status' => 'failed',
            'error' => $error,
            'completed_at' => now(),
        ]);
    }
}