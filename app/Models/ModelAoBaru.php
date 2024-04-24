<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelAoBaru extends Model
{
    use HasFactory;
    public $table = "ao_baru";
    protected $fillable = [
        'kode_ao',
        'nama_ao',
        'plafon',
       
    ];
}
