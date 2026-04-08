<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JasaItem extends Model
{
    protected $table = 'jasa_items';

    protected $fillable = [
        'jasa_id',
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
