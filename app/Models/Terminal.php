<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Terminal extends Model
{
    /** Prefix label for terminal API keys; first 12 chars of key used for DB lookup */
    public const API_KEY_PREFIX_LABEL = 'poskey_';

    protected $fillable = [
        'branch_id',
        'code',
        'name',
        'min',
        'sn',
        'tin',
        'is_active',
        'api_key_prefix',
        'api_key_hash',
        'z_counter',
        'accumulated_sales',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'api_key_last_used_at' => 'datetime',
    ];

    protected $hidden = [
        'api_key_hash',
    ];

    /**
     * Check if the given plain key matches this terminal's stored hash.
     */
    public function checkApiKey(string $plainKey): bool
    {
        if (empty($this->api_key_hash)) {
            return false;
        }
        return Hash::check($plainKey, $this->api_key_hash);
    }

    /**
     * Generate a new API key for this terminal. Stores hash and prefix; returns the plain key ONCE.
     * Caller must show the key to the user (e.g. for .env) — it cannot be retrieved later.
     */
    public function generateApiKey(): string
    {
        $prefix = self::API_KEY_PREFIX_LABEL . Str::lower(Str::random(5));
        $secret = Str::random(24);
        $plainKey = $prefix . $secret;
        $this->api_key_prefix = $prefix;
        $this->api_key_hash = Hash::make($plainKey);
        $this->api_key_last_used_at = null;
        $this->save();
        return $plainKey;
    }

    /**
     * Revoke the terminal's API key. Key will no longer authenticate.
     */
    public function revokeApiKey(): void
    {
        $this->api_key_prefix = null;
        $this->api_key_hash = null;
        $this->api_key_last_used_at = null;
        $this->save();
    }

    public function hasApiKey(): bool
    {
        return ! empty($this->api_key_prefix) && ! empty($this->api_key_hash);
    }

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
