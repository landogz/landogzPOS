<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingSyncQueue extends Model
{
    protected $table = 'pending_sync_queue';

    protected $fillable = ['model_type', 'record_id', 'action', 'payload', 'status', 'attempt_count', 'error', 'synced_at'];

    protected $casts = [
        'payload' => 'array',
        'synced_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_SYNCED = 'synced';
    const STATUS_FAILED = 'failed';
}
