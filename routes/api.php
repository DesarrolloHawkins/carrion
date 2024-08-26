<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ReservasController;
use App\Http\Controllers\API\AuthClienteController;
use App\Http\Controllers\API\AuthAdminController;
use App\Http\Controllers\API\MapApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::prefix('cliente')->group(function () {
    Route::post('/register', [AuthClienteController::class, 'register']);
    Route::post('/login', [AuthClienteController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthClienteController::class, 'logout']);
});

Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthAdminController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthAdminController::class, 'logout']);
});

Route::get('/sillas', [MapApiController::class, 'getSillas']); // Obtener sillas con filtros
Route::get('/palcos/{id}/{zona}/{sector}', [MapApiController::class, 'getPalco']); // Obtener un palco específico
Route::get('/gradas/{id}/{zona}', [MapApiController::class, 'getGrada']); // Obtener una grada específica

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    //reservas
    Route::apiResource('reservas', App\Http\Controllers\API\ReservasController::class);

    //clientes
    Route::apiResource('clientes', App\Http\Controllers\API\ClienteController::class);

    //Socios
    Route::apiResource('socios', App\Http\Controllers\API\SociosController::class);

    //Pistas
    Route::apiResource('pistas', App\Http\Controllers\API\PistasController::class);

    //Torneos
    Route::apiResource('torneos', App\Http\Controllers\API\TorneosController::class);

    //Monitores
    Route::apiResource('monitores', App\Http\Controllers\API\MonitoresController::class);

    //Settings
    Route::apiResource('settings', App\Http\Controllers\API\SettingsController::class);

    //Clubes
    Route::apiResource('clubes', App\Http\Controllers\API\ClubController::class);



    // Añade otras rutas protegidas aquí
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
