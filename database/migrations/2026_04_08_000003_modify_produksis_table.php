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
        Schema::table('produksis', function (Blueprint $table) {
            // Remove item-specific fields that will move to produksi_items
            $table->dropColumn(['nama_produksi', 'nama_bahan', 'jumlah', 'harga']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produksis', function (Blueprint $table) {
            $table->string('nama_produksi')->nullable();
            $table->string('nama_bahan')->nullable();
            $table->integer('jumlah')->nullable();
            $table->decimal('harga', 15, 2)->nullable();
        });
    }
};
