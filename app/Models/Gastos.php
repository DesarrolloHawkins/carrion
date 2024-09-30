<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gastos extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "gastos";

    protected $fillable = [
        'cliente_id',
        'concepto',
        'precio',
        'fecha'
    ];

    /**
     * Mutaciones de fecha.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

     // Relación con Cliente
     public function cliente()
     {
         return $this->belongsTo(Cliente::class);
     }

     public function proveedor()
     {
         return $this->belongsTo(Proveedor::class);
     }
}
