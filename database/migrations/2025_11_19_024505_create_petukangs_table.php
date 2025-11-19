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
        Schema::create('petukangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('status', ['ready', 'busy']);
            $table->string('kontak');
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('set null');
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updateAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petukangs');
    }
};
