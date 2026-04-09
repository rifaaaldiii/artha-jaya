<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Database\Factories\TeamFactory factory($count = null, $state = [])
 */
class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;
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
        return $this->hasMany(Petukang::class, 'team_id');
    }

    /**
     * Get all produksis for this team.
     */
    public function produksis()
    {
        return $this->hasMany(Produksi::class, 'team_id');
    }

    /**
     * Get all pelanggans for this team.
     */
    public function pelanggans()
    {
        return $this->hasMany(Pelanggan::class, 'team_id');
    }

    protected static function booted(): void
    {
        static::creating(function (Team $team): void {
            if (blank($team->createdAt)) {
                $team->createdAt = now();
            }
        });

        static::updating(function (Team $team): void {
            $team->updatedAt = now();
        });
    }
}
