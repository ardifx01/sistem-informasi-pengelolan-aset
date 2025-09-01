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
        Schema::create('transfer_barangs', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('barang_id')
                ->constrained('barangs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->unsignedBigInteger('cabang_pengirim_id');
            $table->unsignedBigInteger('cabang_penerima_id');
            $table->text('description')->nullable();
            $table->enum('status_approve', ['pending', 'approve', 'diterima'])->default('pending');
            $table->unsignedBigInteger('request_id');
            $table->timestamps();

            //foreign key
            $table->foreign('cabang_pengirim_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cabang_penerima_id')->references('id')->on('userss')->onDelete('cascade');
            $table->foreign('request_id')->references('id')->on('request_barangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_barangs');
    }
};
