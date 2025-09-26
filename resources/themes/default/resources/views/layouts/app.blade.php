<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\Setting::where('key', 'site_title')->value('value') ?? config('settings.site_title', 'Blog'))</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/wysiwyg-post.css') }}">
</head>
<body class="bg-gray-100 min-h-screen theme-default">
    <nav class="bg-white shadow mb-8">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-xl font-bold text-gray-800">{{ \App\Models\Setting::where('key', 'site_title')->value('value') ?? config('settings.site_title', 'Sakarepku') }}</a>
                <div class="space-x-6">
                <a href="/blog" class="text-gray-600 hover:text-blue-600">Blog</a>
                <a href="/categories" class="text-gray-600 hover:text-blue-600">Kategori</a>
                <a href="/about" class="text-gray-600 hover:text-blue-600">Tentang</a>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>
    <footer class="bg-white mt-12 py-4 shadow">
        <div class="container mx-auto text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} Sakarepku. All rights reserved.
        </div>
    </footer>
</body>
</html>
