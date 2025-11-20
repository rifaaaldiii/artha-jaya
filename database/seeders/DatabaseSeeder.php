<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin Toko',
            'email' => 'admintoko@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'admin_toko',
        ]);

        User::factory()->create([
            'name' => 'Admin Gudang',
            'email' => 'admingudang@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'admin_gudang',
        ]);

        User::factory()->create([
            'name' => 'Kepala Teknisi Lapangan',
            'email' => 'kepalateknisilapangan@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'kepala_teknisi_lapangan',
        ]);

        User::factory()->create([
            'name' => 'Kepala Teknisi Gudang',
            'email' => 'kepalateknisigudang@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'kepala_teknisi_gudang',
        ]);

        User::factory()->create([
            'name' => 'Petugas',
            'email' => 'petugas@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
        ]);

        User::factory()->create([
            'name' => 'Petukang',
            'email' => 'petukang@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'petukang',
        ]);
    }
}
