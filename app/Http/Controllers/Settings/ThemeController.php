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
                $author = null;
                $authorUrl = null;
                $url = null;
                $version = null;

                $metaFile = $path . DIRECTORY_SEPARATOR . 'theme.json';
                if (File::exists($metaFile)) {
                    try {
                        $meta = json_decode(File::get($metaFile), true) ?: [];
                        $label = $meta['name'] ?? $label;
                        $description = $meta['description'] ?? null;

                        // Support multiple meta shapes for author/url
                        if (! empty($meta['author'])) {
                            if (is_array($meta['author'])) {
                                $author = $meta['author']['name'] ?? null;
                                $authorUrl = $meta['author']['url'] ?? null;
                                // keep theme url separate; author.url should not overwrite theme url
                                if (empty($url) && ! empty($meta['author']['url'])) {
                                    // only use as theme url if no top-level url provided
                                    $url = $meta['author']['url'];
                                }
                            } else {
                                $author = $meta['author'];
                            }
                        }

                        // If meta provides a top-level url (repo/site), prefer it when author url not set
                        if (! empty($meta['url']) && empty($url)) {
                            $url = $meta['url'];
                        }

                        // Version
                        if (! empty($meta['version'])) {
                            $version = (string) $meta['version'];
                        }
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
                    'author' => $author,
                    'author_url' => $authorUrl,
                    'url' => $url,
                    'version' => $version,
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
