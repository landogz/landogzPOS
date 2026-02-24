<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BirSetting extends Model
{
    protected $table = 'bir_settings';

    protected $fillable = [
        'branch_id', 'provider_name', 'provider_address',
        'tin', 'accreditation_number', 'series_start', 'series_end',
        'valid_from', 'valid_until', 'ptu_number', 'validity_statement', 'footer_text',
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
