<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        // If request wants JSON (api/browser), return json list; otherwise show blade view
        $query = Media::query();

        if ($request->filled('q')) {
            $query->where('filename', 'like', '%' . $request->input('q') . '%');
        }

        $media = $query->orderBy('created_at', 'desc')->paginate(10);

        if ($request->wantsJson()) {
            return response()->json($media);
        }

        return view('manage.media.index', compact('media'));
    }

    public function create()
    {
        return view('manage.media.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // max 10MB
        ]);

        $file = $request->file('file');
        // Preserve original filename and store inside date-based folder: uploads/YYYY/MM/DD
        $originalName = $file->getClientOriginalName();
        $datePath = now()->format('Y') . '/' . now()->format('m') . '/' . now()->format('d');
        $storageDir = 'uploads/' . $datePath;
        $path = $storageDir . '/' . $originalName;

        // If file with same name exists in the same dated folder, reject upload to avoid overwrite
        if (Storage::disk('public')->exists($path)) {
            return back()->withErrors(['file' => 'A file with that name already exists for today. Please rename your file and try again.'])->withInput();
        }

        // Ensure directory exists (storeAs will create it, but keep explicit for clarity)
        // Then store the file
        $path = $file->storeAs($storageDir, $originalName, 'public');

        try {
            $media = Media::create([
                'filename' => $originalName,
                'path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'user_id' => $request->user()?->id,
            ]);
        } catch (\Throwable $e) {
            // If DB insert fails, delete the stored file to avoid orphan files
            try {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                }
            } catch (\Throwable $_) {
                // ignore storage delete errors
            }

            // Log the original exception and return an error to the user
            logger()->error('Failed to create media record after storing file', [
                'exception' => $e,
                'path' => $path,
                'user_id' => $request->user()?->id,
            ]);

            return back()->withErrors(['file' => 'Upload failed (internal error). Please try again.']);
        }

        return redirect()->route('media.index')->with('success', 'File uploaded');
    }

    public function show(Media $media)
    {
        return view('manage.media.show', compact('media'));
    }

    public function edit(Media $media)
    {
        return view('manage.media.edit', compact('media'));
    }

    public function update(Request $request, Media $media)
    {
        $request->validate([
            'filename' => 'required|string|max:255',
        ]);

        $media->update([
            'filename' => $request->input('filename'),
        ]);

        return redirect()->route('media.index')->with('success', 'Media updated');
    }

    public function destroy(Media $media)
    {
        // Delete file from storage
        if (Storage::disk('public')->exists($media->path)) {
            Storage::disk('public')->delete($media->path);
        }

        $media->delete();

        return redirect()->route('media.index')->with('success', 'Media deleted');
    }
}
