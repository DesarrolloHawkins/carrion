<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //clientes with all relations
        $clientes = Cliente::with('categoriaJugadores', 'socios')->get();
        return response()->json($clientes);
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
        $clientes = Cliente::find($id);

        return response()->json($clientes);
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

    public function updateProfile(Request $request)
{
    $user = Auth::user(); // Obtiene el usuario autenticado

    // Validación de datos
    $rules = [
        'nombre' => 'nullable|string|max:255',
        'apellidos' => 'nullable|string|max:255',
        'dni' => 'nullable|string|max:20',
        'movil' => 'nullable|string|max:15',
        'correo' => 'nullable|email|max:255',
        'password' => 'nullable|string|min:6|confirmed',
    ];

    // Solo aplica la validación de password_confirmation si se proporciona un password
    if ($request->filled('password')) {
        $rules['password_confirmation'] = 'required_with:password|same:password';
    }

    $request->validate($rules);

    // Actualizar datos del usuario
    if ($request->has('nombre')) {
        $user->nombre = $request->input('nombre');
    }
    if ($request->has('apellidos')) {
        $user->apellidos = $request->input('apellidos');
    }
    if ($request->has('dni')) {
        $user->dni = $request->input('dni');
    }
    if ($request->has('telefono')) {
        $user->movil = $request->input('telefono');
    }
    if ($request->has('correo')) {
        $user->email = $request->input('correo');
    }
    if ($request->filled('password')) {
        // Solo actualizar la contraseña si se proporciona
        $user->password = Hash::make($request->input('password'));
    }

    $user->save();

    return response()->json(['message' => 'Perfil actualizado con éxito.']);
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
