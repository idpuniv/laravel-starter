<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class AuditService
{
    public static function log($event, $target = null, $outcome = 'success', $old = null, $new = null, $context = null)
    {
        $actor = auth()->user() ?? null;
        
        if (!$actor) {
            $actor = User::find(2);
        }
        
        $log = new AuditLog();
        
        if ($actor) {
            $log->actor_id = $actor->id;
            $log->actor_type = get_class($actor);
            
            if ($actor->is_system ?? false) {
                $log->system_user = $actor->system_type ?? 'system';
            }
        } else {
            $log->system_user = 'unknown';
        }
        
        $log->event = $event;
        $log->event_outcome = $outcome;
        
        if ($target) {
            $log->target_type = get_class($target);
            $log->target_id = $target->id;
            $log->target_identifier = $target->name ?? $target->email ?? $target->id;
        }
        
        if ($event === 'login' && $outcome === 'failure' && isset($context['email'])) {
            $log->target_identifier = $context['email'];
        }
        
        if ($context) {
            $log->context_type = $context['type'] ?? null;
            $log->context_id = $context['id'] ?? null;
        }
        
        if ($old) {
            $log->old_values = $old;
        }
        if ($new) {
            $log->new_values = $new;
        }
        
        $log->ip_address = Request::ip();
        $log->user_agent = Request::userAgent();
        $log->url = Request::fullUrl();
        $log->http_method = Request::method();
        $log->referrer = Request::header('referer');
        
        $log->save();
        
        return $log;
    }
    
    public static function logFailure($event, $target = null, $error = null, $old = null, $new = null, $context = null)
    {
        return self::log($event, $target, 'failure', $old, $new, $context);
    }
}