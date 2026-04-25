<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jasa extends Model
{
    protected $table = 'jasas';

    protected $fillable = [
        'no_jasa',
        'no_ref',
        'branch',
        'jadwal',
        'jadwal_petugas',
        'catatan',
        'progress_images',
        'completion_images',
        'completion_notes',
        'petugas_id',
        'pelanggan_id',
        'alamat',
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
        'progress_images' => 'array',
        'completion_images' => 'array',
    ];

    protected $rules = [
        'no_ref' => 'required|string|unique:jasas,no_ref',
    ];

    /**
     * Get the petugas for this jasa (legacy single petugas relation).
     */
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }

    /**
     * Get all petugas for this jasa (many-to-many relation).
     */
    public function petugasMany(): BelongsToMany
    {
        return $this->belongsToMany(Petugas::class, 'jasa_petugas', 'jasa_id', 'petugas_id');
    }

    /**
     * Get the pelanggan for this jasa.
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    /**
     * Get alamat from pelanggan if alamat is null.
     */
    public function getAlamatPelangganAttribute(): ?string
    {
        return $this->pelanggan?->alamat;
    }

    /**
     * Get all items for this jasa.
     */
    public function items(): HasMany
    {
        return $this->hasMany(JasaItem::class, 'jasa_id');
    }

    protected static function booted(): void
    {
        static::creating(function (Jasa $jasa): void {
            \Log::info('Jasa creating event triggered', ['no_jasa' => $jasa->no_jasa]);
            
            if (blank($jasa->no_jasa)) {
                \Log::info('Generating new Jasa number');
                // Format: JSA/DDMMYYYY/0001
                $prefix = 'JSA';
                $date = now()->format('dmy'); // DDMMYYYY
                $fullPrefix = $prefix . '/' . $date . '/';
                $padLength = 4;

                $lastNo = static::query()
                    ->where('no_jasa', 'like', $fullPrefix . '%')
                    ->orderByDesc('id')
                    ->value('no_jasa');

                if ($lastNo) {
                    // Extract sequence number
                    $parts = explode('/', $lastNo);
                    $num = intval(end($parts));
                    $nextNum = $num + 1;
                } else {
                    $nextNum = 1;
                }

                $jasa->no_jasa = $fullPrefix . str_pad($nextNum, $padLength, '0', STR_PAD_LEFT);
                \Log::info('Generated Jasa number', ['no_jasa' => $jasa->no_jasa]);
            }

            if (blank($jasa->createdAt)) {
                $jasa->createdAt = now();
            }
        });

        static::updating(function (Jasa $jasa): void {
            $jasa->updateAt = now();
        });

        static::updated(function (Jasa $jasa): void {
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
                            Petugas::where('id', $petugasId)->update(['status' => 'ready']);
                        }
                    }
                }
            }

            // Jika status berubah menjadi 'terjadwal', update petugas menjadi 'busy'
            if ($originalStatus !== 'terjadwal' && $newStatus === 'terjadwal') {
                $petugasIds = $jasa->petugasMany()->pluck('petugas_id')->toArray();
                
                if (!empty($petugasIds)) {
                    Petugas::whereIn('id', $petugasIds)->update(['status' => 'busy']);
                }
            }
        });

        static::deleting(function (Jasa $jasa): void {
            // IMPORTANT: Use 'deleting' event (before delete) because pivot table has cascade delete
            // If we use 'deleted' event, the pivot records are already gone
            $petugasIds = $jasa->petugasMany()->pluck('petugas_id')->toArray();
            
            // Store in variable for use in deleted event
            $jasa->setAttribute('_petugas_ids_before_delete', $petugasIds);
        });

        static::deleted(function (Jasa $jasa): void {
            // Get petugas IDs that were stored before delete
            $petugasIds = $jasa->getAttribute('_petugas_ids_before_delete') ?? [];
            
            if (!empty($petugasIds)) {
                foreach ($petugasIds as $petugasId) {
                    // Cek apakah petugas masih memiliki jasa aktif lainnya
                    $hasActiveJasa = static::query()
                        ->whereHas('petugasMany', function ($query) use ($petugasId) {
                            $query->where('petugas_id', $petugasId);
                        })
                        ->where('status', '!=', 'selesai')
                        ->exists();

                    // Jika tidak ada jasa aktif lain, update status menjadi 'ready'
                    if (!$hasActiveJasa) {
                        Petugas::where('id', $petugasId)->update([
                            'status' => 'ready',
                            'updateAt' => now(),
                        ]);
                    }
                }
            }
        });
    }

    /**
     * Get all update tokens for this jasa.
     */
    public function updateTokens(): HasMany
    {
        return $this->hasMany(JasaUpdateToken::class);
    }

    /**
     * Generate a new update token for this jasa.
     */
    public function generateUpdateToken(): string
    {
        \Log::info('Generating update token for jasa', [
            'jasa_id' => $this->id,
            'no_jasa' => $this->no_jasa,
        ]);
        
        $token = hash('sha256', $this->id . now()->timestamp . config('app.key'));
        
        try {
            JasaUpdateToken::create([
                'jasa_id' => $this->id,
                'token' => $token,
                'target_status' => 'selesai dikerjakan',
                'expires_at' => now()->addDays(7),
            ]);
            
            \Log::info('Update token created successfully', [
                'jasa_id' => $this->id,
                'token' => substr($token, 0, 20) . '...',
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create update token', [
                'jasa_id' => $this->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
        
        return $token;
    }
}
