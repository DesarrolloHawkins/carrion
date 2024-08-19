<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Socios extends Model
{
    use HasFactory;

    protected $table = 'socios';

    protected $fillable = [
        'cliente_id',
        'membresia_id',
        'tarjeta',
        'estado'
    ];


    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function membresia()
    {
        return $this->belongsTo(Membresias::class, 'membresia_id');
    }


    public function cuotas()
    {
        return $this->hasMany(Cuotas::class, 'socio_id');
    }


}
