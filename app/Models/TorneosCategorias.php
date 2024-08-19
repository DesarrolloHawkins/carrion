<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TorneosCategorias extends Model
{
    use HasFactory;

    protected $table = 'torneos_categorias';

    protected $fillable = [
        'torneo_id',
        'categoria_id',
        'max_jugadores',
        'formato_juego',
        'inscripciones_abiertas'
    ];

    public function torneo()
    {
        return $this->belongsTo(Torneos::class, 'torneo_id');
    }

    public function inscripciones()
    {
        return $this->hasMany(TorneosCategoriasInscripciones::class, 'torneo_categoria_id');
    }

    public function jugadores()
    {
        return $this->hasManyThrough(
            Cliente::class,
            TorneosCategoriasInscripciones::class,
            'torneo_categoria_id', // Clave foránea en TorneosCategoriasInscripciones
            'id', // Clave foránea en Cliente
            'id', // Clave local en TorneosCategorias
            'jugador_id' // Clave local en TorneosCategoriasInscripciones
        );
    }

    // Nueva relación: Duos dentro de esta categoría
    public function duos()
    {
        return $this->hasManyThrough(
            TorneosDuos::class,
            TorneosCategoriasInscripciones::class,
            'torneo_categoria_id', // Foreign key en TorneosCategoriasInscripciones
            'inscripcion_id', // Foreign key en TorneosDuos
            'id', // Local key en TorneosCategorias
            'id'  // Local key en TorneosCategoriasInscripciones
        );
    }

    // Relación con los partidos
    public function partidos()
    {
        return $this->hasMany(Partidos::class, 'torneos_categorias_id');
    }

    public function categoria()
    {
        return $this->belongsTo(categoriaJugadores::class, 'categoria_id');
    }
}
