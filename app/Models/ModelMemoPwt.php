<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelMemoPwt extends Model
{
    use HasFactory;
    public $table = "memo_pwt";
    protected $fillable = [
        'id_register',
        'marketing',
        // 
        'nama_debitur',
        'nik_debitur',
        'tempat_lahir_debitur',
        'tgl_lahir_debitur',
        'alamat_debitur',
        'tipe_debitur',
        'file_debitur',
        // 
        'nama_penjamin',
        'nik_penjamin',
        'tempat_lahir_penjamin',
        'tgl_lahir_penjamin',
        'alamat_penjamin',
        'tipe_penjamin',
        'file_penjamin',
    ];
}
