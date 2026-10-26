<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'mediable_type',
        'mediable_id',
        'file_path',
        'caption',
        'alt_text',
        'media_type',
        'license',
        'source_credit',
        'photographer_or_draughtsman',
        'capture_year',
        'is_cover',
        'display_order',
    ];

    protected $casts = [
        'capture_year' => 'integer',
        'is_cover' => 'boolean',
        'display_order' => 'integer',
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the resolved public URL of the media file.
     */
    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://') || str_starts_with($this->file_path, '/')) {
            return $this->file_path;
        }

        return Storage::disk('public')->url($this->file_path);
    }
}
