<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaJugadores extends Model
{
    use HasFactory;
    protected $table = 'categorias_jugadores';

    protected $fillable = ['nombre'];
}
