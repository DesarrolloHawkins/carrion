<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthClienteController extends Controller
{
    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'nombre' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255',
    //         'password' => 'required|string|min:8',
    //         'dni' => 'required|string|max:20',
    //         'movil' => 'nullable|string|max:15',
    //         'fijo' => 'nullable|string|max:15',
    //         'apellidos' => 'nullable|string|max:255',
    //     ]);

    //     // Comprobación si existe por DNI
    //     $cliente = Cliente::where('DNI', $request->dni)->first();

    //           //si ya hay un cliente con ese email, devolver un error
    //           if (Cliente::where('email', $request->email)->exists()) {
    //             return response()->json(['error' => 'Ya existe un cliente con ese email'], 400);
    //         }

    //     if (!$cliente) {
    //         // Si no existe por DNI, buscar por teléfono o email
    //         $cliente = Cliente::where(function($query) use ($request) {
    //             $query->where('email', $request->email)
    //                 ->Where('movil', $request->movil)
    //                 ->Where('fijo', $request->fijo);
    //         })->first();
    //     }

    //     if ($cliente) {
    //         // Si el cliente ya existe, actualizamos su email y password
    //         $cliente->update([
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password),
    //         ]);

    //         return response()->json([
    //             'message' => 'Cliente ya existente, se han actualizado sus datos.',
    //             'cliente' => $cliente
    //         ], 200);
    //     }


    //     // Si no existe, crear un nuevo cliente
    //     $cliente = Cliente::create([
    //         'nombre' => $request->nombre,
    //         'email' => $request->email,
    //         'DNI' => $request->dni,
    //         'movil' => $request->movil,
    //         'fijo' => $request->fijo,
    //         'password' => Hash::make($request->password),
    //         'apellidos' => $request->apellidos,
    //     ]);

    //     return response()->json(['cliente' => $cliente], 201);
    // }


    public function register(Request $request)
	{
		try {
			
			// Comprobación si existe por DNI
			$cliente = Cliente::where('DNI', $request->dni)->first();

			// Si ya hay un cliente con ese email, devolver un error
			if (Cliente::where('email', $request->email)->exists()) {
				return response()->json(['error' => 'Ya existe un cliente con ese email'], 400);
			}

			if (!$cliente) {
				
				// Si no existe por DNI, buscar coincidencias aproximadas en otros campos
				$posiblesClientes = Cliente::where('apellidos', 'like', '%' . $request->apellidos . '%')
					->orWhere('email', 'like', '%' . $request->email . '%')
					// ->orWhere('apellidos', 'like', '%' . $request->apellidos . '%')
					->get();
                    
				if ($posiblesClientes->isNotEmpty()) {
					
					// Llamar a la API de ChatGPT para analizar las coincidencias
					$coincidencias = $posiblesClientes->map(function($cliente) {
						return [
							'id' => $cliente->id,
							'nombre' => $cliente->nombre,
							'email' => $cliente->email,
							'movil' => $cliente->movil,
							'fijo' => $cliente->fijo,
							'dni' => $cliente->DNI,
							'apellidos' => $cliente->apellidos ?? 'N/A', // Usar 'N/A' si no existe apellidos

						];
					});

				   // Petición a ChatGPT
					$respuestaChatGPT = $this->consultarChatGPT($coincidencias, $request->all());

					// Decodificar la respuesta JSON devuelta por ChatGPT
					$respuestaDecodificada = json_decode($respuestaChatGPT, true);

					// Verificar si la respuesta contiene coincidencia y ID
					if (isset($respuestaDecodificada['coincidendia']) && $respuestaDecodificada['coincidendia'] == true) {
						$id = $respuestaDecodificada['id'];
						$cliente = Cliente::find($id);

						$cliente->nombre = $request->nombre;
						$cliente->email = $request->email;
						$cliente->DNI = $request->dni;
						$cliente->movil = $request->movil;
						$cliente->fijo = $request->fijo;
						$cliente->password = Hash::make($request->password);
						$cliente->apellidos = $request->apellidos;
						$cliente->save();

						return response()->json(['cliente' => $cliente], 201);
					}
					 // Crear un nuevo cliente si no existe ningún registro similar
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
				}else {
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
			}else {
				$cliente->nombre = $request->nombre;
				$cliente->email = $request->email;
				// $cliente->DNI = $request->dni;
				$cliente->movil = $request->movil;
				$cliente->fijo = $request->fijo;
				$cliente->password = Hash::make($request->password);
				$cliente->apellidos = $request->apellidos;
				$cliente->save();
				return response()->json('Cliente guardado correctamente:',200);

			}
		} catch (\Throwable $th) {
			Log::error(['Error al registrar: '.$th]);
			return response()->json('ERROR: '.$th,500);
		}

}

// Función para realizar la petición a ChatGPT
private function consultarChatGPT($coincidencias, $nuevosDatos)
{
    // Estructura de datos para enviar a ChatGPT
    $messages = [
        [
            'role' => 'system',
            'content' => 'Eres un asistente que ayuda a asociar nuevos clientes con clientes existentes. Intenta asociar el DNI y si no coincide o no existe  intenta comparar el nombre y apellido. No seas estricto si coincide alguna nombre o apellido bastante cercano. Si no coincide nada compara apellidos no dejes pasar, si falla una letra en el dni puede que sea ese usuario.'
        ],
        [
            'role' => 'user',
            'content' => "Estos son los posibles clientes encontrados:\n" .
                collect($coincidencias)->map(function($cliente) {
                    return "- Cliente: Nombre: {{$cliente['id']}}, {$cliente['nombre']}, Email: {$cliente['email']}, Apellidos: {$cliente['apellidos']}, DNI: {$cliente['dni']}";
                })->implode("\n") .
                "\n\nLos nuevos datos son: Nombre: {$nuevosDatos['nombre']}, Email: {$nuevosDatos['email']}, Apellidos: {$nuevosDatos['apellidos']}, Movil: {$nuevosDatos['movil']}, DNI: {$nuevosDatos['dni']}\n" .
                "¿A qué cliente existente deberíamos asociar estos nuevos datos o deberíamos crear un nuevo registro? Dame tu respuesta siempre en un formato JSON de esta forma: {'coincidendia': true o false, 'id': id del usuario si coincide.}"
        ]
    ];
    // return $messages;
    // Aquí llamas a la API de OpenAI usando cURL o cualquier otro método para hacer una petición HTTP
    $apiKey = env('OPENAI_API_KEY'); // Asegúrate de tener tu clave API configurada
    $client = new \GuzzleHttp\Client();
    $response = $client->post('https://api.openai.com/v1/chat/completions', [
        'headers' => [
            'Authorization' => "Bearer $apiKey",
            'Content-Type' => 'application/json',
        ],
        'json' => [
            'model' => 'gpt-4',
            'messages' => $messages,
            'max_tokens' => 150,
        ]
    ]);
    $result = json_decode($response->getBody(), true);
    
    return $result['choices'][0]['message']['content'];
    // return $result['choices'][0]['message']['content'];
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
