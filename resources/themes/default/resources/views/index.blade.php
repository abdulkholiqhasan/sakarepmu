<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteTitle ?? 'Beranda' }} - {{ $siteTagline ?? 'Platform blog minimalis untuk berbagi cerita, inspirasi, dan pengetahuan.' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @extends('layouts.app')

    @section('title', $siteTitle ?? 'Beranda')

    @section('content')
    <div class="container mx-auto py-12">
        <div class="text-center mb-12">
              <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $siteTitle ?? 'Sakarepku' }}</h1>
              <p class="text-lg text-gray-600 mb-6">Platform blog minimalis untuk berbagi cerita, inspirasi, dan pengetahuan.</p>
        </div>
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($posts ?? [] as $post)
                <div class="bg-white rounded-lg shadow p-6 flex flex-col">
                    @if($post->featured_image)
                        <div class="w-full aspect-[16/9] mb-4 overflow-hidden rounded bg-gray-100 flex items-center justify-center">
                            <img src="{{ $post->featured_image }}" alt="Featured" class="w-full h-full object-cover" style="aspect-ratio:16/9;">
                        </div>
                    @endif
                    <h2 class="text-xl font-semibold mb-2">
                        <a href="{{ route('blog.show', $post->slug) }}" class="hover:underline">{{ $post->title }}</a>
                    </h2>
                    <p class="text-gray-500 text-sm mb-2">{{ $post->created_at->format('d M Y') }} oleh {{ $post->author->name ?? 'Unknown' }}</p>
                    <p class="mb-4 text-gray-700">{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="mt-auto text-blue-600 hover:underline font-medium">Baca selengkapnya</a>
                </div>
            @endforeach
        </div>
        @if(isset($posts) && $posts->count())
            <div class="mt-10 flex justify-center">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
    @endsection
</body>
</html>
