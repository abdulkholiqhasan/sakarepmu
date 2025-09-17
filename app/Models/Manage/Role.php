<?php

namespace App\Models\Manage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;

    /**
     * Boot function to assign UUID to id on creating.
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

    /**
     * The primary key is a UUID string, not an incrementing integer.
     */
    public $incrementing = false;

    protected $keyType = 'string';

    /** @var array<string> */
    protected $fillable = [
        'name',
        'guard_name',
    ];

    // Relationships
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'role_user', 'role_id', 'user_id');
    }

    // Helper: attach permission
    public function givePermissionTo($permission)
    {
        $this->permissions()->syncWithoutDetaching([$permission instanceof Permission ? $permission->getKey() : $permission]);
    }
}
