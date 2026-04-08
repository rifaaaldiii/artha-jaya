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
        Schema::table('jasas', function (Blueprint $table) {
            // Remove item-specific fields that will move to jasa_items
            $table->dropColumn(['jenis_layanan', 'harga']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jasas', function (Blueprint $table) {
            $table->string('jenis_layanan')->nullable();
            $table->decimal('harga', 15, 2)->nullable();
        });
    }
};
