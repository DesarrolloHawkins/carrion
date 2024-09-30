<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoInicial extends Model
{
    use HasFactory;

    protected $table = "saldos_iniciales";

    protected $fillable = ['saldo_inicial', 'año'];

}
