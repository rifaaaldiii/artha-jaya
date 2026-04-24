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
        Schema::create('jasa_update_tokens', function (Blueprint $table) {
            $table->id();
            // Use unsignedBigInteger to match jasas.id type exactly
            $table->unsignedBigInteger('jasa_id');
            $table->string('token', 64)->unique(); // SHA-256 hash
            $table->string('target_status')->default('selesai dikerjakan');
            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->string('used_by_ip')->nullable();
            $table->string('used_by_device')->nullable();
            $table->dateTime('expires_at');
            $table->timestamps();
            
            // Add foreign key constraint separately
            $table->foreign('jasa_id')
                  ->references('id')
                  ->on('jasas')
                  ->onDelete('cascade');
            
            $table->index(['token', 'is_used']);
            $table->index(['jasa_id', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jasa_update_tokens');
    }
};
