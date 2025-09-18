<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $table = 'pages';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'title',
        'slug',
        'content',
        'published',
        'published_at',
        'modified_at',
    ];

    protected $casts = [
        'published' => 'boolean',
        'published_at' => 'datetime',
        'modified_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($page) {
            if (empty($page->id)) {
                $page->id = (string) Str::uuid();
            }
            if (empty($page->slug) && !empty($page->title)) {
                $page->slug = static::generateUniqueSlug($page->title);
            }
        });

        static::updating(function ($page) {
            $page->modified_at = now();
        });
    }

    public static function generateUniqueSlug(string $title, $exceptId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)
            ->when($exceptId, function ($q) use ($exceptId) {
                $q->where('id', '!=', $exceptId);
            })->exists()
        ) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
