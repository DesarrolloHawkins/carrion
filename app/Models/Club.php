<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    protected $table = 'club';

    protected $fillable = [
        'nombre',
        'numero_pistas',
        'pagina_web',
        'facebook',
        'twitter',
        'descripcion',
        'nombre_contacto',
        'email_contacto',
        'telefono',
        'direccion',
        'pais',
        'ciudad',
        'poblacion',
        'codigo_postal',
        'lunes_apertura',
        'lunes_cierre',
        'martes_apertura',
        'martes_cierre',
        'miercoles_apertura',
        'miercoles_cierre',
        'jueves_apertura',
        'jueves_cierre',
        'viernes_apertura',
        'viernes_cierre',
        'sabado_apertura',
        'sabado_cierre',
        'domingo_apertura',
        'domingo_cierre',
        'extracto',
        'limite_reserva',
        'tiempo_cancelacion',
        'maximo_reservas_dia',
        'maximo_reservas_activas'
    ];

    
}
