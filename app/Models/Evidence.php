<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Evidence extends Model
{
    use HasFactory;

    protected $table = 'evidence';

    protected $fillable = [
        'title',
        'evidence_type',
        'classification',
        'description',
        'stratigraphic_context',
        'methodology_applied',
        'uncertainty_notes',
    ];

    public function claims(): BelongsToMany
    {
        return $this->belongsToMany(Claim::class, 'claim_evidence')
            ->withPivot('relationship_type', 'scholarly_weight', 'analysis_notes')
            ->withTimestamps();
    }

    public function sources(): BelongsToMany
    {
        return $this->belongsToMany(Source::class, 'evidence_source')
            ->withPivot('specific_pages', 'direct_quotation_or_data', 'citation_context')
            ->withTimestamps();
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
