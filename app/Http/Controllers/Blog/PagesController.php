<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog\Page;
use Illuminate\Support\Str;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class PagesController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $status = $request->query('status');

        $pages = Page::query()
            ->when($q, fn($qB) => $qB->where('title', 'like', "%{$q}%")
                ->orWhere('content', 'like', "%{$q}%"))
            ->when($status === 'published', fn($qB) => $qB->where('published', true))
            ->when($status === 'draft', fn($qB) => $qB->where('published', false))
            ->with(['author'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
            'featured_image' => 'nullable|string',
            'featured_image_file' => 'nullable|file|image|max:5120',
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

        // handle uploaded featured image file (store in date folder, create Media record)
        if ($request->hasFile('featured_image_file')) {
            $file = $request->file('featured_image_file');
            $originalName = $file->getClientOriginalName();
            $filename = basename(preg_replace('/[\\\\\/]+/', '', (string) $originalName));
            $datePath = now()->format('Y') . '/' . now()->format('m') . '/' . now()->format('d');
            $storageDir = 'uploads/' . $datePath;
            $fullPath = $storageDir . '/' . $filename;
            // If file with same name exists in the same dated folder, reject upload to avoid overwrite
            if (Storage::disk('public')->exists($fullPath)) {
                return back()->withErrors(['featured_image_file' => 'A file with that name already exists for today. Please rename your file and try again.'])->withInput();
            }
            $path = $file->storeAs($storageDir, $filename, 'public');

            try {
                $media = Media::create([
                    'filename' => $filename,
                    'path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'user_id' => $request->user()?->id,
                ]);
                $data['featured_image'] = $media->url;
            } catch (\Throwable $e) {
                try {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                } catch (\Throwable $_) {
                }
                logger()->error('Failed to create media record after storing featured image for page', ['exception' => $e, 'path' => $path, 'user_id' => $request->user()?->id]);
                return back()->withErrors(['featured_image_file' => 'Upload failed (internal error). Please try again.'])->withInput();
            }
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
            'featured_image' => 'nullable|string',
            'featured_image_file' => 'nullable|file|image|max:5120',
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

        // handle uploaded featured image file
        if ($request->hasFile('featured_image_file')) {
            $file = $request->file('featured_image_file');
            $originalName = $file->getClientOriginalName();
            $filename = basename(preg_replace('/[\\\\\/]+/', '', (string) $originalName));
            $path = \Illuminate\Support\Facades\Storage::disk('public')->putFileAs('uploads', $file, $filename);
            $data['featured_image'] = \Illuminate\Support\Facades\Storage::url($path);
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
