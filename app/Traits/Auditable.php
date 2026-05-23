<?php

namespace App\Traits;

use App\Services\AuditService;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            AuditService::log('create', $model, 'success', null, $model->getAttributes());
        });
        
        static::updated(function ($model) {
            AuditService::log('update', $model, 'success', $model->getOriginal(), $model->getChanges());
        });
        
        static::deleted(function ($model) {
            AuditService::log('delete', $model, 'success', $model->getAttributes(), null);
        });
        
        static::restored(function ($model) {
            AuditService::log('restore', $model, 'success', null, $model->getAttributes());
        });
    }
}