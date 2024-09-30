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
        $perPage = $request->input('perPage', 10);

        // Inicializar sortColumn y sortDirection con valores por defecto
        $sortColumn = $request->input('sortColumn', 'nombre'); // 'nombre' es el valor por defecto
        $sortDirection = $request->input('sortDirection', 'asc'); // 'asc' es el valor por defecto

        // Query básica para obtener los clientes con filtros
        $clientes = Cliente::query();

        // Filtro por nombre, apellidos, o DNI
        if ($filtro) {
            $clientes->where(function($query) use ($filtro) {
                $query->where('nombre', 'like', "%{$filtro}%")
                    ->orWhere('apellidos', 'like', "%{$filtro}%")
                    ->orWhere('email', 'like', "%{$filtro}%")
                    ->orWhere('telefono', 'like', "%{$filtro}%")
                    ->orWhere('fecha_nacimiento', 'like', "%{$filtro}%");
            });
        }

        

        // Ordenar los resultados y paginarlos
        $clientes = $clientes->orderBy($sortColumn, $sortDirection)->paginate($perPage);

        return view('cliente.index', compact('clientes', 'filtro',  'sortDirection', 'sortColumn', 'perPage'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            // 'email' => 'required|string|email|max:255|unique:clientes',
            // 'telefono' => 'required|string|max:15',
            // 'fecha_nacimiento' => 'required|date',
            // 'genero' => 'required|string',
            // 'domicilio' => 'required|string|max:255',
            // 'ciudad' => 'required|string|max:255',
            // 'pais' => 'required|string|max:255',
        ]);

        // Crear nuevo cliente
        Cliente::create([
            'nombre' => $request->input('nombre'),
            'apellidos' => $request->input('apellidos'),
            'email' => $request->input('email'),
            'telefono' => $request->input('telefono'),
            'fecha_nacimiento' => $request->input('fecha_nacimiento'),
            'genero' => $request->input('genero'),
            'domicilio' => $request->input('domicilio'),
            'ciudad' => $request->input('ciudad'),
            // 'pais' => $request->input('pais'),
        ]);

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
        $cliente = Cliente::with('deudas')->findOrFail($id);
        return view('cliente.edit', compact('cliente'));
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
            // 'email' => 'required|string|email|max:255|unique:clientes,email,' . $id, // Asegura que el email sea único excepto para el cliente actual
            // 'telefono' => 'required|string|max:15',
            // 'fecha_nacimiento' => 'required|date',
            // 'genero' => 'required|string',
            // 'domicilio' => 'required|string|max:255',
            // 'ciudad' => 'required|string|max:255',
            // 'pais' => 'required|string|max:255',
        ]);
    
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
