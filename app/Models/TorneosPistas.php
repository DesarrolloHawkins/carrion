<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TorneosPistas extends Model
{
    use HasFactory;

    protected $table = 'torneos_pistas';

    protected $fillable = [
        'torneo_id',
        'pista_id'
    ];

    public function torneo()
    {
        return $this->belongsTo(Torneos::class);
    }

}
