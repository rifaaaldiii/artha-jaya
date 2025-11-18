<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produksi extends Model
{
    protected $table = 'produksis';

    protected $primaryKey = 'id';

    protected $fillable = [
        'no_produksi',
        'nama_produksi',
        'jumlah',
        'petukang_id',
        'status',
        'catatan',
        'createdAt',
        'updateAt',
    ];

    public $timestamps = false;

    protected $casts = [
        'createdAt' => 'datetime',
        'updateAt' => 'datetime',
    ];

    /**
     * Get the petukang (user) assigned to the produksi.
     */
    public function petukang(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petukang_id');
    }

    protected static function booted(): void
    {
        static::creating(function (Produksi $produksi): void {
            // Auto-generate running "no_produksi" if not provided
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

            // Set createdAt on first create if not already set
            if (blank($produksi->createdAt)) {
                $produksi->createdAt = now();
            }
        });

        static::updating(function (Produksi $produksi): void {
            // Set updateAt every time the record is updated
            $produksi->updateAt = now();
        });
    }
}