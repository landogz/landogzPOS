<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOut extends Model
{
    protected $table = 'stock_outs';

    protected $fillable = ['branch_id', 'product_id', 'product_batch_id', 'quantity', 'reason', 'recorded_by', 'recorded_at'];

    protected $casts = ['quantity' => 'decimal:3', 'recorded_at' => 'datetime'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
