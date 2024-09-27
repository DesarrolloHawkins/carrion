<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusApp extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "status_app";
    protected $fillable = [
        'estado',
    ];


}
