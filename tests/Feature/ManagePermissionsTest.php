<?php

use App\Models\User;
use Illuminate\Support\Str;

test('permissions index is accessible to authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('permissions.index'))
        ->assertStatus(200);
});

test('can create a new permission', function () {
    $actor = User::factory()->create();

    $this->actingAs($actor)
        ->post(route('permissions.store'), [
            'name' => 'edit-' . Str::random(5),
            'guard_name' => 'web',
        ])
        ->assertRedirect(route('permissions.index'));

    $this->assertDatabaseHas('permissions', ['guard_name' => 'web']);
});
