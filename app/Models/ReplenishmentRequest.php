<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReplenishmentRequest extends Model
{
    protected $fillable = ['requesting_branch_id', 'product_id', 'quantity_requested', 'status', 'approved_by'];

    protected $casts = ['quantity_requested' => 'decimal:3'];

    public function requestingBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'requesting_branch_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
