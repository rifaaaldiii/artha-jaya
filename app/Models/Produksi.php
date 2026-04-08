<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produksi extends Model
{
    protected $table = 'produksis';

    protected $fillable = [
        'no_produksi',
        'no_ref',
        'branch',
        'status',
        'catatan',
        'progress_images',
        'team_id',
        'createdAt',
        'updateAt',
    ];

    public $timestamps = false;

    protected $casts = [
        'createdAt' => 'datetime',
        'updateAt' => 'datetime',
        'progress_images' => 'array',
    ];

    /**
     * Get the team for this produksi.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /**
     * Get all items for this produksi.
     */
    public function items(): HasMany
    {
        return $this->hasMany(ProduksiItem::class, 'produksi_id');
    }

    protected static function booted(): void
    {
        static::creating(function (Produksi $produksi): void {
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

            if (blank($produksi->createdAt)) {
                $produksi->createdAt = now();
            }
        });

        static::updating(function (Produksi $produksi): void {
            $produksi->updateAt = now();
        });

        static::created(function (Produksi $produksi): void {
            if ($produksi->team_id) {
                Team::where('id', $produksi->team_id)->update(['status' => 'busy']);
                Petukang::where('team_id', $produksi->team_id)->update(['status' => 'busy']);
            }
        });

        static::updated(function (Produksi $produksi): void {
            $originalTeamId = $produksi->getOriginal('team_id');
            $newTeamId = $produksi->team_id;
            $originalStatus = $produksi->getOriginal('status');
            $newStatus = $produksi->status;

            // Validasi: Jika status berubah menjadi 'selesai', update team dan petukang menjadi 'ready'
            if ($originalStatus !== 'selesai' && $newStatus === 'selesai' && $produksi->team_id) {
                Team::where('id', $produksi->team_id)->update(['status' => 'ready']);
                Petukang::where('team_id', $produksi->team_id)->update(['status' => 'ready']);
            }

            // Handle perubahan team_id
            if ($originalTeamId !== $newTeamId) {
                if ($newTeamId) {
                    Team::where('id', $newTeamId)->update(['status' => 'busy']);
                    Petukang::where('team_id', $newTeamId)->update(['status' => 'busy']);
                }

                if ($originalTeamId) {
                    $hasActiveProduksi = static::query()
                        ->where('team_id', $originalTeamId)
                        ->where('id', '!=', $produksi->id)
                        ->where('status', '!=', 'selesai')
                        ->exists();

                    if (!$hasActiveProduksi) {
                        Team::where('id', $originalTeamId)->update(['status' => 'ready']);
                        Petukang::where('team_id', $originalTeamId)->update(['status' => 'ready']);
                    }
                }
            }
        });

        static::deleted(function (Produksi $produksi): void {
            if ($produksi->team_id) {
                $hasActiveProduksi = static::query()
                    ->where('team_id', $produksi->team_id)
                    ->where('status', '!=', 'selesai')
                    ->exists();

                if (!$hasActiveProduksi) {
                    Team::where('id', $produksi->team_id)->update(['status' => 'ready']);
                    Petukang::where('team_id', $produksi->team_id)->update(['status' => 'ready']);
                }
            }
        });
    }
}
