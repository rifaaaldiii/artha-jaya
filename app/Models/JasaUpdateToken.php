<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JasaUpdateToken extends Model
{
    protected $table = 'jasa_update_tokens';

    protected $fillable = [
        'jasa_id',
        'token',
        'target_status',
        'is_used',
        'used_at',
        'used_by_ip',
        'used_by_device',
        'expires_at',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the jasa that owns this token.
     */
    public function jasa(): BelongsTo
    {
        return $this->belongsTo(Jasa::class);
    }

    /**
     * Check if token is expired.
     */
    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }

    /**
     * Check if token is valid (not used and not expired).
     */
    public function isValid(): bool
    {
        return !$this->is_used && !$this->isExpired();
    }
}
