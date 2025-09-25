<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    private function ensurePermission($request, string $permission): void
    {
        $user = $request->user();
        if (! $user || ! method_exists($user, 'hasPermission') || ! $user->hasPermission($permission)) {
            abort(403);
        }
    }

    public function index()
    {
        $this->ensurePermission(request(), 'create tags');
        $q = request('q');

        $tags = Tag::when($q, function ($query, $q) {
            $query->where('name', 'like', "%{$q}%");
        })->orderBy('name')->paginate(10)->withQueryString();

        return view('blog.tags.index', compact('tags'));
    }

    public function create()
    {
        $this->ensurePermission(request(), 'create tags');
        return view('blog.tags.create');
    }

    public function store(Request $request)
    {
        $this->ensurePermission($request, 'create tags');
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:tags,slug'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Tag::generateUniqueSlug($validated['name']);
        }

        Tag::create($validated);

        // If AJAX/JSON request, return the created tag as JSON so forms can create inline
        if ($request->wantsJson() || $request->ajax()) {
            $tag = Tag::where('name', $validated['name'])->first();
            return response()->json($tag, 201);
        }

        return redirect()->route('tags.index')->with('success', 'Tag created.');
    }

    public function edit(Tag $tag)
    {
        $this->ensurePermission(request(), 'create tags');
        return view('blog.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $this->ensurePermission($request, 'create tags');
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:tags,slug,' . $tag->id],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Tag::generateUniqueSlug($validated['name'], $tag->id);
        }

        $tag->update($validated);

        return redirect()->route('tags.index')->with('success', 'Tag updated.');
    }

    public function destroy(Tag $tag)
    {
        $this->ensurePermission(request(), 'create tags');
        $tag->delete();

        return redirect()->route('tags.index')->with('success', 'Tag deleted.');
    }

    /**
     * JSON search endpoint for tags used by the post form typeahead.
     */
    public function search(Request $request)
    {
        // Allow search for authenticated users who can create/edit posts.
        $user = $request->user();
        if (! $user || ! method_exists($user, 'hasPermission') || ! (
            $user->hasPermission('create posts') || $user->hasPermission('edit posts') || $user->hasPermission('manage posts')
        )) {
            abort(403);
        }

        $q = $request->query('q');

        $results = Tag::when($q, function ($query, $q) {
            $query->where('name', 'like', "%{$q}%");
        })->orderBy('name')->limit(50)->get(['id', 'name']);

        return response()->json($results);
    }
}
