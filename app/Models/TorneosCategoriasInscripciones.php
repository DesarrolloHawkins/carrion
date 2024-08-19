<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//softModel
use Illuminate\Database\Eloquent\SoftDeletes;

class TorneosCategoriasInscripciones extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'torneos_categorias_inscripciones';

    
    protected $fillable = [
        'torneo_categoria_id',
        'jugador_id',
        'fecha_inscripcion',
        'email',
        'telefono',
        'DNI',
        'nickName',
        'nombre',
        'apellidos',
        'ciudad',
        'genero',
        'categoria',
        'comentario',
        'pagado',
        'total_precio',
        'pendiente'
    ];

    public function torneoCategoria()
    {
        return $this->belongsTo(TorneosCategorias::class, 'torneo_categoria_id');
    }

    public function jugador()
    {
        return $this->belongsTo(Cliente::class, 'jugador_id');
    }

    public function disponibilidad()
    {
        return $this->hasMany(TorneosInscripcionDisponibilidad::class, 'inscripcion_id');
    }

    public function duos()
    {
        return $this->hasMany(TorneosDuos::class, 'inscripcion_id');
    }

    public function duos2()
    {
        return $this->hasMany(TorneosDuos::class, 'inscripcion_id_2');
    }

    



}
