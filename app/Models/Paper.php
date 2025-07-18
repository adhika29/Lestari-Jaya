<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'abstract',
        'author',
        'publication_date',
        'category',
        'keywords',
        'file_path'
    ];
    
    protected $casts = [
        'publication_date' => 'date'
    ];
}
