<?php

namespace App\Models\Manage;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

class PermissionRole extends Pivot
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'permission_role';

    /**
     * Ensure a UUID id is set when creating pivot rows.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
