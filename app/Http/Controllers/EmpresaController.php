<?php
namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\SaldoInicial;
use App\Models\SaldoInicialEmpresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::paginate(10);

        // Obtener el saldo inicial para el año actual
        $añoActual = date('Y');
        $saldoInicial = SaldoInicial::where('año', $añoActual)->first();

        return view('empresas.index', compact('empresas', 'saldoInicial'));
    }


    public function create()
    {
        return view('empresas.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'nullable|string|max:255',
            'telefono' => 'nullable|integer',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
            'cif' => 'nullable|string|max:20',
            'cod_postal' => 'nullable|string|max:10',
            'localidad' => 'nullable|string|max:255',
            'pais' => 'nullable|string|max:255',
            'legal1' => 'nullable|string',
            'legal2' => 'nullable|string',
            'legal3' => 'nullable|string',
            'legal4' => 'nullable|string',
            'saldo_inicial' => 'nullable|numeric',
            'año' => 'nullable|integer',
        ]);

        // Crear la empresa
        $empresa = Empresa::create($validatedData);

        // Guardar el saldo inicial por año si ambos están presentes
        if ($request->filled('saldo_inicial') && $request->filled('año')) {
            SaldoInicial::updateOrCreate(
                ['año' => $request->input('año')],
                ['saldo_inicial' => $request->input('saldo_inicial')]
            );
        }

        return redirect()->route('empresas.index')->with('success', 'Empresa creada exitosamente.');
    }


    public function edit($id)
    {
        $empresa = Empresa::with('saldosIniciales')->findOrFail($id);
        return view('empresas.edit', compact('empresa'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nombre' => 'nullable|string|max:255',
            'telefono' => 'nullable|integer',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
            'cif' => 'nullable|string|max:20',
            'cod_postal' => 'nullable|string|max:10',
            'localidad' => 'nullable|string|max:255',
            'pais' => 'nullable|string|max:255',
            'legal1' => 'nullable|string',
            'legal2' => 'nullable|string',
            'legal3' => 'nullable|string',
            'legal4' => 'nullable|string',
            'saldo_inicial' => 'nullable|numeric',
            'año' => 'nullable|integer',
        ]);

        // Actualizar la empresa
        $empresa = Empresa::findOrFail($id);
        $empresa->update($validatedData);

        // Actualizar o crear el saldo inicial por año
        if ($request->filled('saldo_inicial') && $request->filled('año')) {
            SaldoInicial::updateOrCreate(
                ['año' => $request->input('año')],
                ['saldo_inicial' => $request->input('saldo_inicial')]
            );
        }

        return redirect()->route('empresas.index')->with('success', 'Empresa actualizada exitosamente.');
    }
}
