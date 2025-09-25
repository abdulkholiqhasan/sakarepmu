<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class SettingsService
{
    protected string $file = '';

    public function __construct()
    {
        // canonical settings file (configurable via config/settings.php)
        $this->file = config('settings.file', storage_path('app/settings.json'));

        // migrate legacy private settings file if present to the canonical location
        $legacy = config('settings.legacy_file', storage_path('app/private/settings.json'));

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

    /**
     * Return all settings as an associative array. Prefer DB when table exists.
     */
    public function all(): array
    {
        try {
            if (Schema::hasTable('settings')) {
                return Setting::all()->pluck('value', 'key')->map(function ($v) {
                    $decoded = json_decode($v, true);
                    return $decoded === null ? $v : $decoded;
                })->toArray();
            }
        } catch (\Throwable $e) {
            // ignore DB errors and fallback to file
        }

        if (! file_exists($this->file)) {
            return [];
        }

        $contents = file_get_contents($this->file);

        return json_decode($contents, true) ?: [];
    }

    public function get(string $key, $default = null)
    {
        $all = $this->all();
        return $all[$key] ?? $default;
    }

    public function set(array $data): void
    {
        // Avoid accidental truncation: require non-empty data to apply
        if (empty($data)) {
            return;
        }

        // If DB available, write each key as JSON-encoded value
        try {
            if (Schema::hasTable('settings')) {
                foreach ($data as $k => $v) {
                    $value = is_scalar($v) ? $v : json_encode($v);
                    Setting::updateOrCreate(['key' => $k], ['value' => $value]);
                }

                return;
            }
        } catch (\Throwable $e) {
            // ignore DB write errors and fallback to file
        }

        // Fallback to file-based persistence
        $existing = $this->all();

        // Merge top-level keys so callers can update a subset without removing others
        $merged = array_merge($existing, $data);

        // Ensure directory exists
        @mkdir(dirname($this->file), 0755, true);

        // Create a backup of the current settings file to aid recovery in case of accidental overwrite
        if (file_exists($this->file)) {
            try {
                copy($this->file, $this->file . '.bak');
            } catch (\Throwable $e) {
                // ignore backup failures
            }
        }

        // Write atomically: write to temp file then rename
        $temp = $this->file . '.tmp';
        $encoded = json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        // Try to write with exclusive lock
        file_put_contents($temp, $encoded, LOCK_EX);

        // Rename over the original file (atomic on most filesystems)
        @rename($temp, $this->file);
    }
}
