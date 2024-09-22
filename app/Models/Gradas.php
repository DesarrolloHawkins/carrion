<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gradas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gradas';


    protected $fillable = [
        'numero',
        'id_zona',

    ];


    public function zonas()
    {
        return $this->belongsTo(Zonas::class, 'id_zona');
    }

    public function sillas()
    {
        return $this->hasMany(Sillas::class, 'id_grada');
    }


}
