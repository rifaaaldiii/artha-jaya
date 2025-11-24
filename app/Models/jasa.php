<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class jasa extends Model
{
    protected $table = 'jasas';

    protected $fillable = [
        'no_jasa',
        'no_ref',
        'jenis_layanan',
        'jadwal',
        'jadwal_petugas',
        'catatan',
        'petugas_id',
        'pelanggan_id',
        'status',
        'createdAt',
        'updateAt',
    ];

    public $timestamps = false;

    protected $casts = [
        'jadwal' => 'datetime',
        'jadwal_petugas' => 'datetime',
        'createdAt' => 'datetime',
        'updateAt' => 'datetime',
    ];

    /**
     * Get the petugas for this jasa (legacy single petugas relation).
     */
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(petugas::class, 'petugas_id');
    }

    /**
     * Get all petugas for this jasa (many-to-many relation).
     */
    public function petugasMany(): BelongsToMany
    {
        return $this->belongsToMany(petugas::class, 'jasa_petugas', 'jasa_id', 'petugas_id');
    }

    /**
     * Get the pelanggan for this jasa.
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(pelanggan::class, 'pelanggan_id');
    }

    protected static function booted(): void
    {
        static::creating(function (jasa $jasa): void {
            if (blank($jasa->no_jasa)) {
                $prefix = 'J-';
                $padLength = 5;

                $lastNo = static::query()
                    ->where('no_jasa', 'like', $prefix . '%')
                    ->orderByDesc('id')
                    ->value('no_jasa');

                if ($lastNo) {
                    $num = (int) substr($lastNo, strlen($prefix));
                    $nextNum = $num + 1;
                } else {
                    $nextNum = 1;
                }

                $jasa->no_jasa = $prefix . str_pad($nextNum, $padLength, '0', STR_PAD_LEFT);
            }

            if (blank($jasa->createdAt)) {
                $jasa->createdAt = now();
            }
        });

        static::updating(function (jasa $jasa): void {
            $jasa->updateAt = now();
        });

        static::updated(function (jasa $jasa): void {
            $originalStatus = $jasa->getOriginal('status');
            $newStatus = $jasa->status;

            // Jika status berubah menjadi 'selesai', update semua petugas terkait menjadi 'ready'
            if ($originalStatus !== 'selesai' && $newStatus === 'selesai') {
                $petugasIds = $jasa->petugasMany()->pluck('petugas_id')->toArray();
                
                if (!empty($petugasIds)) {
                    foreach ($petugasIds as $petugasId) {
                        // Cek apakah petugas masih memiliki jasa aktif selain yang ini
                        $hasActiveJasa = static::query()
                            ->whereHas('petugasMany', function ($query) use ($petugasId) {
                                $query->where('petugas_id', $petugasId);
                            })
                            ->where('id', '!=', $jasa->id)
                            ->where('status', '!=', 'selesai')
                            ->exists();

                        if (!$hasActiveJasa) {
                            petugas::where('id', $petugasId)->update(['status' => 'ready']);
                        }
                    }
                }
            }

            // Jika status berubah menjadi 'terjadwal', update petugas menjadi 'busy'
            if ($originalStatus !== 'terjadwal' && $newStatus === 'terjadwal') {
                $petugasIds = $jasa->petugasMany()->pluck('petugas_id')->toArray();
                
                if (!empty($petugasIds)) {
                    petugas::whereIn('id', $petugasIds)->update(['status' => 'busy']);
                }
            }
        });

        static::deleted(function (jasa $jasa): void {
            // Saat jasa dihapus, update semua petugas terkait menjadi 'ready'
            $petugasIds = $jasa->petugasMany()->pluck('petugas_id')->toArray();
            
            if (!empty($petugasIds)) {
                foreach ($petugasIds as $petugasId) {
                    // Cek apakah petugas masih memiliki jasa aktif selain yang dihapus
                    $hasActiveJasa = static::query()
                        ->whereHas('petugasMany', function ($query) use ($petugasId) {
                            $query->where('petugas_id', $petugasId);
                        })
                        ->where('status', '!=', 'selesai')
                        ->exists();

                    if (!$hasActiveJasa) {
                        petugas::where('id', $petugasId)->update(['status' => 'ready']);
                    }
                }
            }
        });
    }
}
