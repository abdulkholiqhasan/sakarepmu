<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Post;
use App\Models\Blog\Category;
use App\Models\Blog\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $posts = Post::query()
            ->when($q, fn($qB) => $qB->where('title', 'like', "%{$q}%"))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('blog.posts.index', compact('posts'));
    }

    public function create()
    {
        // Do not load all categories for the create form — use AJAX search instead.
        $categories = collect();
        return view('blog.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'category_id' => 'nullable|uuid|exists:categories,id',
            'published' => 'sometimes|boolean',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'published_at' => 'nullable|date',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'featured_image_file' => 'nullable|file|image|max:5120',
            'status' => 'nullable|string',
            'post_type' => 'nullable|string',
            'tags' => 'nullable',
            'new_tags' => 'nullable|string'
        ]);

        // Determine publish action: publish or save as draft
        $action = $request->input('action'); // 'publish' | 'draft' | null
        if ($action === 'publish') {
            $data['published'] = true;
            if (empty($data['published_at'])) {
                $data['published_at'] = now();
            }
        } elseif ($action === 'draft') {
            $data['published'] = false;
            $data['published_at'] = null;
        } else {
            $data['published'] = $request->has('published') ? (bool) $request->input('published') : false;
        }

        // use provided slug if present, otherwise generate unique slug from title
        if (empty($data['slug'])) {
            $data['slug'] = Post::generateUniqueSlug($data['title']);
        }
        $data['author_id'] = $request->input('author_id') ?? (Auth::id() ?? null);

        // tags may come as a comma separated list in the hidden input
        $tagsRaw = $request->input('tags');
        $tags = [];
        if ($tagsRaw) {
            if (is_array($tagsRaw)) {
                $tags = $tagsRaw;
            } else {
                $tags = array_filter(array_map('trim', explode(',', $tagsRaw)));
            }
        }
        unset($data['tags']);

        // handle uploaded featured image file
        if ($request->hasFile('featured_image_file')) {
            $file = $request->file('featured_image_file');
            $path = \Illuminate\Support\Facades\Storage::disk('public')->putFile('uploads', $file);
            $data['featured_image'] = \Illuminate\Support\Facades\Storage::url($path);
        }

        $post = Post::create($data);
        if (!empty($tags)) {
            // for simplicity, filter out any 'new:' placeholders here — those will be created in subsequent step
            $existing = array_filter($tags, fn($t) => strpos((string)$t, 'new:') !== 0);
            $post->tags()->sync($existing);
        }

        // create new tags only when the post is published (new tags remain draft otherwise)
        if (!empty($data['published']) && $request->filled('new_tags')) {
            $newNames = array_filter(array_map('trim', explode(',', $request->input('new_tags'))));
            foreach ($newNames as $name) {
                if (!$name) continue;
                $tag = Tag::firstOrCreate(['name' => $name], ['slug' => Tag::generateUniqueSlug($name)]);
                $post->tags()->syncWithoutDetaching([$tag->id]);
            }
        }

        // Optionally sync categories as many-to-many
        if ($request->has('categories')) {
            $post->categories()->sync($request->input('categories'));
        }

        return redirect()->route('posts.index')->with('success', 'Post created.');
    }

    public function edit(Post $post)
    {
        // Only provide the post's currently selected categories to avoid loading large sets.
        $categories = $post->categories()->orderBy('name')->get();
        return view('blog.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'category_id' => 'nullable|uuid|exists:categories,id',
            'published' => 'sometimes|boolean',
            'slug' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'featured_image_file' => 'nullable|file|image|max:5120',
            'status' => 'nullable|string',
            'post_type' => 'nullable|string',
            'tags' => 'nullable',
            'new_tags' => 'nullable|string'
        ]);

        // Handle action buttons: 'update' (normal save), 'revert' (revert to draft), 'publish', 'draft'
        $action = $request->input('action');

        if ($action === 'publish') {
            $data['published'] = true;
            if (empty($data['published_at'])) {
                $data['published_at'] = now();
            }
        } elseif ($action === 'revert' || $action === 'draft') {
            // revert to draft
            $data['published'] = false;
            $data['published_at'] = null;
        } else {
            $data['published'] = $request->has('published') ? (bool) $request->input('published') : false;
            // published_at: if user did not provide it in the update payload, keep existing published_at
            if (!$request->filled('published_at') && array_key_exists('published_at', $data)) {
                unset($data['published_at']);
            }
        }

        // If slug provided in update use it; otherwise regenerate based on title (excluding this post)
        if (empty($data['slug'])) {
            $data['slug'] = Post::generateUniqueSlug($data['title'], $post->id);
        } else {
            // ensure slug uniqueness excluding current post
            if (Post::where('slug', $data['slug'])->where('id', '!=', $post->id)->exists()) {
                $data['slug'] = Post::generateUniqueSlug($data['slug'], $post->id);
            }
        }
        $data['author_id'] = $request->input('author_id') ?? ($post->author_id ?? (Auth::id() ?? null));

        $tagsRaw = $request->input('tags');
        $tags = [];
        if ($tagsRaw) {
            if (is_array($tagsRaw)) {
                $tags = $tagsRaw;
            } else {
                $tags = array_filter(array_map('trim', explode(',', $tagsRaw)));
            }
        }
        unset($data['tags']);

        // handle uploaded featured image file
        if ($request->hasFile('featured_image_file')) {
            $file = $request->file('featured_image_file');
            $path = \Illuminate\Support\Facades\Storage::disk('public')->putFile('uploads', $file);
            $data['featured_image'] = \Illuminate\Support\Facades\Storage::url($path);
        }

        $post->update($data);

        if (!empty($tags)) {
            $existing = array_filter($tags, fn($t) => strpos((string)$t, 'new:') !== 0);
            $post->tags()->sync($existing);
        } else {
            $post->tags()->sync([]);
        }

        if (!empty($data['published']) && $request->filled('new_tags')) {
            $newNames = array_filter(array_map('trim', explode(',', $request->input('new_tags'))));
            foreach ($newNames as $name) {
                if (!$name) continue;
                $tag = Tag::firstOrCreate(['name' => $name], ['slug' => Tag::generateUniqueSlug($name)]);
                $post->tags()->syncWithoutDetaching([$tag->id]);
            }
        }

        if ($request->has('categories')) {
            $post->categories()->sync($request->input('categories'));
        }

        return redirect()->route('posts.index')->with('success', 'Post updated.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted.');
    }
}
