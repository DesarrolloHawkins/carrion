<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deuda extends Model
{
    use SoftDeletes;
    protected $table = "deudas";

    protected $fillable = ['cliente_id', 'concepto', 'cantidad', 'fecha', 'pagada', 'fecha_pago'];

    // Relación con cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    
}

