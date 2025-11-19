<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class pelanggan extends Model
{
    protected $table = 'pelanggans';

    protected $fillable = [
        'nama',
        'kontak',
        'alamat',
        'team_id',
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
        return $this->belongsTo(team::class, 'team_id');
    }

    /**
     * Get all jasas for this pelanggan.
     */
    public function jasas()
    {
        return $this->hasMany(jasa::class, 'pelanggan_id');
    }

    protected static function booted(): void
    {
        static::creating(function (pelanggan $pelanggan): void {
            if (blank($pelanggan->createdAt)) {
                $pelanggan->createdAt = now();
            }
        });

        static::updating(function (pelanggan $pelanggan): void {
            $pelanggan->UpdateAt = now();
        });
    }
}
