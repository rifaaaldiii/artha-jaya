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
            // Kategori 1 - Pompa & Filter
            [
                'itemcode' => '9601001021',
                'kategori_id' => 1,
                'nama' => 'JASA PASANG POMPA DORONG',
                'harga' => 200000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001022',
                'kategori_id' => 1,
                'nama' => 'JASA PASANG POMPA KECIL',
                'harga' => 200000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001023',
                'kategori_id' => 1,
                'nama' => 'JASA PASANG POMPA SEMI JET',
                'harga' => 200000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001024',
                'kategori_id' => 1,
                'nama' => 'JASA PASANG JET PUMP',
                'harga' => 300000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001025',
                'kategori_id' => 1,
                'nama' => 'JASA PASANG POMPA SATELIT 1/2 PK',
                'harga' => 500000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001026',
                'kategori_id' => 1,
                'nama' => 'JASA PASANG POMPA SATELIT 3/4 PK',
                'harga' => 600000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001027',
                'kategori_id' => 1,
                'nama' => 'JASA PASANG POMPA SATELIT 1 PK',
                'harga' => 700000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001028',
                'kategori_id' => 1,
                'nama' => 'JASA PASANG FILTER WFH 10"',
                'harga' => 150000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001029',
                'kategori_id' => 1,
                'nama' => 'JASA PASANG FILTER FRP',
                'harga' => 500000,
                'uom' => 'UNIT',
            ],
            
            // Kategori 2 - Water Heater & Saniter
            [
                'itemcode' => '9601001013',
                'kategori_id' => 2,
                'nama' => 'JASA PASANG WATER HEATER (HANYA UNIT)',
                'harga' => 200000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001014',
                'kategori_id' => 2,
                'nama' => 'JASA PASANG WATER HEATER (PLUS INSTALASI)',
                'harga' => 500000,
                'uom' => 'PAKET',
            ],
            [
                'itemcode' => '9601001015',
                'kategori_id' => 2,
                'nama' => 'JASA PASANG SHOWER SCREEN (HANYA UNIT)',
                'harga' => 600000,
                'uom' => 'SET',
            ],
            [
                'itemcode' => '9601001016',
                'kategori_id' => 2,
                'nama' => 'JASA PASANG SHOWER TIANG (HANYA UNIT)',
                'harga' => 200000,
                'uom' => 'SET',
            ],
            [
                'itemcode' => '9601001017',
                'kategori_id' => 2,
                'nama' => 'JASA PASANG WASTAFEL CABINET (HANYA UNIT)',
                'harga' => 300000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001018',
                'kategori_id' => 2,
                'nama' => 'JASA PASANG WASTAFEL GANTUNG (HANYA UNIT)',
                'harga' => 200000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001019',
                'kategori_id' => 2,
                'nama' => 'JASA PASANG URINAL (HANYA UNIT)',
                'harga' => 350000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001020',
                'kategori_id' => 2,
                'nama' => 'JASA PASANG CLOSET DUDUK (HANYA UNIT)',
                'harga' => 350000,
                'uom' => 'UNIT',
            ],
            
            // Kategori 3 - CCTV, Smartlock & Lainnya
            [
                'itemcode' => '9601001030',
                'kategori_id' => 3,
                'nama' => 'JASA SETTING CCTV',
                'harga' => 150000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001031',
                'kategori_id' => 3,
                'nama' => 'JASA SETTING, PASANG & INSTALASI KABEL CCTV',
                'harga' => 350000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001032',
                'kategori_id' => 3,
                'nama' => 'JASA SETTING SMARTLOCK',
                'harga' => 200000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001033',
                'kategori_id' => 3,
                'nama' => 'JASA PASANG & SETTING SMARTLOCK',
                'harga' => 350000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001035',
                'kategori_id' => 3,
                'nama' => 'JASA PASANG PINTU BAJA (SINGLE)',
                'harga' => 300000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001036',
                'kategori_id' => 3,
                'nama' => 'JASA PASANG PINTU BAJA (DOUBLE)',
                'harga' => 500000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001039',
                'kategori_id' => 3,
                'nama' => 'JASA PASANG KRAN MIXER',
                'harga' => 150000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001040',
                'kategori_id' => 3,
                'nama' => 'JASA PASANG & INSTALASI KRAN MIXER',
                'harga' => 250000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '9601001042',
                'kategori_id' => 3,
                'nama' => 'JASA PASANG BATHTUB',
                'harga' => 500000,
                'uom' => 'UNIT',
            ],
            [
                'itemcode' => '1',
                'kategori_id' => 4,
                'nama' => 'JASA PASANG AC SPLIT WALL KAPASITAS 0,5-1PK',
                'harga' => 200000,
                'uom' => 'UNIT',
            ],
        ];

        foreach ($jenisJasas as $jenisJasa) {
            JenisJasa::create($jenisJasa);
        }
    }
}
