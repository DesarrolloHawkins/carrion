<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TorneosClubs extends Model
{
    use HasFactory;

    protected $table = 'torneos_clubs';

    protected $fillable = [
        'torneo_id',
        'club_id'
    ];

    public function torneo()
    {
        return $this->belongsTo(Torneos::class, 'torneo_id');
    }

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }
}
