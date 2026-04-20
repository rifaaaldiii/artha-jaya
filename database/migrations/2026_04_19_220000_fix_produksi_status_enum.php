<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, expand the enum to include both old and new values
        DB::statement("ALTER TABLE produksis MODIFY COLUMN status ENUM('produksi baru', 'siap produksi', 'dalam pengerjaan', 'selesai dikerjakan', 'lolos qc', 'produksi siap diambil', 'selesai', 'baru', 'proses', 'siap diambil') DEFAULT 'baru'");
        
        // Then update existing data to use new status values
        DB::table('produksis')->where('status', 'produksi baru')->update(['status' => 'baru']);
        DB::table('produksis')->where('status', 'siap produksi')->update(['status' => 'proses']);
        DB::table('produksis')->where('status', 'dalam pengerjaan')->update(['status' => 'proses']);
        DB::table('produksis')->where('status', 'selesai dikerjakan')->update(['status' => 'proses']);
        DB::table('produksis')->where('status', 'lolos qc')->update(['status' => 'proses']);
        DB::table('produksis')->where('status', 'produksi siap diambil')->update(['status' => 'siap diambil']);
        
        // Finally, change enum to only include new values
        DB::statement("ALTER TABLE produksis MODIFY COLUMN status ENUM('baru', 'proses', 'siap diambil', 'selesai') DEFAULT 'baru'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Expand enum to include old values
        DB::statement("ALTER TABLE produksis MODIFY COLUMN status ENUM('baru', 'proses', 'siap diambil', 'selesai', 'produksi baru', 'siap produksi', 'dalam pengerjaan', 'selesai dikerjakan', 'lolos qc', 'produksi siap diambil') DEFAULT 'produksi baru'");
        
        // Revert back to old status values
        DB::table('produksis')->where('status', 'baru')->update(['status' => 'produksi baru']);
        DB::table('produksis')->where('status', 'proses')->update(['status' => 'dalam pengerjaan']);
        DB::table('produksis')->where('status', 'siap diambil')->update(['status' => 'produksi siap diambil']);
        
        // Change enum back to old values only
        DB::statement("ALTER TABLE produksis MODIFY COLUMN status ENUM('produksi baru', 'siap produksi', 'dalam pengerjaan', 'selesai dikerjakan', 'lolos qc', 'produksi siap diambil', 'selesai') DEFAULT 'produksi baru'");
    }
};
