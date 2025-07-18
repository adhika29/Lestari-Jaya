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
        Schema::create('sugar_cane_shipments', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengirim');
            $table->enum('jenis_tebu', ['Cening (CN)', 'Bululawang (BL)', 'Baru Rakyat (BR)']);
            $table->integer('bobot_kg');
            $table->integer('harga_per_kg');
            $table->integer('total_harga');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sugar_cane_shipments');
    }
};
