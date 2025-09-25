<?php

use App\Models\User;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Volt;

test('general settings page is displayed', function () {
    $this->actingAs($user = User::factory()->create());

    $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
    $permission = Permission::firstOrCreate(['name' => 'manage settings'], ['guard_name' => 'web']);
    $role->givePermissionTo($permission);
    DB::table('role_user')->insert([
        'role_id' => $role->getKey(),
        'user_id' => $user->getKey(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->get(route('general.edit'))->assertOk();
});

test('general settings can be saved', function () {
    $user = User::factory()->create();

    $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
    $permission = Permission::firstOrCreate(['name' => 'manage settings'], ['guard_name' => 'web']);
    $role->givePermissionTo($permission);
    DB::table('role_user')->insert([
        'role_id' => $role->getKey(),
        'user_id' => $user->getKey(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($user);
    // ensure no leftover file (use configured settings.file)
    @unlink(config('settings.file'));

    $response = Volt::test('settings.general')
        ->set('site_title', 'My Site')
        ->set('site_tagline', 'An awesome site')
        ->set('site_url', 'https://example.test')
        ->set('admin_email', 'admin@example.test')
        ->set('timezone', 'UTC')
        ->call('save');

    $response->assertHasNoErrors();

    // Assert persisted values via SettingsService (DB or file fallback)
    $settings = new \App\Services\SettingsService();

    expect($settings->get('site_title'))->toEqual('My Site');
    expect($settings->get('site_tagline'))->toEqual('An awesome site');
    expect($settings->get('site_url'))->toEqual('https://example.test');
    expect($settings->get('admin_email'))->toEqual('admin@example.test');
    expect($settings->get('timezone'))->toEqual('UTC');
});
