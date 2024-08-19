<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zonas extends Model
{
    use HasFactory;

    protected $table = 'zonas';

    protected $fillable = [
        'nombre',
        'max_capacidad'
    ];

    public function membresias()
{
    return $this->belongsToMany(Membresias::class, 'membresia_zona');
}
}
