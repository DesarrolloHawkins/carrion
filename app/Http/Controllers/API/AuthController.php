<?php

namespace App\Http\Controllers\API;

use App\Models\User;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CodigoVerificacionMail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['user' => $user, 'token' => $user->createToken('API Token')->plainTextToken]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function olvideContrasenia(Request $request)
    {
        if (!isset($request->email)) {
            return response()->json(['error' => 'Email no enviado'], 401);
        }

        $email = $request->email;
        $cliente = Cliente::where('email', $email)->first();

        if (!$cliente) {
            return response()->json(['error' => 'Email no corresponde con ningún usuario'], 401);
        }

        // Generar un código de verificación de 6 dígitos
        $codigo = rand(100000, 999999);
        $cliente->code = $codigo;
        $cliente->save();

        // Enviar el correo con el código de verificación
        Mail::to($cliente->email)->send(new CodigoVerificacionMail($codigo));

        return response()->json(['message' => 'Correo de verificación enviado correctamente']);
    }

    public function passwordRestore(Request $request)
    {
        if (!isset($request->password) && !isset($request->email)) {
            return response()->json(['error' => 'La contraseña no enviada'], 401);
        }
        $email = $request->email;
        $password = $request->password;
        $cliente = Cliente::where('email', $email)->first();
        if (!$cliente) {
            return response()->json(['error' => 'Email no corresponde con ningun usuario'], 401);

        }
        $cliente->password = Hash::make($password);
        $cliente->save();

        return response()->json(['message' => 'Contraseña actualizada correctamente']);
    }
}
