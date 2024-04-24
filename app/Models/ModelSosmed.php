<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelSosmed extends Model
{
    use HasFactory;
    public $table = "sosial_media";
    protected $fillable = [
        'platform',
        'username',
        'password',
        'kantor',
    ];
}
