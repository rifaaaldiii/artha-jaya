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
        Schema::create('produksis', function (Blueprint $table) {
            $table->id();
            $table->string('no_produksi');
            $table->string('nama_produksi');
            $table->integer('jumlah');
            $table->foreignId('petukang_id')
                ->constrained('users')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
                $table->enum('status', ['produksi baru', 'siap produksi', 'dalam pengerjaan', 'selesai dikerjakan', 'lolos qc', 'produksi siap diambil', 'selesai']);
            $table->string('catatan');
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updateAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksis');
    }
};