<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriJasaItem extends Model
{
    protected $table = 'kategori_jasa_items';

    protected $fillable = [
        'nama',
        'deskripsi',
        'createdAt',
        'updateAt',
    ];

    public $timestamps = false;

    protected $casts = [
        'createdAt' => 'datetime',
        'updateAt' => 'datetime',
    ];

    /**
     * Get all jasa items for this category.
     */
    public function jasaItems(): HasMany
    {
        return $this->hasMany(JasaItem::class, 'kategori_jasa_item_id');
    }

    /**
     * Get all jenis jasa for this category.
     */
    public function jenisJasas(): HasMany
    {
        return $this->hasMany(JenisJasa::class, 'kategori_id');
    }

    protected static function booted(): void
    {
        static::creating(function (KategoriJasaItem $kategori): void {
            if (blank($kategori->createdAt)) {
                $kategori->createdAt = now();
            }
        });

        static::updating(function (KategoriJasaItem $kategori): void {
            $kategori->updateAt = now();
        });
    }
}
