@extends('layouts.app')

@section('title', $tag->name . ' - ' . (\App\Models\Setting::where('key', 'site_title')->value('value') ?? config('settings.site_title', 'Blog')))

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">Tag: {{ $tag->name }}</h1>
    @if($posts->count())
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($posts as $post)
                <div class="bg-white rounded shadow p-4">
                    @if($post->featured_image)
                        <img src="{{ $post->featured_image }}" alt="Featured" class="mb-4 w-full h-48 object-cover rounded">
                    @endif
                    <h2 class="text-xl font-semibold mb-2">
                        <a href="{{ route('blog.show', $post->slug) }}" class="hover:underline">{{ $post->title }}</a>
                    </h2>
                    <p class="text-gray-600 text-sm mb-2">{{ ($post->published_at ?? $post->updated_at ?? $post->created_at)->format('d M Y') }} oleh {{ $post->author->name ?? 'Unknown' }}</p>
                    <p class="mb-3">{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="text-blue-600 hover:underline">Baca selengkapnya</a>
                </div>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    @else
        <p>Tidak ada post ditemukan dengan tag ini.</p>
    @endif
</div>
@endsection
