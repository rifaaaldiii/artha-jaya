<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This migration is now handled by 2026_04_19_220000_fix_produksi_status_enum.php
        // which properly updates the enum before changing data
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to old status values
        DB::table('produksis')->where('status', 'baru')->update(['status' => 'produksi baru']);
        DB::table('produksis')->where('status', 'proses')->update(['status' => 'dalam pengerjaan']);
        DB::table('produksis')->where('status', 'siap diambil')->update(['status' => 'produksi siap diambil']);
    }
};
