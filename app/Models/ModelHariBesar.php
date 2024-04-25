<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHariBesar extends Model
{
    use HasFactory;
    public $table = "hari_besar";
    protected $fillable = [
        'hari',
        'tanggal',
        'status',
        'desain',
    ];
}
