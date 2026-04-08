<?php

namespace Database\Seeders;

use App\Models\JenisProduksi;
use Illuminate\Database\Seeder;

class JenisProduksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisProduksis = [
            [
                'nama' => 'JASA POTONG PLINT 60',
                'harga' => 3000,
                'uom' => 'KPG',
            ],
            [
                'nama' => 'JASA POTONG PLINT 80',
                'harga' => 5000,
                'uom' => 'KPG',
            ],
            [
                'nama' => 'JASA POTONG PLINT 120',
                'harga' => 10000,
                'uom' => 'KPG',
            ],
            [
                'nama' => 'JASA STEPNOSING 60',
                'harga' => 12000,
                'uom' => 'KPG',
            ],
            [
                'nama' => 'JASA STEPNOSING 80',
                'harga' => 18000,
                'uom' => 'KPG',
            ],
            [
                'nama' => 'JASA STEPNOSING 120',
                'harga' => 30000,
                'uom' => 'KPG',
            ],
            [
                'nama' => 'JASA BULNOSE/BEVEL 60',
                'harga' => 10000,
                'uom' => 'KPG',
            ],
            [
                'nama' => 'JASA BULNOSE/BEVEL 80',
                'harga' => 15000,
                'uom' => 'KPG',
            ],
            [
                'nama' => 'JASA BULNOSE/BEVEL 120',
                'harga' => 20000,
                'uom' => 'KPG',
            ],
        ];

        foreach ($jenisProduksis as $jenisProduksi) {
            JenisProduksi::create($jenisProduksi);
        }
    }
}
