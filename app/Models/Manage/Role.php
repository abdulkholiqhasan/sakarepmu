<?php

namespace App\Models\Manage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Manage\PermissionRole;

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
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id')
            ->using(PermissionRole::class)
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'role_user', 'role_id', 'user_id')
            ->using(RoleUser::class)
            ->withTimestamps();
    }

    // Helper: attach permission
    public function givePermissionTo($permission)
    {
        // Support pivot table with its own UUID primary key (permission_role.id)
        $permissionId = $permission instanceof Permission ? $permission->getKey() : $permission;

        // If the relation already exists, nothing to do.
        if ($this->permissions()->where('permissions.id', $permissionId)->exists()) {
            return;
        }

        // Attach with a generated UUID for the pivot `id` column when present.
        try {
            $columns = \Illuminate\Support\Facades\Schema::getColumnListing('permission_role');
            if (is_array($columns) && in_array('id', $columns, true)) {
                $this->permissions()->attach($permissionId, ['id' => (string) Str::uuid()]);
            } else {
                $this->permissions()->attach($permissionId);
            }
        } catch (\Throwable $e) {
            try {
                $this->permissions()->attach($permissionId);
            } catch (\Throwable $_) {
                // fallback: insert directly via DB
                try {
                    \Illuminate\Support\Facades\DB::table('permission_role')->insert([
                        'permission_id' => $permissionId,
                        'role_id' => $this->getKey(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Throwable $__) {
                    // silent fallback
                }
            }
        }
    }
}
