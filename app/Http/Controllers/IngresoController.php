<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Models\Ingresos;
use Illuminate\Http\Request;

class IngresoController extends Controller
{
    public function index(Request $request)
    {
        $ingresos = Ingresos::paginate(10);
        return view('ingresos.index', compact('ingresos'));
    }

    public function create()
    {
        return view('ingresos.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'concepto' => 'nullable|string|max:255',
            'precio' => 'nullable|numeric',
            'fecha' => 'nullable|date',
        ]);

        Ingresos::create($validatedData);

        return redirect()->route('ingresos.index')->with('success', 'Ingreso creado exitosamente.');
    }

    public function edit($id)
    {
        $ingreso = Ingresos::findOrFail($id);
        return view('ingresos.edit', compact('ingreso'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'concepto' => 'nullable|string|max:255',
            'precio' => 'nullable|numeric',
            'fecha' => 'nullable|date',
        ]);

        $ingreso = Ingresos::findOrFail($id);
        $ingreso->update($validatedData);

        return redirect()->route('ingresos.index')->with('success', 'Ingreso actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $ingreso = Ingresos::findOrFail($id);
        $ingreso->delete();

        return redirect()->route('ingresos.index')->with('success', 'Ingreso eliminado exitosamente.');
    }
}
