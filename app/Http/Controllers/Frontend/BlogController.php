<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::where('published', true)
            ->orderByRaw("(CASE
                WHEN published_at IS NULL AND modified_at IS NULL THEN created_at
                WHEN published_at IS NULL THEN modified_at
                WHEN modified_at IS NULL THEN published_at
                WHEN published_at > modified_at THEN published_at
                ELSE modified_at
            END) DESC")
            ->paginate(10);
        return view('blog', compact('posts'));
    }

    public function show($slug)
    {
        $post = \App\Models\Blog\Post::where('slug', $slug)
            ->where('published', true)
            ->with(['author', 'tags'])
            ->firstOrFail();
        return view('single', compact('post'));
    }

    public function category($slug)
    {
        $category = \App\Models\Blog\Category::where('slug', $slug)->firstOrFail();
        $posts = $category->posts()->where('published', true)
            ->orderByRaw("(CASE
                WHEN published_at IS NULL AND modified_at IS NULL THEN created_at
                WHEN published_at IS NULL THEN modified_at
                WHEN modified_at IS NULL THEN published_at
                WHEN published_at > modified_at THEN published_at
                ELSE modified_at
            END) DESC")
            ->paginate(10);
        return view('category', compact('category', 'posts'));
    }
    public function tag($slug)
    {
        $tag = \App\Models\Blog\Tag::where('slug', $slug)->firstOrFail();
        $posts = $tag->posts()->where('published', true)
            ->orderByRaw("(CASE
                WHEN published_at IS NULL AND modified_at IS NULL THEN created_at
                WHEN published_at IS NULL THEN modified_at
                WHEN modified_at IS NULL THEN published_at
                WHEN published_at > modified_at THEN published_at
                ELSE modified_at
            END) DESC")
            ->paginate(10);
        return view('tag', compact('tag', 'posts'));
    }
    public function home()
    {
        $posts = \App\Models\Blog\Post::where('published', true)
            ->orderByRaw("(CASE
                WHEN published_at IS NULL AND modified_at IS NULL THEN created_at
                WHEN published_at IS NULL THEN modified_at
                WHEN modified_at IS NULL THEN published_at
                WHEN published_at > modified_at THEN published_at
                ELSE modified_at
            END) DESC")
            ->paginate(6);
        $siteTitle = \App\Models\Setting::where('key', 'site_title')->value('value') ?? 'Sakarepku';
        $siteTagline = \App\Models\Setting::where('key', 'site_tagline')->value('value') ?? 'Platform blog minimalis untuk berbagi cerita, inspirasi, dan pengetahuan.';
        return view('index', compact('posts', 'siteTitle', 'siteTagline'));
    }
}
