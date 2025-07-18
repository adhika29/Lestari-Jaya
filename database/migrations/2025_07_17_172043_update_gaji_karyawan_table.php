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
            // Hapus kolom yang tidak diperlukan lagi
            $table->dropForeign(['karyawan_id']);
            $table->dropColumn([
                'karyawan_id',
                'bulan',
                'tahun',
                'gaji_pokok',
                'bonus',
                'potongan',
                'keterangan',
                'status_dibayar'
            ]);
            
            // Tambahkan kolom baru sesuai dengan gambar
            $table->integer('sak')->after('tanggal');
            $table->decimal('bobot_kg', 12, 2)->after('sak');
            $table->decimal('jumlah_gula_ton', 12, 2)->after('bobot_kg');
            $table->decimal('gaji_per_ton', 12, 2)->default(600000)->after('jumlah_gula_ton');
            // Tidak perlu menambahkan total_gaji karena sudah ada
            // Cukup pindahkan posisinya jika diperlukan
            $table->integer('jumlah_karyawan')->after('total_gaji');
            $table->decimal('gaji_per_karyawan', 12, 2)->after('jumlah_karyawan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gaji_karyawan', function (Blueprint $table) {
            // Kembalikan kolom yang dihapus, tapi periksa dulu apakah sudah ada
            if (!Schema::hasColumn('gaji_karyawan', 'karyawan_id')) {
                $table->foreignId('karyawan_id')->nullable();
            }
            if (!Schema::hasColumn('gaji_karyawan', 'bulan')) {
                $table->string('bulan')->nullable();
            }
            if (!Schema::hasColumn('gaji_karyawan', 'tahun')) {
                $table->integer('tahun')->nullable();
            }
            if (!Schema::hasColumn('gaji_karyawan', 'gaji_pokok')) {
                $table->decimal('gaji_pokok', 12, 2)->default(0)->nullable();
            }
            if (!Schema::hasColumn('gaji_karyawan', 'bonus')) {
                $table->decimal('bonus', 12, 2)->default(0)->nullable();
            }
            if (!Schema::hasColumn('gaji_karyawan', 'potongan')) {
                $table->decimal('potongan', 12, 2)->default(0)->nullable();
            }
            if (!Schema::hasColumn('gaji_karyawan', 'keterangan')) {
                $table->text('keterangan')->nullable();
            }
            if (!Schema::hasColumn('gaji_karyawan', 'status_dibayar')) {
                $table->boolean('status_dibayar')->default(false)->nullable();
            }
            
            // Hapus kolom yang ditambahkan
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