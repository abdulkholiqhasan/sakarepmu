<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SettingsService;
use DateTimeZone;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // nothing to bind here for now
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            $settings = new SettingsService();

            // Site title -> app.name
            if ($title = $settings->get('site_title')) {
                config(['app.name' => $title]);
            }

            // Site URL -> app.url
            if ($url = $settings->get('site_url')) {
                config(['app.url' => $url]);
            }

            // Admin Email -> mail.from.address
            if ($admin = $settings->get('admin_email')) {
                config(['mail.from.address' => $admin]);
            }

            // Timezone -> app.timezone and PHP runtime timezone
            if ($tz = $settings->get('timezone')) {
                if (in_array($tz, DateTimeZone::listIdentifiers(), true)) {
                    config(['app.timezone' => $tz]);
                    try {
                        date_default_timezone_set($tz);
                    } catch (\Exception $e) {
                        // ignore
                    }
                }
            }

            // Site tagline -> app.tagline (optional)
            if ($tagline = $settings->get('site_tagline')) {
                config(['app.tagline' => $tagline]);
            }

            // Locale -> app.locale and translator locale
            if ($locale = $settings->get('locale')) {
                $available = array_keys(config('locales', []));
                if (in_array($locale, $available, true)) {
                    config(['app.locale' => $locale]);
                    try {
                        if (function_exists('app')) {
                            $app = app();
                            if ($app && $app->has('translator')) {
                                $app->make('translator')->setLocale($locale);
                            }
                        }
                    } catch (\Throwable $e) {
                        // ignore
                    }
                }
            }
        } catch (\Throwable $e) {
            // Defensive: don't break app boot if settings file missing or corrupt
        }
    }
}
