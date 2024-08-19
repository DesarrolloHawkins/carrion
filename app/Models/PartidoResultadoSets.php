<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartidoResultadoSets extends Model
{
    use HasFactory;

    protected $table = 'partido_resultados_sets';

    protected $fillable = [
        'partido_resultado_id',
        'set_number',
        'duo_1_score',
        'duo_2_score',
        'duo_1_id',
        'duo_2_id',


    ];


    public function partidoResultado()
    {
        return $this->belongsTo(PartidoResultados::class);
    }

    public function duo1()
    {
        return $this->belongsTo(TorneosDuos::class, 'duo_1_id');
    }

    public function duo2()
    {
        return $this->belongsTo(TorneosDuos::class, 'duo_2_id');
    }


}
