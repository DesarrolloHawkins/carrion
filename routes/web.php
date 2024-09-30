<?php

use App\Http\Controllers\CajaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DeudaController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\ProveedorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();




Route::name('inicio')->get('/', function () {
    return view('auth.login');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'is.admin', 'prefix' => 'admin'], function () {

     // Clientes
     Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
     Route::get('/clientes-create', [ClienteController::class, 'create'])->name('clientes.create');
     Route::post('/clientes-store', [ClienteController::class, 'store'])->name('clientes.store');
     Route::get('/clientes-edit/{id}', [ClienteController::class, 'edit'])->name('clientes.edit');
     Route::post('/clientes/update/{id}', [ClienteController::class, 'update'])->name('clientes.update');
     Route::get('/clientes/delete/{id}', [ClienteController::class, 'destroy'])->name('clientes.delete');

     Route::get('/caja', [CajaController::class, 'index'])->name('caja.index');

     // Ingresos
     Route::get('/ingresos', [IngresoController::class, 'index'])->name('ingresos.index');
     Route::get('/ingresos/create', [IngresoController::class, 'create'])->name('ingresos.create');
     Route::post('/ingresos/store', [IngresoController::class, 'store'])->name('ingresos.store');
     Route::get('/ingresos/edit/{id}', [IngresoController::class, 'edit'])->name('ingresos.edit');
     Route::post('/ingresos/update/{id}', [IngresoController::class, 'update'])->name('ingresos.update');
     Route::get('/ingresos/delete/{id}', [IngresoController::class, 'destroy'])->name('ingresos.destroy');
     // Gastos
     Route::get('/gastos', [GastoController::class, 'index'])->name('gastos.index');
     Route::get('/gastos/create', [GastoController::class, 'create'])->name('gastos.create');
     Route::post('/gastos/store', [GastoController::class, 'store'])->name('gastos.store');
     Route::get('/gastos/edit/{id}', [GastoController::class, 'edit'])->name('gastos.edit');
     Route::post('/gastos/update/{id}', [GastoController::class, 'update'])->name('gastos.update');
     Route::get('/gastos/delete/{id}', [GastoController::class, 'destroy'])->name('gastos.destroy');
     // Proveedores
     Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');
     Route::get('/proveedores/create', [ProveedorController::class, 'create'])->name('proveedores.create');
     Route::post('/proveedores/store', [ProveedorController::class, 'store'])->name('proveedores.store');
     Route::get('/proveedores/edit/{id}', [ProveedorController::class, 'edit'])->name('proveedores.edit');
     Route::post('/proveedores/update/{id}', [ProveedorController::class, 'update'])->name('proveedores.update');
     Route::get('/proveedores/delete/{id}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');
     // Deudas
     Route::get('/deudas', [DeudaController::class, 'index'])->name('deudas.index');
     Route::get('/deudas/create', [DeudaController::class, 'create'])->name('deudas.create');
     Route::post('/deudas/store', [DeudaController::class, 'store'])->name('deudas.store');
     Route::get('/deudas/edit/{id}', [DeudaController::class, 'edit'])->name('deudas.edit');
     Route::post('/deudas/update/{id}', [DeudaController::class, 'update'])->name('deudas.update');
     Route::get('/deudas/delete/{id}', [DeudaController::class, 'destroy'])->name('deudas.destroy');
     Route::patch('deudas/{id}/pagar', [DeudaController::class, 'marcarComoPagada'])->name('deudas.marcarComoPagada');
     // Empresas
     Route::get('/empresa', [EmpresaController::class, 'index'])->name('empresas.index');
     Route::get('/empresa/create', [EmpresaController::class, 'create'])->name('empresas.create');
     Route::post('/empresa/store', [EmpresaController::class, 'store'])->name('empresas.store');
     Route::get('/empresa/edit/{id}', [EmpresaController::class, 'edit'])->name('empresas.edit');
     Route::post('/empresa/update/{id}', [EmpresaController::class, 'update'])->name('empresas.update');
     Route::get('/empresa/delete/{id}', [EmpresaController::class, 'destroy'])->name('empresas.destroy');

});
