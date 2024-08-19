<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Festivos extends Model
{
    use HasFactory;

    protected $table = 'festivos';

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'cierre'
    ];
}
