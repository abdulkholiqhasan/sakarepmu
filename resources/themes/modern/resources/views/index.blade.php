@extends('layouts.app')

@section('title', $siteTitle ?? 'Beranda')

@section('content')
<div class="container mx-auto py-12">
    <!-- MODERN THEME INDICATOR -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-lg mb-8 text-center">
        <h2 class="text-2xl font-bold">🚀 MODERN THEME AKTIF 🚀</h2>
        <p class="text-blue-100 mt-2">Theme modern berhasil diaktifkan!</p>
    </div>
    
    <div class="text-center mb-12">
          <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4">{{ $siteTitle ?? 'Sakarepku' }}</h1>
          <p class="text-xl text-gray-600 mb-6 font-medium">🌟 Platform blog modern untuk berbagi cerita, inspirasi, dan pengetahuan. 🌟</p>
    </div>
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($posts ?? [] as $post)
                <div class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 flex flex-col border border-blue-100">
                    @if($post->featured_image)
                        <div class="w-full aspect-[16/9] mb-4 overflow-hidden rounded-lg bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                            <img src="{{ $post->featured_image }}" alt="Featured" class="w-full h-full object-cover rounded-lg" style="aspect-ratio:16/9;">
                        </div>
                    @endif
                    <h2 class="text-xl font-bold mb-2 text-gray-800">
                        <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-600 transition-colors">{{ $post->title }}</a>
                    </h2>
                    <p class="text-blue-600 text-sm mb-2 font-medium">📅 {{ $post->created_at->format('d M Y') }} by {{ $post->author->name ?? 'Unknown' }}</p>
                    <p class="mb-4 text-gray-700 leading-relaxed">{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="mt-auto bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all font-medium text-center">✨ Baca selengkapnya</a>
                </div>
            @endforeach
        </div>
        @if(isset($posts) && $posts->count())
            <div class="mt-12 flex justify-center">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
