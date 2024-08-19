<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TorneosDias extends Model
{
    use HasFactory;

    protected $table = 'torneos_dias';

    protected $fillable = [
        'torneo_id',
        'dia',
        'hora_inicio',
        'hora_fin',
        'created_at',
        'updated_at'
    ];

    public function torneo()
    {
        return $this->belongsTo(Torneos::class, 'torneo_id');
    }

    


}
