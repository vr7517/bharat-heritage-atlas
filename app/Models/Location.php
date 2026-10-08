<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'historical_names',
        'state',
        'district',
        'taluk_tehsil',
        'latitude',
        'longitude',
        'altitude_meters',
        'uncertainty_radius_meters',
        'description',
    ];

    protected $casts = [
        'historical_names' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
        'altitude_meters' => 'integer',
        'uncertainty_radius_meters' => 'integer',
    ];

    public function heritageSites(): HasMany
    {
        return $this->hasMany(HeritageSite::class);
    }

    public function objects(): HasMany
    {
        return $this->hasMany(HeritageObject::class);
    }
}
