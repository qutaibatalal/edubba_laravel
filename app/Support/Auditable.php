<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

/**
 * Wires create/update/delete events of a model into the audit trail.
 */
trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function (Model $model) {
            AuditService::log($model, 'created', self::label($model).' تم إنشاؤه');
        });

        static::updated(function (Model $model) {
            $diff = AuditService::diff($model, $model->getOriginal());
            if ($diff) {
                AuditService::log($model, 'updated', self::label($model).' تم تعديله', $diff);
            }
        });

        static::deleted(function (Model $model) {
            AuditService::log($model, 'deleted', self::label($model).' تم حذفه');
        });
    }

    protected static function label(Model $model): string
    {
        return class_basename($model).' #'.$model->getKey();
    }
}
