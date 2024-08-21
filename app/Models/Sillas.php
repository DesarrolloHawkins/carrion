<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sillas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sillas';


    protected $fillable = [
        'numero',
        'id_grada',
        'id_zona',
        'fila',
        'numero'
        
        
    ];

}
