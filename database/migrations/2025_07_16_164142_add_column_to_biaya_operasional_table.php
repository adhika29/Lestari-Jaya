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
        Schema::table('biaya_operasional', function (Blueprint $table) {
            $table->date('tanggal');
            $table->string('keterangan');
            $table->decimal('volume', 10, 2);
            $table->string('satuan');
            $table->decimal('harga', 12, 2);
            $table->decimal('total_harga', 12, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if the columns exist before trying to drop them
        if (Schema::hasColumn('biaya_operasional', 'tanggal')) {
            Schema::table('biaya_operasional', function (Blueprint $table) {
                $table->dropColumn(['tanggal', 'keterangan', 'volume', 'satuan', 'harga', 'total_harga']);
            });
        }
    }
};
