<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiayaKonsumsi extends Model
{
    use HasFactory;

    protected $table = 'biaya_konsumsi';
    
    protected $fillable = [
        'tanggal',
        'keterangan',
        'volume',
        'satuan',
        'harga',
        'total_harga'
    ];
    
    protected $casts = [
        'tanggal' => 'date',
    ];
}