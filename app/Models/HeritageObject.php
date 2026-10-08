<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class HeritageObject extends Model
{
    use HasFactory;

    protected $table = 'objects';

    protected $fillable = [
        'name',
        'slug',
        'heritage_site_id',
        'location_id',
        'period_id',
        'dynasty_id',
        'object_type',
        'material',
        'dimensions',
        'current_repository',
        'accession_number',
        'dating_statement',
        'start_year',
        'end_year',
        'is_dating_uncertain',
        'description',
        'iconographic_notes',
        'is_published',
    ];

    protected $casts = [
        'start_year' => 'integer',
        'end_year' => 'integer',
        'is_dating_uncertain' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function heritageSite(): BelongsTo
    {
        return $this->belongsTo(HeritageSite::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function dynasty(): BelongsTo
    {
        return $this->belongsTo(Dynasty::class);
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class, 'object_id');
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
