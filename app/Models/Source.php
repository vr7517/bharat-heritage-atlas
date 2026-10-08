<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Source extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'authors',
        'publication_year',
        'source_type',
        'publisher',
        'journal_or_series',
        'volume_issue',
        'pages',
        'doi',
        'isbn_issn',
        'url',
        'access_date',
        'archival_location',
        'reliability_tier',
        'notes',
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'access_date' => 'date',
    ];

    public function evidence(): BelongsToMany
    {
        return $this->belongsToMany(Evidence::class, 'evidence_source')
            ->withPivot('specific_pages', 'direct_quotation_or_data', 'citation_context')
            ->withTimestamps();
    }
}
