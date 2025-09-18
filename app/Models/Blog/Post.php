<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Blog\Tag;

class Post extends Model
{
    protected $table = 'posts';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'title',
        'slug',
        'content',
        'category_id',
        'published',
        'published_at',
        'modified_at',
        'author_id',
        'excerpt',
        'featured_image',
        'status',
        'post_type'
    ];

    protected $casts = [
        'published' => 'boolean',
        'published_at' => 'datetime',
        'modified_at' => 'datetime',
        'author_id' => 'string',
    ];

    protected static function booted()
    {
        static::creating(function ($post) {
            if (empty($post->id)) {
                $post->id = (string) Str::uuid();
            }
            if (empty($post->slug) && !empty($post->title)) {
                $post->slug = static::generateUniqueSlug($post->title);
            }
        });

        static::updating(function ($post) {
            // set modified_at timestamp when model is updated
            $post->modified_at = now();
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

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_post', 'post_id', 'category_id');
    }

    public function scopePublished($q)
    {
        return $q->where('published', true)->where('status', 'published');
    }
}
