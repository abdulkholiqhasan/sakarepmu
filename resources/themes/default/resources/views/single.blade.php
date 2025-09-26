@extends('layouts.app')

@section('title', $post->title . ' - ' . (\App\Models\Setting::where('key', 'site_title')->value('value') ?? config('settings.site_title', 'Blog')))

@section('content')
<div class="container mx-auto py-8">
    <nav class="mb-6 text-sm text-gray-500">
        <a href="/blog" class="hover:underline">Blog</a>
        @foreach($post->categories as $category)
            &raquo; <a href="{{ route('category.show', $category->slug) }}" class="hover:underline">{{ $category->name }}</a>
        @endforeach
        &raquo; <span class="text-gray-700">{{ $post->title }}</span>
    </nav>
    <article class="max-w-2xl mx-auto bg-white rounded shadow p-6">
        <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>
        <p class="text-gray-600 text-sm mb-4">{{ $post->created_at->format('d M Y') }} oleh {{ $post->author->name ?? 'Unknown' }}</p>
        <div class="prose max-w-none mb-6">
            {!! $post->content !!}
        </div>
        @if($post->tags->count())
            <div class="mt-4">
                <span class="text-gray-500 text-sm">Tags:</span>
                @foreach($post->tags as $tag)
                    <span class="inline-block bg-gray-200 text-gray-700 px-2 py-1 rounded mr-2 text-xs">
                        <a href="{{ route('tag.show', $tag->slug) }}" class="hover:underline">{{ $tag->name }}</a>
                    </span>
                @endforeach
            </div>
        @endif
    </article>
</div>
@endsection
