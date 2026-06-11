<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Traits\JobTrackingTrait;

class FailingJob implements ShouldQueue
{
    use Queueable, JobTrackingTrait;

    public function __construct()
    {
        $this->trackDispatch('Job qui échoue');
    }

    public function handle(): void
    {
        $this->startTracking();

        throw new \Exception("Une erreur est survenue dans le job");
    }

    public function failed(\Throwable $exception)
    {
        $this->failTracking($exception->getMessage());
    }
}