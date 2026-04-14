<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use App\Models\Petukang;
use App\Models\Petugas;
use App\Filament\Pages\Report;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\JenisJasaSeeder;
use Database\Seeders\JenisProduksiSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User seeding - directly create without factory
        User::create([
            'name' => 'Rifaldi Yuda',
            'email' => 'rifaldiyuda29@gmail.com',
            'password' => bcrypt('aev872767'),
            'role' => 'administrator',
            'username' => 'rifaaaldiii',
            'createdAt' => now(),
        ]);

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'administrator',
            'username' => 'admin',
            'createdAt' => now(),
        ]);

        User::create([
            'name' => 'Admin Toko',
            'email' => 'admintoko@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'admin_toko',
            'username' => 'admintoko',
            'createdAt' => now(),
        ]);

        User::create([
            'name' => 'Admin Gudang',
            'email' => 'admingudang@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'admin_gudang',
            'username' => 'admingudang',
            'createdAt' => now(),
        ]);

        User::create([
            'name' => 'Kepala Teknisi Lapangan',
            'email' => 'kepalateknisilapangan@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'kepala_teknisi_lapangan',
            'username' => 'kepalateknisilapangan',
            'createdAt' => now(),
        ]);

        User::create([
            'name' => 'Kepala Teknisi Gudang',
            'email' => 'kepalateknisigudang@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'kepala_teknisi_gudang',
            'username' => 'kepalateknisigudang',
            'createdAt' => now(),
        ]);

        User::create([
            'name' => 'Petugas',
            'email' => 'petugas@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'username' => 'petugas',
            'createdAt' => now(),
        ]);

        // Team seeding - directly create
        $teams = [
            ['nama' => 'Team A', 'status' => 'ready'],
            ['nama' => 'Team B', 'status' => 'ready'],
            ['nama' => 'Team C', 'status' => 'ready'],
        ];

        foreach ($teams as $teamData) {
            Team::create($teamData);
        }

        // Petukang seeding - directly create
        $petukangs = [
            ['nama' => 'Petukang 1', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 1],
            ['nama' => 'Petukang 2', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 1],
            ['nama' => 'Petukang 3', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 2],
            ['nama' => 'Petukang 4', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 2],
            ['nama' => 'Petukang 5', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 3],
        ];

        foreach ($petukangs as $petukangData) {
            Petukang::create($petukangData);
        }

        $petugas = [
            ['nama' => 'Petugas 1', 'status' => 'ready', 'kontak' => '082123609953'],
            ['nama' => 'Petugas 2', 'status' => 'ready', 'kontak' => '082123609953'],
            ['nama' => 'Petugas 3', 'status' => 'ready', 'kontak' => '082123609953'],
            ['nama' => 'Petugas 4', 'status' => 'ready', 'kontak' => '082123609953'],
            ['nama' => 'Petugas 5', 'status' => 'ready', 'kontak' => '082123609953'],
        ];

        foreach ($petugas as $petugasData) {
            Petugas::create($petugasData);
        }

        // Jenis Jasa seeding
        $this->call(JenisJasaSeeder::class);
        
        // Jenis Produksi seeding
        $this->call(JenisProduksiSeeder::class);
    }
}
