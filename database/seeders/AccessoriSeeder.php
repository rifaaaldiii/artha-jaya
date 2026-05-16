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
            ],
            [
                'id' => 2,
                'itemcode' => '2',
                'nama' => 'BRACKET AC OUTDOR',
                'harga' => 50000.00,
                'uom' => 'set',
            ],
            [
                'id' => 3,
                'itemcode' => '3',
                'nama' => 'KABEL NYM 3 x 1,5 M',
                'harga' => 20000.00,
                'uom' => 'meter',
            ],
            [
                'id' => 4,
                'itemcode' => '4',
                'nama' => 'SELANG FLEXIBLE ( PEMBUANGAN ) 20mm',
                'harga' => 10000.00,
                'uom' => 'mm',
            ],
        ];

        foreach ($accessories as $accessori) {
            Accessori::create($accessori);
        }
    }
}
