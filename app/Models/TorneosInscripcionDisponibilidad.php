<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TorneosInscripcionDisponibilidad extends Model
{
    use HasFactory;

    protected $table = 'torneos_inscripcion_disponibilidad';

    protected $fillable = [
        'torneo_id',
        'inscripcion_id',
        'fecha_no_disponible',
        'hora_no_disponible'
    ];

    public function torneo()
    {
        return $this->belongsTo(Torneos::class);
    }

    public function duos()
    {
        return $this->belongsTo(TorneosDuos::class);
    }
}
