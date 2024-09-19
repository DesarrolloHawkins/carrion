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
        
        'id_cliente',
        'id_silla',
        'fecha',
        'año',
        'id_evento',
        'precio',
        'estado',
        'order',
        'transaction',
        'metodo_pago',
        'isInvitado',
        'isCRM',
        'procesando'

    ];


    public function sillas()
    {
        return $this->belongsTo(Sillas::class, 'id_silla');
    }

    public function clientes()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function eventos()
    {
        return $this->belongsTo(Eventos::class, 'id_evento');
    }

    




}
