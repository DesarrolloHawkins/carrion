<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservaPago extends Model
{
    use HasFactory;

    protected $table = 'reserva_pago';

    protected $fillable = [
        'reserva_id',
        'monto',
        'tipo_pago',
        'fecha_pago',
        'hora_pago',
        'nota',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reservas::class);
    }
}
