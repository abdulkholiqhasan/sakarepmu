<?php

return [
    // Persistent settings file path. Can be overridden in testing via
    // the SETTINGS_FILE environment variable (set in phpunit.xml).
    'file' => env('SETTINGS_FILE', storage_path('app/settings.json')),
];
