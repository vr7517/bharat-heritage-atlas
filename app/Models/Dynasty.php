<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dynasty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'dating_statement',
        'start_year',
        'end_year',
        'region',
        'capital',
        'description',
    ];

    protected $casts = [
        'start_year' => 'integer',
        'end_year' => 'integer',
    ];

    public function heritageSites(): HasMany
    {
        return $this->hasMany(HeritageSite::class, 'primary_dynasty_id');
    }

    public function objects(): HasMany
    {
        return $this->hasMany(HeritageObject::class, 'dynasty_id');
    }
}
