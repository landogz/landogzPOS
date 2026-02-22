<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfficialReceipt extends Model
{
    protected $table = 'official_receipts';

    protected $fillable = [
        'transaction_id', 'or_number', 'tin', 'bir_accreditation',
        'vatable_sales', 'vat_amount', 'vat_exempt', 'issued_at',
    ];

    protected $casts = [
        'vatable_sales' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'vat_exempt' => 'decimal:2',
        'issued_at' => 'datetime',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
