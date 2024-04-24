<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanModel extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql_sigma'; 
    protected $table = 'lloan';
}
