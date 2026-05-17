<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Accessori extends Model
{
    protected $table = 'accessories';

    protected $fillable = [
        'itemcode',
        'nama',
        'harga',
        'uom',
        'jenis_jasa_id',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    /**
     * Get the jenis jasa that owns this accessory.
     */
    public function jenisJasa(): BelongsTo
    {
        return $this->belongsTo(JenisJasa::class, 'jenis_jasa_id');
    }
}
