<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class XReading extends Model
{
    protected $table = 'x_readings';

    protected $fillable = [
        'branch_id', 'cashier_id', 'terminal_id', 'shift',
        'or_series_start', 'or_series_end', 'sales_batch_no',
        'total_transactions', 'void_transactions', 'void_trans_amount', 'returned_transactions', 'items_sold',
        'gross_sales', 'total_discounts', 'total_returns', 'net_sales', 'price_quotes',
        'vatable_sales', 'vat_amount', 'vat_exempt', 'zero_rated',
        'sc_discount', 'sc_vat', 'pwd_discount', 'pwd_vat', 'promo_discount',
        'cash_total', 'card_total', 'ewallet_total', 'hmo_total', 'split_total',
        'change_fund', 'pull_outs', 'amount_submitted', 'amount_over',
        'period_from', 'period_to', 'printed_at', 'administrator_name', 'cash_count',
    ];

    protected $casts = [
        'gross_sales' => 'decimal:2',
        'total_discounts' => 'decimal:2',
        'total_returns' => 'decimal:2',
        'net_sales' => 'decimal:2',
        'vatable_sales' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'vat_exempt' => 'decimal:2',
        'zero_rated' => 'decimal:2',
        'sc_discount' => 'decimal:2',
        'sc_vat' => 'decimal:2',
        'pwd_discount' => 'decimal:2',
        'pwd_vat' => 'decimal:2',
        'promo_discount' => 'decimal:2',
        'cash_total' => 'decimal:2',
        'card_total' => 'decimal:2',
        'ewallet_total' => 'decimal:2',
        'hmo_total' => 'decimal:2',
        'split_total' => 'decimal:2',
        'void_trans_amount' => 'decimal:2',
        'price_quotes' => 'decimal:2',
        'change_fund' => 'decimal:2',
        'pull_outs' => 'decimal:2',
        'amount_submitted' => 'decimal:2',
        'amount_over' => 'decimal:2',
        'period_from' => 'datetime',
        'period_to' => 'datetime',
        'printed_at' => 'datetime',
        'cash_count' => 'array',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function terminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class);
    }
}
