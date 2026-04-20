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
        Schema::table('teams', function (Blueprint $table) {
            // Remove status column
            $table->dropColumn('status');
            
            // Add order column for sorting teams
            $table->integer('order')->default(0)->after('nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('order');
            $table->enum('status', ['ready', 'busy'])->default('ready')->after('nama');
        });
        
        // Reset all teams to ready status
        DB::table('teams')->update(['status' => 'ready']);
    }
};
