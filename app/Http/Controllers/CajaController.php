<?php

namespace App\Http\Controllers;

use App\Models\Gastos;
use App\Models\Ingresos;
use App\Models\SaldoInicial;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
     public function index(Request $request)
{
    // Obtener el saldo inicial del año actual
    $año = date('Y');
    $saldoInicial = SaldoInicial::where('año', $año)->first()->saldo_inicial ?? 0;

    // Obtener los filtros de fecha y tipo de transacción
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $tipo = $request->input('tipo', 'todo'); // 'ingresos', 'gastos', o 'todo'

    // Obtener ingresos y gastos, aplicar filtro de fechas si está presente
    $ingresos = Ingresos::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
        return $query->whereBetween('fecha', [$startDate, $endDate]);
    })->get();

    $gastos = Gastos::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
        return $query->whereBetween('fecha', [$startDate, $endDate]);
    })->get();

    // Unificar ingresos y gastos en un array
    $transacciones = [];

    foreach ($ingresos as $ingreso) {
        $transacciones[] = [
            'fecha' => $ingreso->fecha,
            'concepto' => $ingreso->concepto,
            'debe' => $ingreso->precio,   // Ingreso se suma al saldo
            'haber' => null,              // No afecta el haber
        ];
    }

    foreach ($gastos as $gasto) {
        $transacciones[] = [
            'fecha' => $gasto->fecha,
            'concepto' => $gasto->concepto,
            'debe' => null,               // No afecta el debe
            'haber' => $gasto->precio,    // Gasto se resta del saldo
        ];
    }

    // Ordenar las transacciones por fecha
    usort($transacciones, function($a, $b) {
        return strcmp($a['fecha'], $b['fecha']);
    });

    // Calcular el saldo acumulado, comenzando con el saldo inicial
    $saldo = $saldoInicial;

    // Crear un array para las transacciones con el saldo acumulado
    $transaccionesConSaldo = [];
    foreach ($transacciones as $transaccion) {
        // Sumar ingresos (debe) y restar gastos (haber)
        if ($transaccion['debe']) {
            $saldo += $transaccion['debe'];
        } elseif ($transaccion['haber']) {
            $saldo -= $transaccion['haber'];
        }

        // Agregar el saldo calculado a la transacción
        $transaccionesConSaldo[] = array_merge($transaccion, ['saldo' => $saldo]);
    }

    return view('caja.index', compact('transaccionesConSaldo', 'saldoInicial', 'startDate', 'endDate', 'tipo'));
}

     

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createGasto()
    {
        return view('caja.create-gasto');

    }

    public function createIngreso()
    {
        return view('caja.create-ingreso');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('caja.edit', compact('id'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
