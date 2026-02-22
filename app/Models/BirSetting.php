<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BirSetting extends Model
{
    protected $table = 'bir_settings';

    protected $fillable = [
        'branch_id', 'tin', 'accreditation_number',
        'series_start', 'series_end', 'valid_from', 'valid_until', 'footer_text',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
