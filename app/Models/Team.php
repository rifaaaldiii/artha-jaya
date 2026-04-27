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
        'order',
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
     * Get active (non-completed) produksis count for this team.
     */
    public function getActiveProduksisCount(): int
    {
        return $this->produksis()
            ->where('status', '!=', 'selesai')
            ->count();
    }

    /**
     * Check if team has available capacity (less than 3 active produksis).
     */
    public function hasAvailableCapacity(): bool
    {
        return $this->getActiveProduksisCount() < 3;
    }

    /**
     * Update the order field based on active produksis count.
     * Order = number of active produksis (non-selesai status).
     * Reset to 0 when all produksis are completed.
     */
    public function updateOrder(): void
    {
        $activeCount = $this->getActiveProduksisCount();
        
        // Only update if the value has changed
        if ($this->order !== $activeCount) {
            $this->order = $activeCount;
            // Use saveQuietly to avoid triggering observer again
            $this->saveQuietly();
        }
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
