<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterSlikModelClp extends Model
{
    use HasFactory;

    public $table = "register_slik_clp";
    protected $fillable = [
        'no_ref',
        'tgl_permintaan',
        'nik',
        'tujuan_permintaan',
        'nama',
        'tgl_lahir',
        'tempat_lahir',
        'alamat',
        'petugas',
        'tipe',
    ];
}
