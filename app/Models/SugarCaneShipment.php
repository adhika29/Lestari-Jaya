<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SugarCaneShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'bulan',
        'tahun',
        'nama_pengirim',
        'jenis_tebu',
        'bobot_kg',
        'harga_per_kg',
        'total_harga'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'bulan' => 'string',
        'tahun' => 'integer',
        'bobot_kg' => 'integer',
        'harga_per_kg' => 'integer',
        'total_harga' => 'integer',
    ];
}
