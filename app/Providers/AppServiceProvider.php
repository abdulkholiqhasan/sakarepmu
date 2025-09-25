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

        // General settings are applied by the dedicated SettingsServiceProvider to ensure
        // they are applied consistently during early boot.
    }
}
