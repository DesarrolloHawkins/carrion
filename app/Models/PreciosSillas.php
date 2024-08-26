<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreciosSillas extends Model
{
    use HasFactory;

    protected $table = 'precios_sillas';

    protected $fillable = [
        'id_silla',
        'id_evento',
        'precio',
    ];

    public function sillas()
    {
        return $this->belongsTo(Sillas::class, 'id_silla');
    }

    public function eventos()
    {
        return $this->belongsTo(Eventos::class, 'id_evento');
    }
}
