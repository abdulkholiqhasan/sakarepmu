<?php

use App\Services\SettingsService;

// Boot the application for this test so helpers like storage_path() are available.
uses(Tests\TestCase::class);

it('persists locale in settings and locales config contains the key', function () {
    $settings = new SettingsService();
    $settings->set(['locale' => 'id']);

    expect($settings->get('locale'))->toBe('id');
    $locales = config('locales');
    expect(array_key_exists('id', $locales))->toBeTrue();
    expect($locales['id'])->toBe('Bahasa Indonesia');
});
