<?php

namespace Database\Seeders;

use App\Models\Accessori;
use Illuminate\Database\Seeder;

class AccessoriSeeder extends Seeder
{
    public function run(): void
    {
        $accessories = [
            [
                'id' => 1,
                'itemcode' => '1',
                'nama' => 'PIPA AC 1/4" x 3/8" M',
                'harga' => 100000.00,
                'uom' => 'meter',
                'jenis_jasa_id' => '27',
            ],
            [
                'id' => 2,
                'itemcode' => '2',
                'nama' => 'BRACKET AC OUTDOR',
                'harga' => 50000.00,
                'uom' => 'set',
                'jenis_jasa_id' => '27',
            ],
            [
                'id' => 3,
                'itemcode' => '3',
                'nama' => 'KABEL NYM 3 x 1,5 M',
                'harga' => 20000.00,
                'uom' => 'meter',
                'jenis_jasa_id' => '27',
            ],
            [
                'id' => 4,
                'itemcode' => '4',
                'nama' => 'SELANG FLEXIBLE ( PEMBUANGAN ) 20mm',
                'harga' => 10000.00,
                'uom' => 'meter',
                'jenis_jasa_id' => '27',
            ],
            [
                'id' => 5,
                'itemcode' => '5',
                'nama' => 'PIPA AC 1/4" x 3/8" M',
                'harga' => 150000.00,
                'uom' => 'meter',
                'jenis_jasa_id' => '28',
            ],
            [
                'id' => 6,
                'itemcode' => '6',
                'nama' => 'BRACKET AC OUTDOR',
                'harga' => 120000.00,
                'uom' => 'set',
                'jenis_jasa_id' => '28',
            ],
            [
                'id' => 7,
                'itemcode' => '7',
                'nama' => 'KABEL NYM 3 x 1,5 M',
                'harga' => 25000.00,
                'uom' => 'meter',
                'jenis_jasa_id' => '28',
            ],
            [
                'id' => 8,
                'itemcode' => '8',
                'nama' => 'SELANG FLEXIBLE ( PEMBUANGAN ) 20mm',
                'harga' => 10000.00,
                'uom' => 'meter',
                'jenis_jasa_id' => '28',
            ],
            [
                'id' => 9,
                'itemcode' => '9',
                'nama' => 'DAK TAPE PIPA AC',
                'harga' => 10000.00,
                'uom' => 'pcs',
                'jenis_jasa_id' => '28',
            ],
        ];

        foreach ($accessories as $accessori) {
            Accessori::create($accessori);
        }
    }
}
