<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PistaTipo extends Model
{
    use HasFactory;

    protected $table = 'pista_tipo';

    protected $fillable = ['nombre'];
}
