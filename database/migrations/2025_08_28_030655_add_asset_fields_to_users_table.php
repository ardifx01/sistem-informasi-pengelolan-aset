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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip', 50)->nullable();
            $table->string('username', 100)->unique()->nullable();
            $table->string('role', 20)->default('PIC'); // PIC / GA (sementara)
            $table->foreignId('cabang_id')
                  ->nullable()
                  ->constrained('cabangs')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
           $table->dropConstrainedForeignId('cabang_id');
            $table->dropColumn(['nip','username','role']);
        });
    }
};
