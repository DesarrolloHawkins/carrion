<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Pistas;
use App\Models\Cliente;
use App\Models\Monitor;
use App\Models\Torneos;
use App\Models\Partidos;

class Reservas extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'pista_id',
        'cliente_id',
        'dia',
        'hora_inicio',
        'hora_fin',
        'precio',
        'tipo_pago',
        'nombre_jugador',
        'nota',
        'tipo_reserva',
        'fecha_inicio_recurrente',
        'fecha_fin_recurrente',
        'lunes',
        'martes',
        'miercoles',
        'jueves',
        'viernes',
        'sabado',
        'domingo',
        'repetir_cada',
        'monitor_id',
        'torneo_id',
        'partido_id',
    ];

    public function pista()
    {
        return $this->belongsTo(Pistas::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function monitor()
    {
        return $this->belongsTo(Monitor::class);
    }

    public function torneo()
    {
        return $this->belongsTo(Torneos::class);
    }

    public function partido()
    {
        return $this->belongsTo(Partidos::class);
    }




}
