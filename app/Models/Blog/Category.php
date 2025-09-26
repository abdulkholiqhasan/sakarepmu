<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    // UUID primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'slug',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Generate a unique slug for the category.
     * If the slug exists, append -1, -2, etc until unique.
     *
     * @param  string  $name
     * @param  int|null  $exceptId  ID to exclude from uniqueness check (useful on update)
     * @return string
     */
    public static function generateUniqueSlug(string $name, $exceptId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;

        while (self::where('slug', $slug)
            ->when($exceptId, function ($q) use ($exceptId) {
                return $q->where('id', '!=', $exceptId);
            })->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'category_post', 'category_id', 'post_id');
    }
}
