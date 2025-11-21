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
        Schema::create('jasas', function (Blueprint $table) {
            $table->id();
            $table->string('no_jasa');
            $table->string('no_ref');
            $table->string('jenis_layanan');
            $table->dateTime('jadwal')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('petugas_id')->nullable()->constrained('petugas')->onDelete('set null');
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggans')->onDelete('set null');
            $table->enum('status', ['jasa baru', 'terjadwal', 'selesai dikerjakan', 'selesai']);
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updateAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jasas');
    }
};
