<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Dataset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'source_organization',
        'source_url',
        'dataset_type',
        'version',
        'release_date',
        'license',
        'file_path',
        'file_name',
        'file_size_bytes',
        'file_mime_type',
        'record_count',
        'notes',
        'is_published',
        'is_featured',
    ];

    protected $casts = [
        'release_date' => 'date',
        'file_size_bytes' => 'integer',
        'record_count' => 'integer',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Scope query to only published datasets.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope query to only featured datasets.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get the accessible public URL for the dataset file asset.
     */
    public function getFileUrlAttribute(): ?string
    {
        if (! $this->file_path) {
            return null;
        }

        return Storage::disk('public')->url($this->file_path);
    }

    /**
     * Get human-readable formatted file size.
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if (! $this->file_size_bytes) {
            return 'N/A';
        }

        $bytes = $this->file_size_bytes;
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;

        return number_format($bytes / pow(1024, $power), 1) . ' ' . ($units[$power] ?? 'B');
    }
}
