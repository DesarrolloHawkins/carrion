<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartidoResultados extends Model
{
    use HasFactory;

    protected $table = 'partido_resultados';

    protected $fillable = [
        'partido_id',
        'torneo_id',
        'duo_1_id',
        'duo_2_id',
        'winner_id',
        'duo_1_wins',
        'duo_2_wins'

        
    ];

    public function partido()
    {
        return $this->belongsTo(Partidos::class);
    }
    
    public function torneo()
    {
        return $this->belongsTo(Torneos::class);
    }

    public function duo1()
    {
        return $this->belongsTo(TorneosDuos::class, 'duo_1_id');
    }

    public function duo2()
    {
        return $this->belongsTo(TorneosDuos::class, 'duo_2_id');
    }

    public function winner()
    {
        return $this->belongsTo(TorneosDuos::class, 'winner_id');
    }
    public function sets()
{
    return $this->hasMany(PartidoResultadoSets::class, 'partido_resultado_id');
}

}
