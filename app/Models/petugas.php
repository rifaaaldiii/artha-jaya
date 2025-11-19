<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class petugas extends Model
{
    protected $table = 'petugas';

    protected $fillable = [
        'nama',
        'status',
        'kontak',
        'createdAt',
        'updateAt',
    ];

    public $timestamps = false;

    protected $casts = [
        'createdAt' => 'datetime',
        'updateAt' => 'datetime',
    ];

    /**
     * Get all jasas for this petugas.
     */
    public function jasas()
    {
        return $this->hasMany(jasa::class, 'petugas_id');
    }

    protected static function booted(): void
    {
        static::creating(function (petugas $petugas): void {
            if (blank($petugas->createdAt)) {
                $petugas->createdAt = now();
            }
        });

        static::updating(function (petugas $petugas): void {
            $petugas->updateAt = now();
        });
    }
}
