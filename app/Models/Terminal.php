<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Terminal extends Model
{
    protected $fillable = [
        'branch_id',
        'code',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function posSessions(): HasMany
    {
        return $this->hasMany(PosSession::class, 'terminal_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'terminal_id');
    }
}
