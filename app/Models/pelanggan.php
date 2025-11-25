<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pelanggan extends Model
{
    protected $table = 'pelanggans';

    protected $fillable = [
        'nama',
        'kontak',
        'alamat',
        'createdAt',
        'UpdateAt',
    ];

    public $timestamps = false;

    protected $casts = [
        'createdAt' => 'datetime',
        'UpdateAt' => 'datetime',
    ];

    /**
     * Get the team for this pelanggan.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /**
     * Get all jasas for this pelanggan.
     */
    public function jasas()
    {
        return $this->hasMany(Jasa::class, 'pelanggan_id');
    }

    protected static function booted(): void
    {
        static::creating(function (Pelanggan $pelanggan): void {
            if (blank($pelanggan->createdAt)) {
                $pelanggan->createdAt = now();
            }
        });

        static::updating(function (Pelanggan $pelanggan): void {
            $pelanggan->UpdateAt = now();
        });
    }
}
