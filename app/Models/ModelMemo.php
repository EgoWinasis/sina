<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelMemo extends Model
{
    use HasFactory;
    public $table = "memo";
    protected $fillable = [
        'marketing',
        // 
        'nama_debitur',
        'nik_debitur',
        'tempat_lahir_debitur',
        'tgl_lahir_debitur',
        'alamat_debitur',
        'file_debitur',
        // 
        'nama_penjamin',
        'nik_penjamin',
        'tempat_lahir_penjamin',
        'tgl_lahir_penjamin',
        'alamat_penjamin',
        'file_penjamin',
    ];
}
