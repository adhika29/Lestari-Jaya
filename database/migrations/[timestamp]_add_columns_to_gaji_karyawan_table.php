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
        Schema::table('gaji_karyawan', function (Blueprint $table) {
            // Tambahkan kolom baru jika belum ada
            if (!Schema::hasColumn('gaji_karyawan', 'sak')) {
                $table->integer('sak')->after('tanggal')->nullable();
            }
            if (!Schema::hasColumn('gaji_karyawan', 'bobot_kg')) {
                $table->decimal('bobot_kg', 12, 2)->after('sak')->nullable();
            }
            if (!Schema::hasColumn('gaji_karyawan', 'jumlah_gula_ton')) {
                $table->decimal('jumlah_gula_ton', 12, 2)->after('bobot_kg')->nullable();
            }
            if (!Schema::hasColumn('gaji_karyawan', 'gaji_per_ton')) {
                $table->decimal('gaji_per_ton', 12, 2)->default(600000)->after('jumlah_gula_ton')->nullable();
            }
            if (!Schema::hasColumn('gaji_karyawan', 'jumlah_karyawan')) {
                $table->integer('jumlah_karyawan')->after('total_gaji')->nullable();
            }
            if (!Schema::hasColumn('gaji_karyawan', 'gaji_per_karyawan')) {
                $table->decimal('gaji_per_karyawan', 12, 2)->after('jumlah_karyawan')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gaji_karyawan', function (Blueprint $table) {
            $table->dropColumn([
                'sak',
                'bobot_kg',
                'jumlah_gula_ton',
                'gaji_per_ton',
                'jumlah_karyawan',
                'gaji_per_karyawan'
            ]);
        });
    }
};