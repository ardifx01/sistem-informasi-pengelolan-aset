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
        Schema::create('request_barangs', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('barang_id')
                   ->constrained('barangs')
                   ->cascadeOnUpdate()
                   ->restrictOnDelete();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('cabang_id')
                  ->constrained('cabangs')
                  ->cascadeOnDelete();        
      
            $table->date('date_request')->useCurrent();
            $table->unsignedInteger('stock_request');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->enum('status_approve', ['pending', 'approved', 'rejected'])->default('pending');      
            $table->enum('status_use', ['pending', 'used', 'finished'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(
        
    ): void
    {
        Schema::dropIfExists('request_barangs');
    }
};
