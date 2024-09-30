<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;
    use HasApiTokens;


    protected $table = "clientes";

    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'telefono',
        'fecha_nacimiento',
        'genero',
        'domicilio',
        'ciudad',
        'pais',
    ];

    /**
     * Mutaciones de fecha.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];
    // Relación con Deuda
    public function deudas()
    {
        return $this->hasMany(Deuda::class);
    }
}
