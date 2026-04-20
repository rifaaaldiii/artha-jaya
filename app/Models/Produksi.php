<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produksi extends Model
{
    protected $table = 'produksis';

    protected $fillable = [
        'no_produksi',
        'no_ref',
        'branch',
        'status',
        'catatan',
        'progress_images',
        'team_id',
        'pelanggan_id',
        'alamat',
        'jadwal',
        'createdAt',
        'updateAt',
    ];

    public $timestamps = false;

    protected $casts = [
        'createdAt' => 'datetime',
        'updateAt' => 'datetime',
        'jadwal' => 'datetime',
        'progress_images' => 'array',
    ];

    protected $rules = [
        'no_ref' => 'required|string|unique:produksis,no_ref',
    ];

    /**
     * Get the team for this produksi.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /**
     * Get the pelanggan for this produksi.
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    /**
     * Get all items for this produksi.
     */
    public function items(): HasMany
    {
        return $this->hasMany(ProduksiItem::class, 'produksi_id');
    }

    protected static function booted(): void
    {
        static::creating(function (Produksi $produksi): void {
            if (blank($produksi->no_produksi)) {
                $prefix = 'P-';
                $padLength = 5;

                $lastNo = static::query()
                    ->where('no_produksi', 'like', $prefix . '%')
                    ->orderByDesc('id')
                    ->value('no_produksi');

                if ($lastNo) {
                    $num = (int) substr($lastNo, strlen($prefix));
                    $nextNum = $num + 1;
                } else {
                    $nextNum = 1;
                }

                $produksi->no_produksi = $prefix . str_pad($nextNum, $padLength, '0', STR_PAD_LEFT);
            }

            if (blank($produksi->createdAt)) {
                $produksi->createdAt = now();
            }
        });

        static::updating(function (Produksi $produksi): void {
            $produksi->updateAt = now();
        });

        static::created(function (Produksi $produksi): void {
            // Team can now handle multiple produksis, no status update needed
        });

        static::updated(function (Produksi $produksi): void {
            $originalTeamId = $produksi->getOriginal('team_id');
            $newTeamId = $produksi->team_id;

            // No team status updates needed - teams can handle multiple produksis
        });

        static::deleted(function (Produksi $produksi): void {
            // No team status updates needed - teams can handle multiple produksis
        });
    }
}
