<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class ModelJabatan extends Authenticatable
{
    use HasFactory;
    public $table = "jabatan";
    protected $fillable = [
        'name',
    ];
}
