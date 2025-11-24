<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\team;
use App\Models\petukang;
use App\Models\petugas;
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
            'name' => 'Rifaldi Yuda',
            'email' => 'rifaldiyuda29@gmail.com',
            'password' => bcrypt('aev872767'),
            'role' => 'administrator',
            'username' => 'rifaldi'
        ]);

        User::factory()->create([
            'name' => 'Admin Toko',
            'email' => 'admintoko@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'admin_toko',
            'username' => 'admintoko'
        ]);

        User::factory()->create([
            'name' => 'Admin Gudang',
            'email' => 'admingudang@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'admin_gudang',
            'username' => 'admingudang'
        ]);

        User::factory()->create([
            'name' => 'Kepala Teknisi Lapangan',
            'email' => 'kepalateknisilapangan@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'kepala_teknisi_lapangan',
            'username' => 'kepalateknisilapangan'
        ]);

        User::factory()->create([
            'name' => 'Kepala Teknisi Gudang',
            'email' => 'kepalateknisigudang@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'kepala_teknisi_gudang',
            'username' => 'kepalateknisigudang'
        ]);

        User::factory()->create([
            'name' => 'Petugas',
            'email' => 'petugas@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'username' => 'petugas'
        ]);

        User::factory()->create([
            'name' => 'Petukang',
            'email' => 'petukang@artha-jaya.com',
            'password' => bcrypt('password'),
            'role' => 'petukang',
            'username' => 'petukang'
        ]);

        // Team seeding
        $teams = [
            ['nama' => 'Team A', 'status' => 'ready'],
            ['nama' => 'Team B', 'status' => 'ready'],
            ['nama' => 'Team C', 'status' => 'ready'],
            ['nama' => 'Team D', 'status' => 'ready'],
            ['nama' => 'Team E', 'status' => 'ready'],
            ['nama' => 'Team F', 'status' => 'ready'],
            ['nama' => 'Team G', 'status' => 'ready'],
            ['nama' => 'Team H', 'status' => 'ready'],
            ['nama' => 'Team I', 'status' => 'ready'],
        ];

        foreach ($teams as $teamData) {
            team::factory()->create($teamData);
        }

        // Petukang seeding
        $petukangs = [
            ['nama' => 'Petukang 1', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 1],
            ['nama' => 'Petukang 2', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 1],
            ['nama' => 'Petukang 3', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 2],
            ['nama' => 'Petukang 4', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 2],
            ['nama' => 'Petukang 5', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 3],
            ['nama' => 'Petukang 6', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 3],
            ['nama' => 'Petukang 7', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 4],
            ['nama' => 'Petukang 8', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 4],
            ['nama' => 'Petukang 9', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 5],
            ['nama' => 'Petukang 10', 'status' => 'ready', 'kontak' => '082123609953', 'team_id' => 5],
        ];

        foreach ($petukangs as $petukangData) {
            petukang::factory()->create($petukangData);
        }

        $petugas = [
            ['nama' => 'Petugas 1', 'status' => 'ready', 'kontak' => '082123609953'],
            ['nama' => 'Petugas 2', 'status' => 'ready', 'kontak' => '082123609953'],
            ['nama' => 'Petugas 3', 'status' => 'ready', 'kontak' => '082123609953'],
            ['nama' => 'Petugas 4', 'status' => 'ready', 'kontak' => '082123609953'],
            ['nama' => 'Petugas 5', 'status' => 'ready', 'kontak' => '082123609953'],
            ['nama' => 'Petugas 6', 'status' => 'ready', 'kontak' => '082123609953'],
            ['nama' => 'Petugas 7', 'status' => 'ready', 'kontak' => '082123609953'],
            ['nama' => 'Petugas 8', 'status' => 'ready', 'kontak' => '082123609953'],
            ['nama' => 'Petugas 9', 'status' => 'ready', 'kontak' => '082123609953'],
            ['nama' => 'Petugas 10', 'status' => 'ready', 'kontak' => '082123609953'],
        ];

        foreach ($petugas as $petugasData) {
            petugas::factory()->create($petugasData);
        }
    }
}
