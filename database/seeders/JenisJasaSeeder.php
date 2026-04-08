<?php

namespace Database\Seeders;

use App\Models\JenisJasa;
use Illuminate\Database\Seeder;

class JenisJasaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisJasas = [
            [
                'nama' => 'JASA PASANG INSTALASI WH/ST',
                'harga' => 300000,
                'uom' => 'TITIK',
            ],
            [
                'nama' => 'JASA PASANG WATER HEATER (HANYA UNIT)',
                'harga' => 200000,
                'uom' => 'UNIT',
            ],
            [
                'nama' => 'JASA PASANG WATER HEATER (PLUS INSTALASI)',
                'harga' => 500000,
                'uom' => 'PAKET',
            ],
            [
                'nama' => 'JASA PASANG SHOWER SCREEN (HANYA UNIT)',
                'harga' => 600000,
                'uom' => 'SET',
            ],
            [
                'nama' => 'JASA PASANG SHOWER TIANG (HANYA UNIT)',
                'harga' => 200000,
                'uom' => 'SET',
            ],
            [
                'nama' => 'JASA PASANG WASTAFEL CABINET (HANYA UNIT)',
                'harga' => 300000,
                'uom' => 'UNIT',
            ],
            [
                'nama' => 'JASA PASANG WASTAFEL GANTUNG (HANYA UNIT)',
                'harga' => 200000,
                'uom' => 'UNIT',
            ],
            [
                'nama' => 'JASA PASANG URINAL (HANYA UNIT)',
                'harga' => 350000,
                'uom' => 'UNIT',
            ],
            [
                'nama' => 'JASA PASANG CLOSET DUDUK (HANYA UNIT)',
                'harga' => 350000,
                'uom' => 'UNIT',
            ],
            [
                'nama' => 'JASA PASANG POMPA DORONG',
                'harga' => 200000,
                'uom' => 'UNIT',
            ],
            [
                'nama' => 'JASA PASANG POMPA KECIL',
                'harga' => 200000,
                'uom' => 'UNIT',
            ],
            [
                'nama' => 'JASA PASANG POMPA SEMI JET',
                'harga' => 200000,
                'uom' => 'UNIT',
            ],
            [
                'nama' => 'JASA PASANG JET PUMP',
                'harga' => 300000,
                'uom' => 'UNIT',
            ],
            [
                'nama' => 'JASA PASANG POMPA SATELIT 1/2 PK',
                'harga' => 500000,
                'uom' => 'UNIT',
            ],
            [
                'nama' => 'JASA PASANG POMPA SATELIT 3/4 PK',
                'harga' => 600000,
                'uom' => 'UNIT',
            ],
            [
                'nama' => 'JASA PASANG POMPA SATELIT 1 PK',
                'harga' => 700000,
                'uom' => 'UNIT',
            ],
            [
                'nama' => 'JASA PASANG FILTER WFH 10"',
                'harga' => 150000,
                'uom' => 'UNIT',
            ],
            [
                'nama' => 'JASA PASANG FILTER FRP',
                'harga' => 500000,
                'uom' => 'UNIT',
            ],
        ];

        foreach ($jenisJasas as $jenisJasa) {
            JenisJasa::create($jenisJasa);
        }
    }
}
