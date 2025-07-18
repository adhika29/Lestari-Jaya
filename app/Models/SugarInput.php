<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SugarInput extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'sak',
        'bobot',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'sak' => 'integer',
        'bobot' => 'integer',
    ];
}