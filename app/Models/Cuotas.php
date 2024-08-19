<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuotas extends Model
{
    use HasFactory;

    protected $table = 'cuotas';

    protected $fillable = [
        'socio_id',
        'fecha_inicio',
        'fecha_fin',
        'precio',
        'pagado',
        'fecha_pago',
        'metodo_pago'
    ];
}
