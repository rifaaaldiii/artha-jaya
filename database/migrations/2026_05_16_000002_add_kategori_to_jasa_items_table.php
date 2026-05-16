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
        Schema::table('jasa_items', function (Blueprint $table) {
            $table->foreignId('kategori_jasa_item_id')
                ->nullable()
                ->after('jasa_id')
                ->constrained('kategori_jasa_items')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jasa_items', function (Blueprint $table) {
            $table->dropForeign(['kategori_jasa_item_id']);
            $table->dropColumn('kategori_jasa_item_id');
        });
    }
};
