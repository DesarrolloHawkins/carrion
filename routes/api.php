<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ReservasController;
use App\Http\Controllers\API\AuthClienteController;
use App\Http\Controllers\API\AuthAdminController;
use App\Http\Controllers\API\MapApiController;
use App\Http\Controllers\API\PayController;
use App\Http\Controllers\API\ClienteController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\WebhookController;
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
Route::post('/create-checkout-session', [StripePaymentController::class, 'createCheckoutSession']);
Route::post('/payout', [WebhookController::class, 'handleWebhook']);
Route::post('/registrar-pago', [StripePaymentController::class, 'registrarPago']);



Route::prefix('cliente')->group(function () {
    Route::post('/register', [AuthClienteController::class, 'register']);
    Route::post('/login', [AuthClienteController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthClienteController::class, 'logout']);
    Route::put('/update', [ClienteController::class, 'updateProfile']);
    Route::post('olvide-contrasenia', [AuthController::class, 'olvideContrasenia']);
    Route::post('password-restore', [AuthController::class, 'passwordRestore']);
});

Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthAdminController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthAdminController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('/reservas/cliente/{clienteId}', [ReservasController::class, 'getTotalReservasCliente']);

Route::get('/sillas', [MapApiController::class, 'getSillas']); // Obtener sillas con filtros
Route::get('/palcos/{id}/{zona}/{sector}', [MapApiController::class, 'getPalco']); // Obtener un palco específico
Route::get('/gradas/{id}/{zona}', [MapApiController::class, 'getGrada']); // Obtener una grada específica
Route::get('sillas/{id}/check', [MapApiController::class, 'checkSilla']);
Route::get('sillas/{id}', [MapApiController::class, 'getSilla']);
Route::post('/reservar-silla', [ReservasController::class, 'reservarSilla'])->middleware('auth:sanctum', 'admin');
Route::post('/confirmar-pago', [MapApiController::class, 'confirmarPago']);
Route::post('/reservar-temporal', [MapApiController::class, 'reservarTemporal']);
Route::post('/cancelar-reserva', [MapApiController::class, 'cancelarReserva']);
Route::post('/process-payment', [PayController::class, 'processPayment']);
Route::get('/cliente/sillas-disponibles/{clienteId}', [ReservasController::class, 'getSillasDisponibles']);
Route::get('/fecha-inicio-reservas', [ReservasController::class, 'getFechaInicioReservas']);
Route::get('/puedo-reservar/{clienteId}', [MapApiController::class, 'getPuedoReservar']);

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

    //Sillas

    //perfil



    // Añade otras rutas protegidas aquí
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
