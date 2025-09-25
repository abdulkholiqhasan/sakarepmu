<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Services\SettingsService;

use DateTimeZone;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Blade helper to check permissions using User::hasPermission
        Blade::if('permission', function ($permission) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if (! $user || ! method_exists($user, 'hasPermission')) return false;

            // Accept pipe-separated list or array
            if (is_array($permission)) {
                foreach ($permission as $p) {
                    if ($user->hasPermission($p)) return true;
                }
                return false;
            }

            if (is_string($permission) && str_contains($permission, '|')) {
                foreach (explode('|', $permission) as $p) {
                    if ($user->hasPermission(trim($p))) return true;
                }
                return false;
            }

            return $user->hasPermission((string) $permission);
        });

        // Load persisted general settings (if any) so changes take effect at runtime.
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
                // Basic validation: only set if it's a valid timezone identifier
                if (in_array($tz, DateTimeZone::listIdentifiers(), true)) {
                    config(['app.timezone' => $tz]);
                    try {
                        date_default_timezone_set($tz);
                    } catch (\Exception $e) {
                        // ignore invalid timezone at runtime
                    }
                }
            }

            // Site tagline -> custom app.tagline (optional)
            if ($tagline = $settings->get('site_tagline')) {
                config(['app.tagline' => $tagline]);
            }

            // Locale -> app.locale (and optionally set the translator locale)
            if ($locale = $settings->get('locale')) {
                // Basic validation: ensure it's one of configured locales
                $available = array_keys(config('locales', []));
                if (in_array($locale, $available, true)) {
                    config(['app.locale' => $locale]);
                    try {
                        // set the translator locale if the translator exists
                        if (function_exists('app')) {
                            $app = app();
                            if ($app && $app->has('translator')) {
                                $app->make('translator')->setLocale($locale);
                            }
                        }
                    } catch (\Throwable $e) {
                        // ignore translator errors
                    }
                }
            }
        } catch (\Throwable $e) {
            // Defensively ignore any errors during boot to avoid breaking the app when settings file is missing or corrupt.
        }
    }
}
