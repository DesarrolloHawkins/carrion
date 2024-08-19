<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PistaPrecios extends Model
{
    use HasFactory;

    protected $table = 'pista_precios';

    protected $fillable = [
        'pista_id',
        'regla',
        'duracion',
        'precio',
        'hora_inicio',
        'hora_fin',
        'lunes',
        'martes',
        'miercoles',
        'jueves',
        'viernes',
        'sabado',
        'domingo',
        'temporal',
        'nombre_temporal',
        'fecha_inicio',
        'fecha_fin'

    ];


    public function pista()
    {
        return $this->belongsTo(Pistas::class, 'pista_id');
    }



}
