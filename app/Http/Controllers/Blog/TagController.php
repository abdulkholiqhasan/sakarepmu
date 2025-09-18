<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $q = request('q');

        $tags = Tag::when($q, function ($query, $q) {
            $query->where('name', 'like', "%{$q}%");
        })->orderBy('name')->paginate(20)->withQueryString();

        return view('blog.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('blog.tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $validated['slug'] = Tag::generateUniqueSlug($validated['name']);

        Tag::create($validated);

        return redirect()->route('tags.index')->with('success', 'Tag created.');
    }

    public function edit(Tag $tag)
    {
        return view('blog.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $validated['slug'] = Tag::generateUniqueSlug($validated['name'], $tag->id);

        $tag->update($validated);

        return redirect()->route('tags.index')->with('success', 'Tag updated.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('tags.index')->with('success', 'Tag deleted.');
    }
}
