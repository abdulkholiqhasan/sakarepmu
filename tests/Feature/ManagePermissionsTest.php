<?php

use App\Models\User;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

test('permissions index is accessible to authenticated users', function () {
    $user = User::factory()->create();

    // give the user a role which has the required permission
    $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
    $permission = Permission::firstOrCreate(['name' => 'manage permissions'], ['guard_name' => 'web']);
    $role->givePermissionTo($permission);
    // SQLite pivot table doesn't have `id` column; insert directly without id
    DB::table('role_user')->insert([
        'role_id' => $role->getKey(),
        'user_id' => $user->getKey(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('permissions.index'))
        ->assertStatus(200);
});

test('can create a new permission', function () {
    $actor = User::factory()->create();

    // grant permission to actor
    $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
    $permission = Permission::firstOrCreate(['name' => 'manage permissions'], ['guard_name' => 'web']);
    $role->givePermissionTo($permission);
    DB::table('role_user')->insert([
        'role_id' => $role->getKey(),
        'user_id' => $actor->getKey(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($actor)
        ->post(route('permissions.store'), [
            'name' => 'edit-' . Str::random(5),
            'guard_name' => 'web',
        ])
        ->assertRedirect(route('permissions.index'));

    $this->assertDatabaseHas('permissions', ['guard_name' => 'web']);
});
