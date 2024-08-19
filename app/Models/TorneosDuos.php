<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TorneosDuos extends Model
{
    use HasFactory;

    protected $table = 'torneos_duos';

    protected $fillable = [
        'inscripcion_id',
        'inscripcion_id_2',
        'grupo',
        'presentado',
        'estado'
    ];

    public function inscripcion()
    {
        return $this->belongsTo(TorneosCategoriasInscripciones::class);
    }

    public function inscripcion2()
    {
        return $this->belongsTo(TorneosCategoriasInscripciones::class, 'inscripcion_id_2');
    }

    // Relación con la categoría de torneo (opcional, si es necesario)
    public function categoria()
    {
        return $this->belongsTo(TorneosCategorias::class, 'torneo_categoria_id');
    }

      // Relación con TorneosInscripcionDisponibilidad
      public function disponibilidad()
      {
          return $this->hasMany(TorneosInscripcionDisponibilidad::class, 'inscripcion_id');
      }

    
}
