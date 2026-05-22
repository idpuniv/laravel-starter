<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Traits\JobTrackingTrait;

class ExportJob implements ShouldQueue
{
    use Queueable, JobTrackingTrait;

    public function __construct()
    {
        $this->trackDispatch('Export utilisateurs');
    }

    public function handle(): void
    {
        $this->startTracking();

        $total = 10000;

        for ($i = 0; $i <= $total; $i += 1000) {
            // Traitement par lots
            sleep(1);

            $this->updateProgress($i, $total, "{$i} sur {$total} lignes exportées");
        }

        $this->completeTracking([
            'file_url' => '/exports/users.csv',
            'size' => '2.5 MB',
            'rows' => $total
        ]);
    }
}