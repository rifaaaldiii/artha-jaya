<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class ProduksiItem extends Model
{
    protected $table = 'produksi_items';

    protected $fillable = [
        'produksi_id',
        'nama_produksi',
        'nama_bahan',
        'ukuran',
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
     * Temporary attributes for ukuran calculation (not persisted to DB)
     */
    protected $temporaryAttributes = [];

    /**
     * Set temporary attribute value
     */
    public function setTemporaryAttribute($key, $value): void
    {
        $this->temporaryAttributes[$key] = $value;
    }

    /**
     * Get temporary attribute value
     */
    public function getTemporaryAttribute($key)
    {
        return $this->temporaryAttributes[$key] ?? null;
    }

    /**
     * Get the produksi that owns this item.
     */
    public function produksi(): BelongsTo
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }

    protected static function booted(): void
    {
        static::saving(function (ProduksiItem $item): void {
            // Calculate ukuran from ukuran_panjang and ukuran_lebar if they exist in attributes
            $panjang = $item->getAttribute('ukuran_panjang');
            $lebar = $item->getAttribute('ukuran_lebar');
            
            if ($panjang && $lebar && empty($item->ukuran)) {
                $item->ukuran = "{$panjang} x {$lebar}";
                Log::info('=== UKURAN CALCULATED IN SAVING EVENT ===', [
                    'panjang' => $panjang,
                    'lebar' => $lebar,
                    'ukuran' => $item->ukuran,
                ]);
            }
            
            // Log before saving
            Log::info('=== PRODUKSI ITEM SAVING ===', [
                'nama_produksi' => $item->nama_produksi,
                'nama_bahan' => $item->nama_bahan,
                'ukuran' => $item->ukuran,
                'ukuran_type' => gettype($item->ukuran),
                'ukuran_empty' => empty($item->ukuran),
                'jumlah' => $item->jumlah,
                'harga' => $item->harga,
                'all_attributes' => $item->getAttributes(),
            ]);
        });
        
        static::creating(function (ProduksiItem $item): void {
            if (blank($item->createdAt)) {
                $item->createdAt = now();
            }
        });

        static::created(function (ProduksiItem $item): void {
            Log::info('=== PRODUKSI ITEM CREATED (SAVED TO DB) ===', [
                'id' => $item->id,
                'ukuran_in_db' => $item->ukuran,
                'all_attributes' => $item->getAttributes(),
            ]);
        });

        static::updating(function (ProduksiItem $item): void {
            Log::info('=== PRODUKSI ITEM UPDATING ===', [
                'id' => $item->id,
                'ukuran' => $item->ukuran,
                'ukuran_type' => gettype($item->ukuran),
                'dirty_fields' => $item->getDirty(),
                'all_attributes' => $item->getAttributes(),
            ]);
            
            $item->updateAt = now();
        });

        static::updated(function (ProduksiItem $item): void {
            Log::info('=== PRODUKSI ITEM UPDATED (SAVED TO DB) ===', [
                'id' => $item->id,
                'ukuran_in_db' => $item->ukuran,
                'all_attributes' => $item->getAttributes(),
            ]);
        });
    }
}
