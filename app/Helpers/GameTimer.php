<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class GameTimer
{
    /*
     metodo comenzar_juego almacena en la cahe una variable
    'start_time' que con la hora en que se inicio el juego
     la variable esta activa durante 120 minutos
    */
    public function comenzar_juego()
  {
    $start = now();
    Cache::put('start_time',$start);
    return $start;
  } 
    public function juego($id)
  {
   Cache::put('juego',$id);
  } 
    public function get_juego()
  {
    $juego = Cache::get('juego');
    if($juego)
    {
     
     return  $juego;
    }
    return false;
  } 
  
  public function ver_tiempo()
  {
    $startTime = Cache::get('start_time');
    if($startTime)
    {
     $transc = now()->diffInSeconds($startTime);
    return  $transc;
    }
    return false;
  }

}

?>