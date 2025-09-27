<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog\Page;

class PageController extends Controller
{
    /**
     * Show a published page by slug on the frontend.
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('published', true)
            ->firstOrFail();

        return view('page', compact('page'));
    }
}
