<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spk2Model extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql_nusamba'; 
    protected $table = 'spk2';
}
