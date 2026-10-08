<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'heritage_site_id',
        'object_id',
        'language',
        'script',
        'dating_statement',
        'start_year',
        'end_year',
        'donor',
        'ruler_mentioned',
        'epigraphic_reference',
        'raw_text',
        'translation',
        'interpretation_notes',
        'is_published',
    ];

    protected $casts = [
        'start_year' => 'integer',
        'end_year' => 'integer',
        'is_published' => 'boolean',
    ];

    public function heritageSite(): BelongsTo
    {
        return $this->belongsTo(HeritageSite::class);
    }

    public function object(): BelongsTo
    {
        return $this->belongsTo(HeritageObject::class, 'object_id');
    }

    public function claims(): MorphMany
    {
        return $this->morphMany(Claim::class, 'claimable');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
