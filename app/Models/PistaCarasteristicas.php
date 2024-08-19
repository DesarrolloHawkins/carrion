<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PistaCarasteristicas extends Model
{
    use HasFactory;

    protected $table = 'pista_caracteristicas';

    protected $fillable = ['nombre'];
}
