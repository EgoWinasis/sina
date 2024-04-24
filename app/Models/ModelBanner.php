<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelBanner extends Model
{
    use HasFactory;
    public $table = "banner";
    protected $fillable = [
        'kantor',
        'toko',
        'alamat',
        'deskripsi',
        'image',
        'panjang',
        'lebar',
        'hp',
        'desain',
        'status',
        'deadline',
        'input_by',
    ];
}
