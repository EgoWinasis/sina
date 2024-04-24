<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelPencapaian extends Model
{
    use HasFactory;
    public $table = "pencapaian_lalu";
    protected $fillable = [
        'kode_ao',
        'nama_ao',
        'nasabah',
        'os',
        'bulan',
       
    ];
}
