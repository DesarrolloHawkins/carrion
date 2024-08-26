<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evento extends Model
{
    use HasFactory;


    protected $table = "eventos";

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',

        'created_at',
        'updated_at',
        'deleted_at',

    ];

    public function reservas()
    {
        return $this->hasMany(Reservas::class, 'id_evento');
    }

    public function pistas()
    {
        return $this->belongsToMany(Pistas::class, 'pistas_eventos', 'id_evento', 'id_pista');
    }

    
   
}
