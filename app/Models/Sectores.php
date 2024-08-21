<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sectores extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sectores';

    protected $fillable = [
        'nombre',
        'id_zona',
    ];

    
}
