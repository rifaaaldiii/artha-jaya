<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class jasa extends Model
{
    protected $table = 'jasas';

    protected $fillable = [
        'no_ref',
        'jenis_layanan',
        'jadwal',
        'catatan',
        'petugas_id',
        'pelanggan_id',
        'status',
        'createdAt',
        'updateAt',
    ];

    public $timestamps = false;

    protected $casts = [
        'jadwal' => 'datetime',
        'createdAt' => 'datetime',
        'updateAt' => 'datetime',
    ];

    /**
     * Get the petugas for this jasa.
     */
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(petugas::class, 'petugas_id');
    }

    /**
     * Get the pelanggan for this jasa.
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(pelanggan::class, 'pelanggan_id');
    }

    protected static function booted(): void
    {
        static::creating(function (jasa $jasa): void {
            if (blank($jasa->createdAt)) {
                $jasa->createdAt = now();
            }
        });

        static::updating(function (jasa $jasa): void {
            $jasa->updateAt = now();
        });
    }
}
