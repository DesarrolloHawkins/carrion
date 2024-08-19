<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PistaTamano extends Model
{
    use HasFactory;

    protected $table = 'pista_tamano';

    protected $fillable = ['nombre'];
}
