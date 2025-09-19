<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog\Page;
use Illuminate\Support\Str;

class PagesController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('created_at', 'desc')->paginate(10);
        return view('blog.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('blog.pages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'nullable|string',
            'published' => 'sometimes|boolean',
            'published_at' => 'nullable|date',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Page::generateUniqueSlug($data['title']);
        }

        // Handle publish/draft actions
        $action = $request->input('action');
        if ($action === 'publish') {
            $data['published'] = true;
            if (empty($data['published_at'])) {
                $data['published_at'] = now();
            }
        } elseif ($action === 'draft') {
            $data['published'] = false;
            $data['published_at'] = null;
        } else {
            $data['published'] = isset($data['published']) ? (bool) $data['published'] : false;
        }

        $page = Page::create($data);

        return redirect()->route('pages.index')->with('success', 'Page created.');
    }

    public function show(Page $page)
    {
        return view('blog.pages.show', compact('page'));
    }

    public function edit(Page $page)
    {
        return view('blog.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id . ',id',
            'content' => 'nullable|string',
            'published' => 'sometimes|boolean',
            'published_at' => 'nullable|date',
        ]);

        // Handle action buttons on update: publish, revert/draft, or normal update
        $action = $request->input('action');
        if ($action === 'publish') {
            $data['published'] = true;
            if (empty($data['published_at'])) {
                $data['published_at'] = now();
            }
        } elseif ($action === 'revert' || $action === 'draft') {
            $data['published'] = false;
            $data['published_at'] = null;
        } else {
            $data['published'] = isset($data['published']) ? (bool) $data['published'] : false;
            if (!$request->filled('published_at') && array_key_exists('published_at', $data)) {
                unset($data['published_at']);
            }
        }

        if (empty($data['slug'])) {
            $data['slug'] = Page::generateUniqueSlug($data['title'], $page->id);
        }

        $page->update($data);

        return redirect()->route('pages.index')->with('success', 'Page updated.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('pages.index')->with('success', 'Page deleted.');
    }
}
