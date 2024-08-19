<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pistas extends Model
{
    use HasFactory;

    protected $table = 'pistas';

    protected $fillable = [
        'nombre',
        'deporte',
        'tipo',
        'caracteristicas',
        'tamano',
        'online',
        'disponible',
    ];

    public function deporteRelacion()
    {
        return $this->belongsTo(Deporte::class, 'deporte');
    }

    public function tipoRelacion()
    {
        return $this->belongsTo(PistaTipo::class, 'tipo');
    }

    public function caracteristicaRelacion()
    {
        return $this->belongsTo(PistaCarasteristicas::class, 'caracteristicas');
    }

    public function tamanoRelacion()
    {
        return $this->belongsTo(PistaTamano::class, 'tamano');
    }
}
