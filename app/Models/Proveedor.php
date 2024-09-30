<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use SoftDeletes;
    protected $table = "proveedores";

    protected $fillable = ['nombre', 'direccion', 'telefono', 'email'];

    public function gastos()
    {
        return $this->hasMany(Gastos::class);
    }
}
