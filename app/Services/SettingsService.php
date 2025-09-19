<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class SettingsService
{
    protected string $file = '';

    public function __construct()
    {
        // canonical settings file
        $this->file = storage_path('app/settings.json');

        // migrate legacy private settings file if present to the canonical location
        $legacy = storage_path('app/private/settings.json');

        if (file_exists($legacy) && ! file_exists($this->file)) {
            try {
                @mkdir(dirname($this->file), 0755, true);
                copy($legacy, $this->file);
                // optionally remove legacy file to avoid future confusion
                @unlink($legacy);
            } catch (\Throwable $e) {
                // ignore migration failures; reads will continue to fallback to defaults
            }
        }
    }

    public function all(): array
    {
        if (! file_exists($this->file)) {
            return [];
        }

        $contents = file_get_contents($this->file);

        return json_decode($contents, true) ?: [];
    }

    public function get(string $key, $default = null)
    {
        return $this->all()[$key] ?? $default;
    }

    public function set(array $data): void
    {
        $existing = $this->all();

        $merged = array_merge($existing, $data);

        file_put_contents($this->file, json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
