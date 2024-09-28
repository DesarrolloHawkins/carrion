<?php

namespace App\Http\Controllers;

use App\Exports\ClienteExport;
use App\Models\Client;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Filtros para buscar clientes
        $filtro = $request->input('filtro');
        $abonado = $request->input('abonado');  // Aquí puede ser '1' o null
        $tipo_abonado = $request->input('tipo_abonado'); // Aquí puede ser 'palco', 'silla' o null
        $perPage = $request->input('perPage', 10);

        // Inicializar sortColumn y sortDirection con valores por defecto
        $sortColumn = $request->input('sortColumn', 'nombre'); // 'nombre' es el valor por defecto
        $sortDirection = $request->input('sortDirection', 'asc'); // 'asc' es el valor por defecto

        // Posibles valores para tipo_abonado
        $tiposAbonado = [
            'palco',
            'silla'
        ];

        // Query básica para obtener los clientes con filtros
        $clientes = Cliente::query();

        // Filtro por nombre, apellidos, o DNI
        if ($filtro) {
            $clientes->where(function($query) use ($filtro) {
                $query->where('nombre', 'like', "%{$filtro}%")
                    ->orWhere('apellidos', 'like', "%{$filtro}%")
                    ->orWhere('DNI', 'like', "%{$filtro}%");
            });
        }

        // Filtro por abonado (1 o null)
        if ($abonado !== null) {
            $clientes->where('abonado', $abonado);
        }

        // Filtro por tipo_abonado (palco, silla o null)
        if ($tipo_abonado) {
            $clientes->where('tipo_abonado', $tipo_abonado);
        }

        // Ordenar los resultados y paginarlos
        $clientes = $clientes->orderBy($sortColumn, $sortDirection)->paginate($perPage);

        return view('cliente.index', compact('clientes', 'filtro', 'abonado', 'tipo_abonado', 'sortDirection', 'sortColumn', 'tiposAbonado', 'perPage'));
    }




    public function export(Request $request)
    {
        return Excel::download(new ClienteExport($request->all()), 'reservas.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cliente.create');

    }
    public function createFromBudget()
    {
        //
        return view('cliente.create-from-budget');

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los datos del cliente
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'DNI' => 'required|string|max:15|unique:clientes,DNI',
            'movil' => 'required|string|max:15',
            'fijo' => 'nullable|string|max:15',
            'email' => 'required|email|max:255|unique:clientes,email',
            'abonado' => 'nullable|in:1,0',
            'tipo_abonado' => 'nullable|string|in:palco,silla',
        ]);

        // Si el campo 'abonado' no viene marcado, se asegura que sea 0 (No abonado)
        $validatedData['abonado'] = $request->has('abonado') ? 1 : 0;

        // Crear el cliente en la base de datos
        Cliente::create($validatedData);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
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
        $cliente = Cliente::find($id);
       // Obtener los registros de envío de correos
        $emailLogs = $cliente->emailLogs()->orderBy('created_at', 'desc')->get();

        return view('cliente.edit', compact('cliente', 'emailLogs'));


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
    // Validar los datos del cliente
    $validatedData = $request->validate([
        'nombre' => 'required|string|max:255',
        'apellidos' => 'required|string|max:255',
        'DNI' => 'required|string|max:15|unique:clientes,DNI,' . $id,
        'movil' => 'required|string|max:15',
        'fijo' => 'nullable|string|max:15',
        'email' => 'required|email|max:255|unique:clientes,email,' . $id,
        'abonado' => 'nullable|boolean',
        'tipo_abonado' => 'nullable|string|in:palco,silla',
    ]);

    // Si el campo 'abonado' no viene marcado, se asegura que sea 0 (No abonado)
    $validatedData['abonado'] = $request->has('abonado') ? 1 : 0;

    // Buscar el cliente por su id y actualizarlo
    $cliente = Cliente::findOrFail($id);
    $cliente->update($validatedData);

    return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
{
    // Buscar el cliente por su ID
    $cliente = Cliente::findOrFail($id);

    // Eliminar el cliente
    $cliente->delete();

    // Redirigir a la lista de clientes con un mensaje de éxito
    return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
}

}
