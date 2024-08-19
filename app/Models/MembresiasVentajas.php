<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembresiasVentajas extends Model
{
    use HasFactory;

    protected $table = 'membresias_ventajas';

    protected $fillable = [
        'membresia_id',
        'zona_id',
        'tipo_descuento',
        'descuento',
        'lunes',
        'martes',
        'miercoles',
        'jueves',
        'viernes',
        'sabado',
        'domingo',
        'hora_inicio',
        'hora_fin',
        'antelacion_reserva'
    ];

    public function membresia()
    {
        return $this->belongsTo(Membresias::class, 'membresia_id');
    }

    public function zona()
    {
        return $this->belongsTo(Zonas::class, 'zona_id');
    }
}
