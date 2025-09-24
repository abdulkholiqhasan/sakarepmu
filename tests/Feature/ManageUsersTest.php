<?php

use App\Models\User;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

test('users index is accessible to authenticated users', function () {
    $user = User::factory()->create();

    $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
    $permission = Permission::firstOrCreate(['name' => 'manage users'], ['guard_name' => 'web']);
    $role->givePermissionTo($permission);
    DB::table('role_user')->insert([
        'role_id' => $role->getKey(),
        'user_id' => $user->getKey(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('users.index'))
        ->assertStatus(200);
});

test('can create a new user', function () {
    $actor = User::factory()->create();

    $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
    $permission = Permission::firstOrCreate(['name' => 'manage users'], ['guard_name' => 'web']);
    $role->givePermissionTo($permission);
    DB::table('role_user')->insert([
        'role_id' => $role->getKey(),
        'user_id' => $actor->getKey(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($actor)
        ->post(route('users.store'), [
            'name' => 'New User',
            'username' => 'newuser' . Str::random(5),
            'email' => 'newuser+' . Str::random(5) . '@example.com',
            'password' => 'password123',
        ])
        ->assertRedirect(route('users.index'));

    $this->assertDatabaseHas('users', ['name' => 'New User']);
});
