<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, modify the enum column to include both old and new values
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'administrator',
                'superadmin',
                'admin_toko',
                'kepala_lapangan',
                'admin_gudang',
                'kepala_teknisi_lapangan',
                'kepala_teknisi_gudang',
                'petugas',
            ])->change();
        });

        // Then, migrate existing data to new role values
        DB::table('users')->where('role', 'admin_gudang')->update(['role' => 'superadmin']);
        DB::table('users')->where('role', 'kepala_teknisi_lapangan')->update(['role' => 'kepala_lapangan']);
        DB::table('users')->where('role', 'kepala_teknisi_gudang')->update(['role' => 'superadmin']);
        DB::table('users')->where('role', 'petugas')->update(['role' => 'admin_toko']);

        // Finally, modify the enum column to only include new values
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'administrator',
                'superadmin',
                'admin_toko',
                'kepala_lapangan',
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert data back to old role values
        DB::table('users')->where('role', 'superadmin')->update(['role' => 'admin_gudang']);
        DB::table('users')->where('role', 'kepala_lapangan')->update(['role' => 'kepala_teknisi_lapangan']);
        DB::table('users')->where('role', 'admin_toko')->update(['role' => 'petugas']);

        // Then revert the enum column
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'administrator',
                'admin_toko',
                'admin_gudang',
                'kepala_teknisi_lapangan',
                'kepala_teknisi_gudang',
                'petugas',
            ])->change();
        });
    }
};
