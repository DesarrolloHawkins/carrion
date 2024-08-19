<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory;


    protected $table = "clientes";

    protected $fillable = [
        'email1',
        'nombre',
        'apellido',
        'tlf1',
        'calle',
        'genero',
        'fecha_nacimiento',
        'pais',
        'ciudad',
        'categoria_id',
        'nickName',
        'DNI',
        'telefono'
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
