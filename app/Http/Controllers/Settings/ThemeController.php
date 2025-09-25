<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SettingsService;
use Illuminate\Support\Facades\File;

class ThemeController extends Controller
{
    protected SettingsService $settings;

    public function __construct(SettingsService $settings)
    {
        $this->settings = $settings;
        $this->middleware('auth');
        // Only administrators (manage settings) can change the active theme
        $this->middleware('\\App\\Http\\Middleware\\EnsurePermission:manage settings');
    }

    public function index()
    {
        $base = resource_path('themes');
        $themes = [];

        if (is_dir($base)) {
            foreach (scandir($base) as $d) {
                if ($d === '.' || $d === '..') continue;
                $path = $base . DIRECTORY_SEPARATOR . $d;
                if (! is_dir($path)) continue;

                $label = ucwords(str_replace(['-', '_'], ' ', $d));
                $description = null;

                $metaFile = $path . DIRECTORY_SEPARATOR . 'theme.json';
                if (File::exists($metaFile)) {
                    try {
                        $meta = json_decode(File::get($metaFile), true) ?: [];
                        $label = $meta['name'] ?? $label;
                        $description = $meta['description'] ?? null;
                    } catch (\Throwable $e) {
                        // ignore malformed meta
                    }
                }

                $screenshotUrl = null;
                foreach (['resources/screenshot.png', 'public/screenshot.png', 'screenshot.png'] as $candidate) {
                    $file = $path . DIRECTORY_SEPARATOR . $candidate;
                    if (File::exists($file)) {
                        // image served by controller route
                        $screenshotUrl = route('themes.screenshot', ['theme' => $d]);
                        break;
                    }
                }

                $themes[] = [
                    'dir' => $d,
                    'name' => $label,
                    'description' => $description,
                    'screenshot' => $screenshotUrl,
                ];
            }
        }

        $active = $this->settings->get('theme', null);

        return view('livewire.settings.themes', compact('themes', 'active'));
    }

    public function activate(Request $request)
    {
        $request->validate(['theme' => ['required', 'string']]);
        $name = $request->input('theme');
        $base = resource_path('themes' . DIRECTORY_SEPARATOR . $name);

        if (! is_dir($base)) {
            return back()->with('error', __('Theme not found.'));
        }

        // Persist selected theme via SettingsService
        $this->settings->set(['theme' => $name]);

        return redirect()->route('appearance.themes')->with('status', __('Theme activated.'));
    }

    public function screenshot($theme)
    {
        $base = resource_path('themes' . DIRECTORY_SEPARATOR . $theme);
        foreach (['resources/screenshot.png', 'public/screenshot.png', 'screenshot.png'] as $candidate) {
            $file = $base . DIRECTORY_SEPARATOR . $candidate;
            if (File::exists($file)) {
                return response()->file($file);
            }
        }

        abort(404);
    }
}
