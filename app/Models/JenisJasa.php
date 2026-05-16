<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JenisJasa extends Model
{
    use HasFactory;

    protected $fillable = [
        'itemcode',
        'kategori_id',
        'nama',
        'harga',
        'uom',
    ];

    /**
     * Get the kategori that owns this jenis jasa.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriJasaItem::class, 'kategori_id');
    }
}

