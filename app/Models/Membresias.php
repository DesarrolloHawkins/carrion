<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membresias extends Model
{
    use HasFactory;

    protected $table = 'membresias';

    protected $fillable = [
        'nombre',
        'anotaciones'
    ];

    public function zonas()
    {
        return $this->belongsToMany(Zonas::class, 'membresia_zona');
    }
    
}
