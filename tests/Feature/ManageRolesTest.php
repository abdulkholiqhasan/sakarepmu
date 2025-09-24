<?php

use App\Models\User;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

test('roles index is accessible to authenticated users', function () {
    $user = User::factory()->create();

    $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
    $permission = Permission::firstOrCreate(['name' => 'manage roles'], ['guard_name' => 'web']);
    $role->givePermissionTo($permission);
    DB::table('role_user')->insert([
        'role_id' => $role->getKey(),
        'user_id' => $user->getKey(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('roles.index'))
        ->assertStatus(200);
});

test('can create a new role', function () {
    $actor = User::factory()->create();

    $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
    $permission = Permission::firstOrCreate(['name' => 'manage roles'], ['guard_name' => 'web']);
    $role->givePermissionTo($permission);
    DB::table('role_user')->insert([
        'role_id' => $role->getKey(),
        'user_id' => $actor->getKey(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($actor)
        ->post(route('roles.store'), [
            'name' => 'editor-' . Str::random(5),
            'guard_name' => 'web',
        ])
        ->assertRedirect(route('roles.index'));

    $this->assertDatabaseHas('roles', ['guard_name' => 'web']);
});
