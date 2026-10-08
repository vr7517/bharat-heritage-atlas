<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class HeritageSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'location_id',
        'primary_period_id',
        'primary_dynasty_id',
        'site_type',
        'dating_statement',
        'start_year',
        'end_year',
        'is_dating_uncertain',
        'protection_status',
        'summary',
        'historical_context',
        'architectural_description',
        'is_published',
    ];

    protected $casts = [
        'start_year' => 'integer',
        'end_year' => 'integer',
        'is_dating_uncertain' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function primaryPeriod(): BelongsTo
    {
        return $this->belongsTo(Period::class, 'primary_period_id');
    }

    public function primaryDynasty(): BelongsTo
    {
        return $this->belongsTo(Dynasty::class, 'primary_dynasty_id');
    }

    public function objects(): HasMany
    {
        return $this->hasMany(HeritageObject::class);
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
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
