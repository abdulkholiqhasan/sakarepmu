<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'path',
        'mime_type',
        'size',
        'user_id',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'size' => 'integer',
        'user_id' => 'string',
    ];

    /**
     * Get the full URL for the media file
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Append url to JSON
     */
    protected $appends = ['url'];

    /**
     * Owner relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
