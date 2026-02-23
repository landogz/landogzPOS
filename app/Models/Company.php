<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'tin', 'bir_accreditation', 'address', 'contact', 'logo', 'is_active', 'is_vat'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_vat'    => 'boolean',
    ];

    protected $appends = ['logo_url'];

    public function getLogoUrlAttribute(): ?string
    {
        if (empty($this->logo)) {
            return null;
        }
        return asset('storage/' . $this->logo);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function transactions(): HasManyThrough
    {
        return $this->hasManyThrough(Transaction::class, Branch::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
