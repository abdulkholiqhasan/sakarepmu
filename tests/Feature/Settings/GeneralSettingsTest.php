<?php

use App\Models\User;
use Livewire\Volt\Volt;

test('general settings page is displayed', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('general.edit'))->assertOk();
});

test('general settings can be saved', function () {
    $user = User::factory()->create();

    $this->actingAs($user);
    // ensure no leftover file
    @unlink(storage_path('app/settings.json'));

    $response = Volt::test('settings.general')
        ->set('site_title', 'My Site')
        ->set('site_tagline', 'An awesome site')
        ->set('site_url', 'https://example.test')
        ->set('admin_email', 'admin@example.test')
        ->set('timezone', 'UTC')
        ->call('save');

    $response->assertHasNoErrors();

    // Assert storage file contains the saved values
    $this->assertFileExists(storage_path('app/settings.json'));

    $contents = json_decode(file_get_contents(storage_path('app/settings.json')), true);

    expect($contents['site_title'])->toEqual('My Site');
    expect($contents['site_tagline'])->toEqual('An awesome site');
    expect($contents['site_url'])->toEqual('https://example.test');
    expect($contents['admin_email'])->toEqual('admin@example.test');
    expect($contents['timezone'])->toEqual('UTC');

    // cleanup
    @unlink(storage_path('app/settings.json'));
});
