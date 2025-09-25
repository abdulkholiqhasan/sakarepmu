<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected string $tempSettingsFile = '';

    protected function setUp(): void
    {
        parent::setUp();

        // Use a temporary settings file for the test process to avoid
        // modifying the application's real settings.json during tests.
        $this->tempSettingsFile = sys_get_temp_dir() . '/settings_test_' . uniqid() . '.json';
        // ensure file exists
        file_put_contents($this->tempSettingsFile, json_encode([]));

        config(['settings.file' => $this->tempSettingsFile]);
    }

    protected function tearDown(): void
    {
        // remove temp settings file
        if ($this->tempSettingsFile && file_exists($this->tempSettingsFile)) {
            @unlink($this->tempSettingsFile);
        }

        parent::tearDown();
    }
}
