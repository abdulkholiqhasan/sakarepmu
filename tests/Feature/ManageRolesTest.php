<?php

use App\Models\User;
use Illuminate\Support\Str;

test('roles index is accessible to authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('roles.index'))
        ->assertStatus(200);
});

test('can create a new role', function () {
    $actor = User::factory()->create();

    $this->actingAs($actor)
        ->post(route('roles.store'), [
            'name' => 'editor-' . Str::random(5),
            'guard_name' => 'web',
        ])
        ->assertRedirect(route('roles.index'));

    $this->assertDatabaseHas('roles', ['guard_name' => 'web']);
});
