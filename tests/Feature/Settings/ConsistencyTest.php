<?php

use App\Providers\SettingsServiceProvider;
use App\Services\SettingsService;

test('settings persist and are applied consistently by provider', function () {
    $settings = new SettingsService();

    // set a test value
    $settings->set(['site_title' => 'Consistent E2E Test']);

    // confirm persistence
    expect($settings->get('site_title'))->toBe('Consistent E2E Test');

    // apply provider boot to simulate app bootstrap
    $provider = new SettingsServiceProvider(app());
    $provider->boot();

    // application config should reflect persisted value
    expect(config('app.name'))->toBe('Consistent E2E Test');

    // change it again and reapply
    $settings->set(['site_title' => 'Consistent E2E Test 2']);
    $provider->boot();
    expect(config('app.name'))->toBe('Consistent E2E Test 2');
});
