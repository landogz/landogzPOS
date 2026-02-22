<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'name', 'address', 'tin',
        'bir_series_start', 'bir_series_end', 'current_or_number',
    ];

    protected $casts = ['current_or_number' => 'integer'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'branch_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'branch_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'branch_id');
    }

    public function terminals(): HasMany
    {
        return $this->hasMany(Terminal::class, 'branch_id');
    }
}
