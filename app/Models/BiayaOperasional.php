<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiayaOperasional extends Model
{
    use HasFactory;
    
    protected $table = 'biaya_operasional';
    
    protected $fillable = [
        'tanggal',
        'keterangan',
        'volume',
        'satuan',
        'harga',
        'total_harga',
    ];
    
    protected $casts = [
        'tanggal' => 'date',
        'volume' => 'decimal:2',
        'harga' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];
}
