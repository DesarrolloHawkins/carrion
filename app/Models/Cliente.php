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
        'apellidos',
        'nombre',
        'direccion',
        'codigo_postal',
        'poblacion',
        'provincia',
        'fijo',
        'movil',
        'DNI',
        'email',
        'categoria_id',
        'password',
        'code'
    ];



    public function categoriaJugadores()
    {
        return $this->belongsTo(CategoriaJugadores::class, 'categoria_id');
    }



    public function socios()
    {
        return $this->hasOne(Socios::class, 'cliente_id');
    }



    /**
     * Mutaciones de fecha.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];
}
