<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JasaItem extends Model
{
    protected $table = 'jasa_items';

    protected $fillable = [
        'jasa_id',
        'kategori_jasa_item_id',
        'jenis_layanan',
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
     * Get the kategori for this jasa item.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriJasaItem::class, 'kategori_jasa_item_id');
    }

    /**
     * Get the jasa that owns this item.
     */
    public function jasa(): BelongsTo
    {
        return $this->belongsTo(Jasa::class, 'jasa_id');
    }

    protected static function booted(): void
    {
        static::creating(function (JasaItem $item): void {
            if (blank($item->createdAt)) {
                $item->createdAt = now();
            }
        });

        static::updating(function (JasaItem $item): void {
            $item->updateAt = now();
        });
    }
}
