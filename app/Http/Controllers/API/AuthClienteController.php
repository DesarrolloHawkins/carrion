<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthClienteController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'dni' => 'required|string|max:20',
            'movil' => 'nullable|string|max:15',
            'fijo' => 'nullable|string|max:15',
            'apellidos' => 'nullable|string|max:255',
        ]);

        // Comprobación si existe por DNI
        $cliente = Cliente::where('DNI', $request->dni)->first();

        if (!$cliente) {
            // Si no existe por DNI, buscar por teléfono o email
            $cliente = Cliente::where(function($query) use ($request) {
                $query->where('email', $request->email)
                    ->Where('movil', $request->movil)
                    ->Where('fijo', $request->fijo);
            })->first();
        }

        if ($cliente) {
            // Si el cliente ya existe, actualizamos su email y password
            $cliente->update([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'message' => 'Cliente ya existente, se han actualizado sus datos.',
                'cliente' => $cliente
            ], 200);
        }

        // Si no existe, crear un nuevo cliente
        $cliente = Cliente::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'DNI' => $request->dni,
            'movil' => $request->movil,
            'fijo' => $request->fijo,
            'password' => Hash::make($request->password),
            'apellidos' => $request->apellidos,
        ]);

        return response()->json(['cliente' => $cliente], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    
        $cliente = Cliente::where('email', $request->email)->first();
    
        if (!$cliente || !Hash::check($request->password, $cliente->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        $token = $cliente->createToken('cliente-token')->plainTextToken;
    
        // Devolvemos el token junto con la información del cliente
        return response()->json([
            'token' => $token,
            'cliente' => [
                'id' => $cliente->id,
                'nombre' => $cliente->nombre,
                'email' => $cliente->email,
                'dni' => $cliente->DNI,
                'movil' => $cliente->movil,
                'fijo' => $cliente->fijo,
                'apellidos' => $cliente->apellidos,
                'abonado' => $cliente->abonado,
                'tipo_abonado' => $cliente->tipo_abonado,
            ]
        ], 200);
    }
    

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }
}
