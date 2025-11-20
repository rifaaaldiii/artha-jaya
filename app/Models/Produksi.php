<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class produksi extends Model
{
    protected $table = 'produksis';

    protected $fillable = [
        'no_produksi',
        'nama_produksi',
        'nama_bahan',
        'jumlah',
        'status',
        'catatan',
        'team_id',
        'createdAt',
        'updateAt',
    ];

    public $timestamps = false;

    protected $casts = [
        'createdAt' => 'datetime',
        'updateAt' => 'datetime',
    ];

    /**
     * Get the team for this produksi.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(team::class, 'team_id');
    }

    protected static function booted(): void
    {
        static::creating(function (produksi $produksi): void {
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

        static::updating(function (produksi $produksi): void {
            $produksi->updateAt = now();
        });

        static::created(function (produksi $produksi): void {
            if ($produksi->team_id) {
                team::where('id', $produksi->team_id)->update(['status' => 'busy']);
                petukang::where('team_id', $produksi->team_id)->update(['status' => 'busy']);
            }
        });

        static::updated(function (produksi $produksi): void {
            $originalTeamId = $produksi->getOriginal('team_id');
            $newTeamId = $produksi->team_id;
            $originalStatus = $produksi->getOriginal('status');
            $newStatus = $produksi->status;

            // Validasi: Jika status berubah menjadi 'selesai', update team dan petukang menjadi 'ready'
            if ($originalStatus !== 'selesai' && $newStatus === 'selesai' && $produksi->team_id) {
                team::where('id', $produksi->team_id)->update(['status' => 'ready']);
                petukang::where('team_id', $produksi->team_id)->update(['status' => 'ready']);
            }

            // Handle perubahan team_id
            if ($originalTeamId !== $newTeamId) {
                if ($newTeamId) {
                    team::where('id', $newTeamId)->update(['status' => 'busy']);
                    petukang::where('team_id', $newTeamId)->update(['status' => 'busy']);
                }

                if ($originalTeamId) {
                    $hasActiveProduksi = static::query()
                        ->where('team_id', $originalTeamId)
                        ->where('id', '!=', $produksi->id)
                        ->where('status', '!=', 'selesai')
                        ->exists();

                    if (!$hasActiveProduksi) {
                        team::where('id', $originalTeamId)->update(['status' => 'ready']);
                        petukang::where('team_id', $originalTeamId)->update(['status' => 'ready']);
                    }
                }
            }
        });

        static::deleted(function (produksi $produksi): void {
            if ($produksi->team_id) {
                $hasActiveProduksi = static::query()
                    ->where('team_id', $produksi->team_id)
                    ->where('status', '!=', 'selesai')
                    ->exists();

                if (!$hasActiveProduksi) {
                    team::where('id', $produksi->team_id)->update(['status' => 'ready']);
                    petukang::where('team_id', $produksi->team_id)->update(['status' => 'ready']);
                }
            }
        });
    }
}
