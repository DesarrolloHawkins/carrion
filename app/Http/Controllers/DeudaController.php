<?php

namespace App\Http\Controllers;

use App\Models\Deuda;
use App\Models\Cliente;
use Illuminate\Http\Request;

class DeudaController extends Controller
{
    public function index()
    {
        $deudas = Deuda::with('cliente')->paginate(10);
        return view('deudas.index', compact('deudas'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        return view('deudas.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string|max:255',
            'cantidad' => 'required|numeric',
            'fecha' => 'required|date',
        ]);

        Deuda::create($validatedData);

        return redirect()->route('deudas.index')->with('success', 'Deuda creada exitosamente.');
    }

    public function edit($id)
    {
        $deuda = Deuda::findOrFail($id);
        $clientes = Cliente::all();
        return view('deudas.edit', compact('deuda', 'clientes'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string|max:255',
            'cantidad' => 'required|numeric',
            'fecha' => 'required|date',
        ]);

        $deuda = Deuda::findOrFail($id);
        $deuda->update($validatedData);

        return redirect()->route('deudas.index')->with('success', 'Deuda actualizada exitosamente.');
    }

    public function marcarComoPagada($id)
    {
        $deuda = Deuda::findOrFail($id);
        $deuda->pagada = true;
        $deuda->fecha_pago = now(); // Registrar cuándo se pagó
        $deuda->save();

        return redirect()->route('deudas.index')->with('success', 'Deuda marcada como pagada.');
    }

    public function destroy($id)
    {
        $deuda = Deuda::findOrFail($id);
        $deuda->delete();

        return redirect()->route('deudas.index')->with('success', 'Deuda eliminada exitosamente.');
    }
}

