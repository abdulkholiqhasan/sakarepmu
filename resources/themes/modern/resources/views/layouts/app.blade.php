<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\Setting::where('key', 'site_title')->value('value') ?? config('settings.site_title', 'Blog')) - MODERN THEME</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/wysiwyg-post.css') }}">
        <style>
            /* Page-level override: force WYSIWYG embed centering & larger responsive size */
            .prose .ql-video-embed,
            .prose .ql-html-embed,
            .prose iframe,
            .prose embed,
            .prose video {
                display: block !important;
                margin: 1.75em auto !important;
                width: min(1100px, 90%) !important;
                max-width: 100% !important;
                aspect-ratio: 16/9 !important;
                height: auto !important;
                border-radius: 0.5rem !important;
                box-shadow: 0 6px 18px -6px rgba(0,0,0,0.35) !important;
                background: #000 !important;
            }
        </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <nav class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-lg mb-8">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-white">🚀 {{ \App\Models\Setting::where('key', 'site_title')->value('value') ?? config('settings.site_title', 'Sakarepku') }} - MODERN</a>
                <div class="space-x-6">
                <a href="/blog" class="text-white hover:text-yellow-300 font-medium transition-colors">Blog</a>
                <a href="/categories" class="text-white hover:text-yellow-300 font-medium transition-colors">Kategori</a>
                <a href="/about" class="text-white hover:text-yellow-300 font-medium transition-colors">Tentang</a>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>
    <footer class="bg-gradient-to-r from-blue-600 to-indigo-700 mt-12 py-6 shadow-lg">
        <div class="container mx-auto text-center text-white">
            <p class="text-lg font-semibold">&copy; {{ date('Y') }} {{ \App\Models\Setting::where('key', 'site_title')->value('value') ?? 'Sakarepku' }}</p>
            <p class="text-sm opacity-80 mt-1">✨ Powered by Modern Theme ✨</p>
        </div>
    </footer>
</body>
</html>
