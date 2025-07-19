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
        Schema::create('gaji_karyawan_karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gaji_karyawan_id')->constrained('gaji_karyawan')->onDelete('cascade');
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->timestamps();
            
            // Tambahkan indeks untuk mempercepat pencarian
            $table->index(['gaji_karyawan_id', 'karyawan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gaji_karyawan_karyawan');
    }
};