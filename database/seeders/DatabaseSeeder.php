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
            'username' => 'administrator',
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
            'name' => 'Superadmin',
            'email' => 'superadmin@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
            'username' => 'admin',
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

        // Team seeding - directly create
        $teams = [
            ['nama' => 'Team DC', 'status' => 'ready'],
            ['nama' => 'Team AJR', 'status' => 'ready'],
        ];

        foreach ($teams as $teamData) {
            Team::create($teamData);
        }

        // Petukang seeding - directly create
        $petukangs = [
            ['nama' => 'Aji', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 1],
            ['nama' => 'Samsul', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 1],
            ['nama' => 'Fauzan', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 1],
        ];

        foreach ($petukangs as $petukangData) {
            Petukang::create($petukangData);
        }

        $petugas = [
            ['nama' => 'Bada', 'status' => 'ready', 'kontak' => '082123609953'],
            ['nama' => 'Rian', 'status' => 'ready', 'kontak' => '082123609953'],
            ['nama' => 'Yuda', 'status' => 'ready', 'kontak' => '082123609953'],
            ['nama' => 'Rifki', 'status' => 'ready', 'kontak' => '082123609953'],
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
