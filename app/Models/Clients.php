<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;
    protected $table = "clients";

    protected $fillable = [

        'nameCliente',
        'taxNumber',
        'address',
        'ciudad',
        'province',
        'postCode',
        'nameEmpresa',
        'firstSurname',
        'lastSurname',
        'dni',
        'adressCliente',
        'ciudadCliente',
        'provinceCliente',
        'postCodeCliente',
        'tipoCliente'
    ];

    /**
     * Mutaciones de fecha.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];
}
