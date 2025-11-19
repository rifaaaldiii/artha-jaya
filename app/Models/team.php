<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class team extends Model
{
    protected $table = 'teams';

    protected $fillable = [
        'nama',
        'status',
        'createdAt',
        'updatedAt',
    ];

    public $timestamps = false;

    protected $casts = [
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    /**
     * Get all petukangs for this team.
     */
    public function petukangs()
    {
        return $this->hasMany(petukang::class, 'team_id');
    }

    /**
     * Get all produksis for this team.
     */
    public function produksis()
    {
        return $this->hasMany(produksi::class, 'team_id');
    }

    /**
     * Get all pelanggans for this team.
     */
    public function pelanggans()
    {
        return $this->hasMany(pelanggan::class, 'team_id');
    }

    protected static function booted(): void
    {
        static::creating(function (team $team): void {
            if (blank($team->createdAt)) {
                $team->createdAt = now();
            }
        });

        static::updating(function (team $team): void {
            $team->updatedAt = now();
        });
    }
}
