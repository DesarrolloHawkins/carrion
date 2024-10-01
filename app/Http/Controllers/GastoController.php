<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\Cliente;
use App\Models\Gastos;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class GastoController extends Controller
{
    public function index(Request $request)
    {
        $gastos = Gastos::with('cliente')->paginate(10);
        return view('gastos.index', compact('gastos'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $proveedores = Proveedor::all();

        return view('gastos.create', compact('clientes','proveedores'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'proveedor_id' => 'nullable|exists:clientes,id',
            'concepto' => 'string|max:255',
            'precio' => 'numeric',
            'fecha' => 'date',
        ]);

        Gastos::create($validatedData);

        return redirect()->route('gastos.index')->with('success', 'Gasto creado exitosamente.');
    }

    public function edit($id)
    {
        $gasto = Gastos::findOrFail($id);
        $clientes = Cliente::all();
        $proveedores = Proveedor::all();
        return view('gastos.edit', compact('gasto', 'clientes', 'proveedores'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'proveedor_id' => 'nullable|exists:clientes,id',
            'concepto' => 'string|max:255',
            'precio' => 'numeric',
            'fecha' => 'date',
        ]);

        $gasto = Gastos::findOrFail($id);
        $gasto->update($validatedData);

        return redirect()->route('gastos.index')->with('success', 'Gasto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $gasto = Gastos::findOrFail($id);
        $gasto->delete();

        return redirect()->route('gastos.index')->with('success', 'Gasto eliminado exitosamente.');
    }
}

