<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\team;
use App\Models\petukang;
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
        // User seeding
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

        // Team seeding
        $teams = [
            ['nama' => 'Team A', 'status' => 'ready'],
            ['nama' => 'Team B', 'status' => 'ready'],
            ['nama' => 'Team C', 'status' => 'ready'],
            ['nama' => 'Team D', 'status' => 'ready'],
        ];

        foreach ($teams as $teamData) {
            team::factory()->create($teamData);
        }

        // Petukang seeding
        $petukangs = [
            ['nama' => 'Petukang 1', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 1],
            ['nama' => 'Petukang 2', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 2],
            ['nama' => 'Petukang 3', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 1],
            ['nama' => 'Petukang 4', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 2],
        ];

        foreach ($petukangs as $petukangData) {
            petukang::factory()->create($petukangData);
        }
    }
}
