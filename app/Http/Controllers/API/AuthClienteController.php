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
            'email' => 'required|string|email|max:255|unique:clientes',
            'password' => 'required|string|min:8',
            'dni' => 'required|string|max:20|unique:clientes',
        ]);

        $cliente = Cliente::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'DNI' => $request->dni,
            'password' => Hash::make($request->password),
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

        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }
}
