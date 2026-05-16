<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accessori extends Model
{
    protected $table = 'accessories';

    protected $fillable = [
        'itemcode',
        'nama',
        'harga',
        'uom',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];
}
