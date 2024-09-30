<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use SoftDeletes;
    protected $table = "empresas";

    protected $fillable = [
        'nombre', 'telefono', 'email', 'direccion', 'cif', 'cod_postal', 'localidad', 'pais', 
        'legal1', 'legal2', 'legal3', 'legal4'
    ];

    // Relación con saldos iniciales
    public function saldosIniciales()
    {
        return $this->hasMany(SaldoInicial::class);
    }
}
