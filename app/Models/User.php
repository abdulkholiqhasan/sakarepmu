<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Models\Manage\Role;

class User extends Authenticatable
{
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

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Roles relationship
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')
            ->using(\App\Models\Manage\RoleUser::class)
            ->withTimestamps();
    }

    /**
     * Assign a role to the user. Accepts Role model or id.
     */
    public function assignRole($role)
    {
        $roleId = $role instanceof Role ? $role->getKey() : $role;

        // If already attached, nothing to do
        if ($this->roles()->where('roles.id', $roleId)->exists()) {
            return;
        }

        // Attach with UUID id for pivot when the pivot table has an `id` column
        try {
            // Use getColumnListing which is more reliable across connections/environments
            $columns = \Illuminate\Support\Facades\Schema::getColumnListing('role_user');
            if (is_array($columns) && in_array('id', $columns, true)) {
                $this->roles()->attach($roleId, ['id' => (string) \Illuminate\Support\Str::uuid()]);
            } else {
                $this->roles()->attach($roleId);
            }
        } catch (\Throwable $e) {
            // Fallback: try attaching without id if schema checks fail for any reason
            try {
                $this->roles()->attach($roleId);
            } catch (\Throwable $e) {
                // last-resort: insert directly via DB to avoid Eloquent pivot quirks
                try {
                    \Illuminate\Support\Facades\DB::table('role_user')->insert([
                        'role_id' => $roleId,
                        'user_id' => $this->getKey(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Throwable $_) {
                    // give up silently; caller can handle or developer can inspect
                }
            }
        }
    }

    /**
     * Remove a role from the user.
     */
    public function removeRole($role)
    {
        $this->roles()->detach($role instanceof Role ? $role->getKey() : $role);
    }

    /**
     * Check if user has role by name or id
     */
    public function hasRole($role)
    {
        if ($role instanceof Role) {
            return $this->roles->contains($role->getKey());
        }

        return $this->roles->contains('name', $role) || $this->roles->contains('id', $role);
    }

    /**
     * Check if the user has a given permission name via their roles.
     */
    public function hasPermission(string $permissionName): bool
    {
        foreach ($this->roles as $role) {
            // eager-loaded permissions relationship on Role will be used if available
            if ($role->permissions->contains('name', $permissionName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Always store username as lowercase to keep uniqueness case-insensitive.
     */
    public function setUsernameAttribute(?string $value): void
    {
        $this->attributes['username'] = $value === null ? null : Str::lower($value);
    }
}
