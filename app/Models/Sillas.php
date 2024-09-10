<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sillas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sillas';


    protected $fillable = [
        'numero',
        'id_grada',
        'id_zona',
        'fila',
        'id_palco',
        
        
    ];

    public function grada()
    {
        return $this->belongsTo(Gradas::class, 'id_grada');
    }

    public function zona()
    {
        return $this->belongsTo(Zonas::class, 'id_zona');
    }

    public function reservas()
    {
        return $this->hasMany(Reservas::class, 'id_silla');
    }
    public function estaReservada($fecha)
{
    return $this->reservas()->where('estado', '!=', 'cancelada')->where('fecha', $fecha)->exists();
}

}
