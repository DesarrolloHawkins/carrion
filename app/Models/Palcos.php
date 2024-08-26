<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Palcos extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'palcos';


    protected $fillable = [
        'num_sillas',
        'id_zona',
        'id_sector',
        'ext_prop',
        'numero',
        'coordenada_x',
        'coordenada_y',
    ];

    public function zonas()
    {
        return $this->belongsTo(Zonas::class, 'id_zona');
    }


    public function sectores()
    {
        return $this->belongsTo(Sectores::class, 'id_sector');
    }

    //sillas
    public function sillas()
    {
        return $this->hasMany(Sillas::class, 'id_palco');
    }
}
