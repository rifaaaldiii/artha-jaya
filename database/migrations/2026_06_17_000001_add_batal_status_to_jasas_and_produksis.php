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
        // Add 'batal' to jasas.status ENUM
        DB::statement("ALTER TABLE jasas MODIFY COLUMN status ENUM('jasa baru', 'terjadwal', 'selesai dikerjakan', 'selesai', 'batal') DEFAULT 'jasa baru'");

        // Add cancelled_reason and cancelled_at columns to jasas
        Schema::table('jasas', function (Blueprint $table) {
            $table->text('cancelled_reason')->nullable()->after('status');
            $table->timestamp('cancelled_at')->nullable()->after('cancelled_reason');
            $table->unsignedBigInteger('cancelled_by')->nullable()->after('cancelled_at');
        });

        // Add 'batal' to produksis.status ENUM
        DB::statement("ALTER TABLE produksis MODIFY COLUMN status ENUM('baru', 'proses', 'siap diambil', 'selesai', 'batal') DEFAULT 'baru'");

        // Add cancelled_reason and cancelled_at columns to produksis
        Schema::table('produksis', function (Blueprint $table) {
            $table->text('cancelled_reason')->nullable()->after('status');
            $table->timestamp('cancelled_at')->nullable()->after('cancelled_reason');
            $table->unsignedBigInteger('cancelled_by')->nullable()->after('cancelled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove columns from jasas
        Schema::table('jasas', function (Blueprint $table) {
            $table->dropColumn(['cancelled_reason', 'cancelled_at', 'cancelled_by']);
        });

        // Revert jasas ENUM
        DB::statement("ALTER TABLE jasas MODIFY COLUMN status ENUM('jasa baru', 'terjadwal', 'selesai dikerjakan', 'selesai') DEFAULT 'jasa baru'");

        // Remove columns from produksis
        Schema::table('produksis', function (Blueprint $table) {
            $table->dropColumn(['cancelled_reason', 'cancelled_at', 'cancelled_by']);
        });

        // Revert produksis ENUM
        DB::statement("ALTER TABLE produksis MODIFY COLUMN status ENUM('baru', 'proses', 'siap diambil', 'selesai') DEFAULT 'baru'");
    }
};
