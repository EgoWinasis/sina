<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelPerangkat extends Model
{
    use HasFactory;
    public $table = "perangkats";
    protected $fillable = [
        'perangkat',
        'username',
        'password',
        'kantor',
    ];
}
