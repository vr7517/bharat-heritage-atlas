<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'claimable_type',
        'claimable_id',
        'claim_type',
        'statement',
        'consensus_status',
        'summary_justification',
    ];

    public function claimable(): MorphTo
    {
        return $this->morphTo();
    }

    public function evidence(): BelongsToMany
    {
        return $this->belongsToMany(Evidence::class, 'claim_evidence')
            ->withPivot('relationship_type', 'scholarly_weight', 'analysis_notes')
            ->withTimestamps();
    }
}
