<?php

namespace Database\Seeders;

use App\Models\KategoriJasaItem;
use Illuminate\Database\Seeder;

class KategoriJasaItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategories = [
            [
                'nama' => 'PLUMBING',
                'deskripsi' => 'Kategori untuk jasa pemasangan pompa dan filter',
            ],
            [
                'nama' => 'BATHROOM',
                'deskripsi' => 'Kategori untuk jasa pemasangan water heater dan saniter',
            ],
            [
                'nama' => 'SMARTLOOK & DOOR',
                'deskripsi' => 'Kategori untuk jasa pemasangan CCTV, smartlock, dan layanan lainnya',
            ],
            [
                'nama' => 'AC',
                'deskripsi' => 'Kategori jasa pemasangan AC',
            ],
        ];

        foreach ($kategories as $kategori) {
            KategoriJasaItem::create($kategori);
        }
    }
}
