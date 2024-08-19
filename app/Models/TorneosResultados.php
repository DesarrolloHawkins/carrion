<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TorneosResultados extends Model
{
    use HasFactory;

    protected $table = 'torneos_resultados';

    protected $fillable = [
        'torneo_id',
        'jugador_id',
        'inscripcion_id',
        'puntos',
        'resultado',
        'posicion_final'
    ];

    public function inscripcion()
{
    return $this->belongsTo(TorneosCategoriasInscripciones::class, 'inscripcion_id');
}

}


