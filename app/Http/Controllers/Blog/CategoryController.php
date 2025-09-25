<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $q = request('q');

        $categories = Category::when($q, function ($query, $q) {
            $query->where('name', 'like', "%{$q}%");
        })->orderBy('name')->paginate(10)->withQueryString();

        return view('blog.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('blog.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Category::generateUniqueSlug($validated['name']);
        }

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category)
    {
        return view('blog.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug,' . $category->id],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Category::generateUniqueSlug($validated['name'], $category->id);
        }

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }

    /**
     * JSON search endpoint used by AJAX typeahead when categories list is large.
     * Returns array of {id, name} matching the optional 'q' query parameter.
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

        $results = Category::when($q, function ($query, $q) {
            $query->where('name', 'like', "%{$q}%");
        })->orderBy('name')->limit(50)->get(['id', 'name']);

        return response()->json($results);
    }
}
