<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Especifica los campos que pueden ser rellenados mediante asignación masiva
    protected $fillable = ['cliente_id', 'payment_order_id', 'total', 'status'];

    /**
     * Relación uno a muchos con la tabla `reservas`.
     * Una orden puede tener varias reservas asociadas.
     */
    public function reservas()
    {
        return $this->hasMany(Reservas::class, 'order_id');
    }

    /**
     * Relación con el cliente (si tienes una tabla `clientes`).
     * Una orden pertenece a un cliente.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Devuelve el estado de la orden de manera legible.
     * Por ejemplo: 'pending', 'paid', 'failed'
     */
    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 'paid':
                return 'Pagada';
            case 'failed':
                return 'Fallida';
            case 'pending':
            default:
                return 'Pendiente';
        }
    }

    /**
     * Calcula el monto total de la orden sumando las reservas asociadas.
     * (Esto es útil si no quieres almacenar el total en la base de datos)
     */
    public function calcularTotal()
    {
        return $this->reservas->sum('precio');
    }
}
