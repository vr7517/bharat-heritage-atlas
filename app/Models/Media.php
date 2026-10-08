<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'mediable_type',
        'mediable_id',
        'file_path',
        'caption',
        'alt_text',
        'media_type',
        'license',
        'source_credit',
        'photographer_or_draughtsman',
        'capture_year',
    ];

    protected $casts = [
        'capture_year' => 'integer',
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
