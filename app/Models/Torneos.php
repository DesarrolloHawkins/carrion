<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//softModel
use Illuminate\Database\Eloquent\SoftDeletes;


class Torneos extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'torneos';

    protected $fillable = [
        'inscripcion',
        'nombre',
        'descripcion',
        'imagen',
        'normativa',
        'precio',
        'precio_socio',
        'precio_pronto_pago',
        'precio_socio_pronto_pago',
        'condiciones'
    ];


  
    public function categorias()
    {

        return $this->hasMany('App\Models\TorneosCategorias' , 'torneo_id');

    }
 // Relación para obtener las inscripciones de los jugadores a través de las categorías del torneo
 public function inscripciones()
 {
     return $this->hasManyThrough(
         TorneosCategoriasInscripciones::class, // Modelo de destino
         TorneosCategorias::class,              // Modelo intermedio
         'torneo_id',                           // Clave foránea en el modelo intermedio (TorneosCategorias)
         'torneo_categoria_id',                 // Clave foránea en el modelo de destino (TorneosCategoriasInscripciones)
         'id',                                  // Clave local en el modelo actual (Torneos)
         'id'                                   // Clave local en el modelo intermedio (TorneosCategorias)
     );
 }

 // Relación para obtener los jugadores (clientes) a través de las inscripciones
 public function jugadores()
 {
     return $this->hasManyThrough(
         Cliente::class,                        // Modelo de destino
         TorneosCategoriasInscripciones::class, // Modelo intermedio
         'torneo_categoria_id',                 // Clave foránea en el modelo intermedio (TorneosCategoriasInscripciones)
         'id',                                  // Clave foránea en el modelo de destino (Cliente)
         'id',                                  // Clave local en el modelo actual (Torneos)
         'jugador_id'                           // Clave local en el modelo intermedio (TorneosCategoriasInscripciones)
     );
 }

 public function dias()
 {
     return $this->hasMany('App\Models\TorneosDias' , 'torneo_id');
 }

    public function pistas()
    {
        return $this->hasMany('App\Models\TorneosPistas' , 'torneo_id');
    }

    public function clubes()
    {
        return $this->hasMany(TorneosClubs::class, 'torneo_id');    }

   

   




}



