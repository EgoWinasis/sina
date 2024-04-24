<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelIpAddress extends Model
{
    use HasFactory;
    public $table = "ip_address";
    protected $fillable = [
        'device',
        'ip_address',
        'kantor',
    ];
}
