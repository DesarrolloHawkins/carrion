<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SectoresZonas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sectores_zonas';


    protected $fillable = [
        'id_sector',
        'id_zona',
    ];

    public function sectores()
    {
        return $this->belongsTo(Sectores::class, 'id_sector');
    }


    public function zonas()
    {
        return $this->belongsTo(Zonas::class, 'id_zona');
    }
}
