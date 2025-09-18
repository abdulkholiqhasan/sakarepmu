<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        // List files from public/uploads
        $files = Storage::disk('public')->files('uploads');
        $items = array_map(function ($path) {
            return [
                'path' => $path,
                'url' => Storage::url($path),
            ];
        }, $files);

        return response()->json($items);
    }
}
