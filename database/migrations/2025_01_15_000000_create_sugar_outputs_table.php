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
        Schema::create('sugar_outputs', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama_pembeli');
            $table->integer('sak');
            $table->integer('bobot'); // dalam kg
            $table->integer('harga_per_kg');
            $table->integer('total_harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sugar_outputs');
    }
};