<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'dating_statement',
        'start_year',
        'end_year',
        'start_era',
        'end_era',
        'description',
    ];

    protected $casts = [
        'start_year' => 'integer',
        'end_year' => 'integer',
    ];

    public function heritageSites(): HasMany
    {
        return $this->hasMany(HeritageSite::class, 'primary_period_id');
    }

    public function sites(): HasMany
    {
        return $this->heritageSites();
    }

    public function objects(): HasMany
    {
        return $this->hasMany(HeritageObject::class, 'period_id');
    }
}
