<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuditService
{
    public static function log($event, $target = null, $outcome = 'success', $old = null, $new = null, $context = null)
    {
        $actor = auth()->user() ?? auth()->guard('admin')->user();
        
        $log = new AuditLog();
        
        // Acteur
        if ($actor) {
            $log->actor_id = $actor->id;
            $log->actor_type = get_class($actor);
        } else {
            $log->system_user = 'system';
        }
        
        // Action
        $log->event = $event;
        $log->event_outcome = $outcome;
        
        // Cible
        if ($target) {
            $log->target_type = get_class($target);
            $log->target_id = $target->id;
            $log->target_identifier = $target->name ?? $target->email ?? $target->id;
        }
        
        // Contexte
        if ($context) {
            $log->context_type = $context['type'] ?? null;
            $log->context_id = $context['id'] ?? null;
        }
        
        // Avant/Après
        if ($old) {
            $log->old_values = $old;
        }
        if ($new) {
            $log->new_values = $new;
        }
        
        // HTTP
        $log->ip_address = Request::ip();
        $log->user_agent = Request::userAgent();
        $log->url = Request::fullUrl();
        $log->http_method = Request::method();
        $log->referrer = Request::header('referer');
        
        $log->save();
        
        return $log;
    }
    
    public static function logFailure($event, $target = null, $error = null)
    {
        return self::log($event, $target, 'failure', null, ['error' => $error]);
    }
}