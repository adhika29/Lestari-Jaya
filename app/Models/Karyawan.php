<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;
    
    protected $table = 'karyawan';
    
    protected $fillable = [
        'nama',
        'tanggal_bergabung',
        'alamat',
        'telepon',
        'status_aktif'
    ];
    
    protected $casts = [
        'tanggal_bergabung' => 'date',
        'status_aktif' => 'boolean'
    ];
    
    // Relasi many-to-many dengan GajiKaryawan
    public function gajiKaryawan()
    {
        return $this->belongsToMany(GajiKaryawan::class, 'gaji_karyawan_karyawan');
    }
}
