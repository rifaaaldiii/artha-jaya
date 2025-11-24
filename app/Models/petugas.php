<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static \Database\Factories\PetugasFactory factory($count = null, $state = [])
 */
class petugas extends Model
{
    /** @use HasFactory<\Database\Factories\PetugasFactory> */
    use HasFactory;
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
     * Get all jasas for this petugas (legacy single relation).
     */
    public function jasas()
    {
        return $this->hasMany(jasa::class, 'petugas_id');
    }

    /**
     * Get all jasas for this petugas (many-to-many relation).
     */
    public function jasasMany()
    {
        return $this->belongsToMany(jasa::class, 'jasa_petugas', 'petugas_id', 'jasa_id');
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
