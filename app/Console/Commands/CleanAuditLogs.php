<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use Illuminate\Console\Command;

class CleanAuditLogs extends Command
{
    protected $signature = 'audit:clean {days=90}';
    protected $description = 'Supprimer les anciens logs d\'audit';

    public function handle()
    {
        $days = $this->argument('days');
        $deleted = AuditLog::where('created_at', '<', now()->subDays($days))->delete();
        
        $this->info("{$deleted} logs supprimés (plus de {$days} jours)");
    }
}