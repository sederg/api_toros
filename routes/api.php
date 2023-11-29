<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JuegoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::resource('juego',JuegoController::class);
Route::post('/newgame',[JuegoController::class,'newgame']);
Route::post('/combinacion',[JuegoController::class,'proponercombinacion']);
Route::post('/eliminar_juego',[JuegoController::class,'eliminar_game']);
Route::post('/previo',[JuegoController::class,'previa']);

