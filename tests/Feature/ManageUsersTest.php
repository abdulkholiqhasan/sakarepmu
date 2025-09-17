<?php

use App\Models\User;
use Illuminate\Support\Str;

test('users index is accessible to authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('users.index'))
        ->assertStatus(200);
});

test('can create a new user', function () {
    $actor = User::factory()->create();

    $this->actingAs($actor)
        ->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'newuser+' . Str::random(5) . '@example.com',
            'password' => 'password123',
        ])
        ->assertRedirect(route('users.index'));

    $this->assertDatabaseHas('users', ['name' => 'New User']);
});
