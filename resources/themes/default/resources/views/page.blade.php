@extends('layouts.app')

@section('title', $page->title . ' - ' . (\App\Models\Setting::where('key', 'site_title')->value('value') ?? config('settings.site_title', 'Blog')))

@section('content')
<div class="container mx-auto py-8">
    <nav class="mb-6 text-sm text-gray-500">
        <a href="/" class="hover:underline">Beranda</a>
        &raquo; <span class="text-gray-700">{{ $page->title }}</span>
    </nav>
    <article class="mx-auto bg-white rounded shadow p-6">
        <h1 class="text-3xl font-bold mb-4">{{ $page->title }}</h1>
        <div class="prose max-w-none mb-6">
            {!! $page->content !!}
        </div>
    </article>
</div>
@endsection
