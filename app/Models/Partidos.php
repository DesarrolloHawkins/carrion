<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partidos extends Model
{
    use HasFactory;

    protected $fillable = [
        'torneo_id',
        'equipo1_id',
        'equipo2_id',
        'dia',
        'hora_inicio',
        'hora_fin',
        'pista_id',
        'resultado',
        'torneos_categorias_id',
        'comentario',
        'finalizado',
        'bloqueado'
    ];

    public function torneo()
    {
        return $this->belongsTo(Torneos::class);
    }

    public function equipo1()
    {
        return $this->belongsTo(TorneosDuos::class);
    }

    public function equipo2()
    {
        return $this->belongsTo(TorneosDuos::class);
    }

    public function pista()
    {
        return $this->belongsTo(Pistas::class);
    }

    public function torneosCategoria()
    {
        return $this->belongsTo(TorneosCategorias::class);
    }
    public function torneos_categorias()
    {
        return $this->belongsTo(TorneosCategorias::class);
    }

      // Relación con los resultados del partido
      public function resultado()
      {
          return $this->hasOne(PartidoResultados::class, 'partido_id');
      }
}
