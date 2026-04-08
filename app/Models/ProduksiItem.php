<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProduksiItem extends Model
{
    protected $table = 'produksi_items';

    protected $fillable = [
        'produksi_id',
        'nama_produksi',
        'nama_bahan',
        'jumlah',
        'harga',
        'createdAt',
        'updateAt',
    ];

    public $timestamps = false;

    protected $casts = [
        'createdAt' => 'datetime',
        'updateAt' => 'datetime',
    ];

    /**
     * Get the produksi that owns this item.
     */
    public function produksi(): BelongsTo
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }

    protected static function booted(): void
    {
        static::creating(function (ProduksiItem $item): void {
            if (blank($item->createdAt)) {
                $item->createdAt = now();
            }
        });

        static::updating(function (ProduksiItem $item): void {
            $item->updateAt = now();
        });
    }
}
