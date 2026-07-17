<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('schedules', 'jasa_id')) {
            return;
        }

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropConstrainedForeignId('jasa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('schedules', 'jasa_id')) {
            return;
        }

        Schema::table('schedules', function (Blueprint $table) {
            $table->foreignId('jasa_id')->nullable()->constrained('jasas')->nullOnDelete();
        });
    }
};
