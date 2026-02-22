<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransfer extends Model
{
    protected $table = 'stock_transfers';

    protected $fillable = [
        'from_branch_id', 'to_branch_id', 'product_id', 'product_batch_id',
        'quantity', 'status', 'transferred_at',
    ];

    protected $casts = ['quantity' => 'decimal:3', 'transferred_at' => 'datetime'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function fromBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    public function toBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }
}
