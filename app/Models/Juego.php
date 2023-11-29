<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Juego extends Model
{
    use HasFactory;
    protected $fillable = ['combinacion_real','combinacion_actual','intento','combinaciones','usuario','edad'];
}
