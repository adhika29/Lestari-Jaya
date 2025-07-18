<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GajiKaryawan extends Model
{
    use HasFactory;
    
    protected $table = 'gaji_karyawan';
    
    protected $fillable = [
        'tanggal',
        'sak',
        'bobot_kg',
        'jumlah_gula_ton',
        'gaji_per_ton',
        'total_gaji',
        'jumlah_karyawan',
        'gaji_per_karyawan'
    ];
    
    protected $casts = [
        'tanggal' => 'date',
        'sak' => 'integer',
        'bobot_kg' => 'decimal:2',
        'jumlah_gula_ton' => 'decimal:2',
        'gaji_per_ton' => 'decimal:2',
        'total_gaji' => 'decimal:2',
        'jumlah_karyawan' => 'integer',
        'gaji_per_karyawan' => 'decimal:2'
    ];
    
    // Relasi many-to-many dengan Karyawan
    public function karyawan()
    {
        return $this->belongsToMany(Karyawan::class, 'gaji_karyawan_karyawan');
    }
}
