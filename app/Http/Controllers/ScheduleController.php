<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use App\Models\Jasa;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $produksis = Produksi::with(['pelanggan', 'team'])->get()->map(function($p) {
            return [
                'type' => 'produksi',
                'id' => $p->id,
                'no_ref' => $p->no_ref,
                'jadwal' => $p->jadwal ? $p->jadwal->format('Y-m-d') : null,
                'status' => $p->status,
                'alamat' => $p->alamat,
                'catatan' => $p->catatan,
                'customer' => $p->pelanggan?->nama ?? '-',
                'workers' => $p->team?->nama ?? ($p->branch ?? '-'),
            ];
        });

        $jasas = Jasa::with(['pelanggan', 'petugas', 'petugasMany'])->get()->map(function($j) {
            return [
                'type' => 'jasa',
                'id' => $j->id,
                'no_ref' => $j->no_ref,
                'jadwal' => $j->jadwal_petugas ? $j->jadwal_petugas->format('Y-m-d') : null,
                'status' => $j->status,
                'alamat' => $j->alamat,
                'catatan' => $j->catatan,
                'customer' => $j->pelanggan?->nama ?? '-',
                'workers' => $j->petugasMany->pluck('nama')->join(', ') ?: ($j->petugas?->nama ?? '-'),
            ];
        });

        $schedules = $produksis->concat($jasas)->filter(function($s) {
            return $s['jadwal'] !== null;
        })->sortBy('jadwal');

        return view('public.schedule', compact('schedules'));
    }
}