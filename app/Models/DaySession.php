<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DaySession extends Model
{
    protected $table = 'day_sessions';

    protected $fillable = [
        'branch_id', 'terminal_id', 'session_date', 'opened_by', 'closed_by',
        'opening_cash', 'status', 'opened_at', 'closed_at',
        'or_series_start', 'or_series_end', 'z_reading_id',
    ];

    protected $casts = [
        'session_date' => 'date',
        'opening_cash' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function terminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class);
    }

    public function openedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function closedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function zReading(): BelongsTo
    {
        return $this->belongsTo(ZReading::class, 'z_reading_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'day_session_id');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }
}
