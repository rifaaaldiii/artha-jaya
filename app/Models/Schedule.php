<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';

    protected $fillable = [
        'jadwal_petugas',
        'branch',
        'alamat',
        'keterangan',
        'catatan',
        'pic',
        'pekerja',
        'status',
    ];

    protected $casts = [
        'jadwal_petugas' => 'datetime',
    ];

    /**
     * Snapshot schedule data from a scheduled Jasa.
     * Independent of jasas — create/update/delete here does not affect other tables.
     */
    public static function syncFromJasa(Jasa $jasa): self
    {
        $jasa->loadMissing(['pelanggan', 'petugasMany', 'petugas', 'items']);

        $pekerja = $jasa->petugasMany->pluck('nama')->filter()->join(', ');
        if (blank($pekerja) && $jasa->petugas) {
            $pekerja = $jasa->petugas->nama;
        }

        $pic = User::query()
            ->where('role', 'kepala_lapangan')
            ->pluck('name')
            ->filter()
            ->join(', ');

        $keterangan = $jasa->items
            ->pluck('jenis_layanan')
            ->filter()
            ->unique()
            ->join(', ');

        return static::create([
            'jadwal_petugas' => $jasa->jadwal_petugas,
            'branch' => $jasa->branch,
            'alamat' => $jasa->alamat ?? $jasa->pelanggan?->alamat,
            'keterangan' => $keterangan ?: null,
            'catatan' => $jasa->catatan,
            'pic' => $pic ?: null,
            'pekerja' => $pekerja ?: null,
            'status' => 'Terjadwal',
        ]);
    }
}
