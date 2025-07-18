<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SugarOutput extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'nama_pembeli',
        'sak',
        'bobot',
        'harga_per_kg',
        'total_harga',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'sak' => 'integer',
        'bobot' => 'decimal:2',
        'harga_per_kg' => 'integer',
        'total_harga' => 'integer',
    ];
}