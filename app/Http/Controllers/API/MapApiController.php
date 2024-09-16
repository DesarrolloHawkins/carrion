<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Zonas;
use App\Models\Sectores;
use App\Models\Palcos;
use App\Models\Sillas;
use App\Models\Gradas;
use App\Models\Reservas;
use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservaPagada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapApiController extends Controller
{

    public function confirmarPago(Request $request)
    {
        
        $merchantParams = base64_decode($request->input('Ds_MerchantParameters'));
        $expectedSignature = $request->input('Ds_Signature');
        
        // Verificar la firma (como lo tienes implementado previamente)
        $claveSecreta = 'CLAVE_SECRETA';
        $key = base64_decode($claveSecreta);
        $generatedSignature = base64_encode(hash_hmac('sha256', $request->input('Ds_MerchantParameters'), $key, true));
    
        if ($generatedSignature === $expectedSignature) {
            $params = json_decode($merchantParams, true);
            $orderId = $params['Ds_Order'];
    
            DB::beginTransaction();
            try {
                // Busca las reservas asociadas a ese order
                $reservas = Reservas::where('order', $orderId)->get();
    
                if ($reservas->isEmpty()) {
                    throw new \Exception("No se encontraron reservas con ese pedido.");
                }
    
                // Actualiza el estado de las reservas a 'pagada'
                foreach ($reservas as $reserva) {
                    $reserva->estado = 'pagada';
                    $reserva->save();
                }
                // Enviar el correo al cliente
                $cliente = $reservas->first()->cliente;
                dd($cliente);
                Mail::to($cliente->email)->send(new ReservaPagada($reservas));
                DB::commit();
                return response()->json(['message' => 'Pago confirmado y reservas actualizadas', 'status' => 'success'], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['message' => $e->getMessage(), 'status' => 'error'], 400);
            }
        } else {
           
        
            return response()->json(['message' => 'La firma no es válida. Reservas canceladas.', 'status' => 'error'], 400);
        }
    }

    public function cancelarReserva(Request $request)
    {
        $orderId = $request->input('order_id');
        
        DB::beginTransaction();
        try {
            $reservas = Reservas::where('order', $orderId)->get();

            if ($reservas->isEmpty()) {
                throw new \Exception("No se encontraron reservas con ese pedido.");
            }

            // Actualiza el estado de las reservas a 'cancelada'
            foreach ($reservas as $reserva) {
                $reserva->estado = 'cancelada';
                $reserva->save();
            }

            DB::commit();
            return response()->json(['message' => 'Reservas canceladas con éxito', 'status' => 'success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage(), 'status' => 'error'], 400);
        }
    }


    

public function reservarTemporal(Request $request)
{
    $sillas = $request->input('sillas');  // IDs de las sillas seleccionadas
    $clienteId = $request->input('cliente_id');
    $fecha = $request->input('fecha');
    $orderId = $request->input('order');  // Recibir el orderId generado en el frontend

    $cliente = Cliente::find($clienteId);


    $fechaActual = Carbon::now();
    $fechaInicioReservas = Carbon::parse(env('FECHA_INICIO_RESERVAS')); 

    if ($fechaActual->lt($fechaInicioReservas) && !$cliente->abonado) {
        return response()->json(['error' => 'Las reservas no están disponibles aún para usuarios no abonados'], 400);
    }


    if (!$cliente) {
        return response()->json(['error' => 'Cliente no encontrado'], 404);
    }

    //si el cliente es abonado y el tipo de abonado es palco, puede reservar hasta 8 sillas
    $maxSillasPermitidas = ($cliente->abonado && $cliente->tipo_abonado === 'palco') ? 8 : 4;


    DB::beginTransaction();

    if(count($sillas) > $maxSillasPermitidas){
        DB::rollBack();
        return response()->json(['error' => 'No puedes reservar más de ' . $maxSillasPermitidas . ' sillas'], 400);
    }

    try {
        foreach ($sillas as $sillaId) {
            // Verifica si la silla ya está reservada
            $silla = Sillas::findOrFail($sillaId);
            $reservaExistente = Reservas::where('id_silla', $sillaId)
                                        ->whereIn('estado', ['reservada', 'pagada'])
                                        ->first();

            //si la silla es de grada
            if(!$silla->id_palco){
                $filaNumero = (int) filter_var($silla->fila, FILTER_SANITIZE_NUMBER_INT);
                $precio = DB::table('precios_sillas')
                    ->where('tipo_asiento', 'grada')
                    ->where('fila_inicio', '<=', $filaNumero)
                    ->where('fila_fin', '>=', $filaNumero)
                    ->first();

                if (!$precio) {
                    $precio = DB::table('precios_sillas')
                        ->where('tipo_asiento', 'grada')
                        ->where('fila_inicio', '<=', $filaNumero)
                        ->whereNull('fila_fin')
                        ->first();
                }
            }else{
                $palcoIds = [205, 206, 207, 208, 209, 210, 211, 212,213,214,215,216,217,218,219, 451, 452, 453, 454, 455, 456, 457, 458, 459, 460, 461, 462, 463, 464, 465, 466,

                467, 468, 469,470,471,472,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492
                
                ];
                $precio = DB::table('precios_sillas')
                    ->where('tipo_asiento', 'palco')
                    ->first();

                $palco = Palcos::find($silla->id_palco);


                if($palco){
                    if (in_array( $palco->numero, $palcoIds)) {
                        $precio = (object) ['precio' => 20];
                    }
                }
                
            }

            if ($reservaExistente) {
                throw new \Exception("La silla {$sillaId} ya está reservada.");
            }

            if(!$precio){

            }

            // Crear la reserva en estado 'reservada' con el orderId pasado
            Reservas::create([
                'id_cliente' => $clienteId,
                'id_silla' => $sillaId,
                'fecha' => $fecha,
                'año' => date('Y', strtotime($fecha)),
                'id_evento' => 1,
                'precio' => $precio ? $precio->precio : 12,
                'estado' => 'reservada',
                'order' => $orderId  // Usar el orderId recibido en lugar de generar uno nuevo
            ]);
        }

        DB::commit();
        return response()->json(['message' => 'Sillas reservadas temporalmente', 'order' => $orderId], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 400);
    }
}



    public function simularRespuestaRedsys(Request $request)
{
    $merchantParameters = base64_encode(json_encode([
        'Ds_MerchantCode' => '999008881', 
        'Ds_Order' => '012345678',
        'Ds_Amount' => '5000', // Simulando 50,00€
        'Ds_Currency' => '978',
        'Ds_Response' => '0000', // Código de éxito
        'Ds_SignatureVersion' => 'HMAC_SHA256_V1'
    ]));

    // Genera una firma de ejemplo para simular Redsys
    $signature = base64_encode(hash_hmac('sha256', $merchantParameters, base64_decode('CLAVE_SECRETA'), true));

    return response()->json([
        'Ds_Signature' => $signature,
        'Ds_MerchantParameters' => $merchantParameters,
        'Ds_SignatureVersion' => 'HMAC_SHA256_V1',
    ]);
}


    //function que te dice si puedes reservar o no dependiendo de la fecha
    public function getPuedoReservar($clienteId)
    {
        try {
            // Obtener el cliente por su ID
            $cliente = Cliente::find($clienteId);

            if (!$cliente) {
                return response()->json(['error' => 'Cliente no encontrado'], 404);
            }

            // Fecha actual
            $fechaActual = Carbon::now();
            // Obtener la fecha de inicio de reservas desde el archivo de configuración (o directamente en el código)
            $fechaInicioReservas = Carbon::parse(env('FECHA_INICIO_RESERVAS', '2024-10-01')); // Definir una fecha de inicio

            // Comprobar si el cliente no es abonado y si la fecha actual es anterior a la fecha de inicio de reservas
            if ($fechaActual->lt($fechaInicioReservas) && !$cliente->abonado) {
                return response()->json([
                    'error' => 'No puedes reservar aún',
                    'success' => $fechaInicioReservas->toDateString() // Devolver la fecha de inicio de reservas
                ], 400);
            }

            // Si es abonado o la fecha de inicio ya ha pasado, puede reservar
            return response()->json([
                'success' => 'Puedes reservar',
                'fechaInicioReservas' => $fechaInicioReservas->toDateString()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        
       
    }
    

    public function checkSilla(Request $request, $id)
    {
        // Verificar si la silla existe
        $silla = Sillas::find($id);

        if (!$silla) {
            return response()->json(['error' => 'Silla no encontrada'], 404);
        }

        // Verificar si hay una reserva para la silla
        $reserva = Reservas::where('id_silla', $id)
            ->whereIn('estado', ['pagada', 'reservada'])
            ->first();

        $reservada = $reserva !== null;

        return response()->json([
            'id' => $silla->id,
            'numero' => $silla->numero,
            'reservada' => $reservada
        ]);
    }


    // Obtener información de las sillas con filtros opcionales
    public function getSillas(Request $request)
    {
        $query = Sillas::query();

        // Filtrar por zona
        if ($request->has('zona')) {
            $zona = Zonas::where('nombre', $request->zona)->first();
            if ($zona) {
                $query->where('id_zona', $zona->id);
            } else {
                return response()->json(['error' => 'Zona no encontrada'], 404);
            }
        }

        // Filtrar por sector
        if ($request->has('sector')) {
            $sector = Sectores::where('nombre', $request->sector)->first();
            if ($sector) {
                $query->where('id_sector', $sector->id);
            } else {
                return response()->json(['error' => 'Sector no encontrado'], 404);
            }
        }

        // Filtrar por número de palco
        if ($request->has('palco')) {
            $palco = Palcos::where('numero', $request->palco)->first();
            if ($palco) {
                $query->where('id_palco', $palco->id);
            } else {
                return response()->json(['error' => 'Palco no encontrado'], 404);
            }
        }

        // Filtrar por número de grada
        if ($request->has('grada')) {
            $grada = Gradas::where('id', $request->grada)->first();
            if ($grada) {
                $query->where('id_grada', $grada->id);
            } else {
                return response()->json(['error' => 'Grada no encontrada'], 404);
            }
        }

        // Obtener los resultados paginados
        if($request->has('per_page')){
            $perPage = $request->get('per_page');
            $sillas = $query->paginate($perPage);
        } else {
            
            $sillas = $query->get();

        }

        // Obtener el estado de reserva para cada silla
        $sillas->each(function ($silla) {
            $silla->reservada = Reservas::where('id_silla', $silla->id)
                ->whereIn('estado', ['pagada', 'reservada'])
                ->exists();
        });

        // Ajusta el número de elementos por página según sea necesario

        return response()->json($sillas);
    }


    public function getSilla($id)
    {
        $silla = Sillas::find($id);
        //zona 
        $zona = Zonas::find($silla->id_zona);
        //palco
        $palco = Palcos::find($silla->id_palco);
        
        if($palco){
            $sector = Sectores::find($palco->id_sector);
        }else{
            $sector = null;
        }

        // $precio = DB::table('precios_sillas')
        //     ->where('tipo_asiento', 'palco')
        //     ->where('fila_inicio', '<=', $silla->fila)
        //     ->where('fila_fin', '>=', $silla->fila)
        //     ->first();

        // Extraer el número de fila de la cadena (e.g., 'F1' -> 1)
        $filaNumero = (int) filter_var($silla->fila, FILTER_SANITIZE_NUMBER_INT);

        if ($palco) {
            $palcoIds = [205, 206, 207, 208, 209, 210, 211, 212,213,214,215,216,217,218,219, 451, 452, 453, 454, 455, 456, 457, 458, 459, 460, 461, 462, 463, 464, 465, 466,

            467, 468, 469,470,471,472,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492
            
            ];

           


            $precio = DB::table('precios_sillas')
                ->where('tipo_asiento', 'palco')
                ->first();
                
             if (in_array($palco->numero, $palcoIds)) {
                $precio = (object) ['precio' => 20];
                // return response()->json([
                  
                //     'precio' => $palco->numero
                // ]);
                
             }

        } else {
            $precio = DB::table('precios_sillas')
                ->where('tipo_asiento', 'grada')
                ->where('fila_inicio', '<=', $filaNumero)
                ->where('fila_fin', '>=', $filaNumero)
                ->first();

            if (!$precio) {
                $precio = DB::table('precios_sillas')
                    ->where('tipo_asiento', 'grada')
                    ->where('fila_inicio', '<=', $filaNumero)
                    ->whereNull('fila_fin')
                    ->first();
            }
        }




        if(!$precio){
           //tengo que pasar precio->precio = 12
            $precio = (object) ['precio' => 12];
        }



        //return silla con zona y palco, donde zona es el objeto zona y palco es el objeto palco
        return response()->json([
            'silla' => $silla,
            'zona' => $zona,
            'palco' => $palco,
            'sector' => $sector,
            'precio' => $precio
        ]);




        
    }


    // Obtener información de un palco específico
    public function getPalco($id, $zona, $sector)
    {
        $zonaModel = Zonas::where('nombre', $zona)->first();
        if (!$zonaModel) {
            return response()->json(['error' => 'Zona no encontrada'], 404);
        }

        $sectorModel = Sectores::where('nombre', $sector)
                               ->where('id_zona', $zonaModel->id)
                               ->first();
        if (!$sectorModel) {
            return response()->json(['error' => 'Sector no encontrado'], 404);
        }

        $palco = Palcos::where('id_zona', $zonaModel->id)
                        ->where('id_sector', $sectorModel->id)
                        ->where('numero', $id)
                        ->first();
        if (!$palco) {
            return response()->json(['error' => 'Palco no encontrado'], 404);
        }

        return response()->json($palco);
    }

    // Obtener información de una grada específica
    public function getGrada($id, $zona)
    {
        $zonaModel = Zonas::where('nombre', $zona)->first();
        if (!$zonaModel) {
            return response()->json(['error' => 'Zona no encontrada'], 404);
        }

        $grada = Gradas::where('id_zona', $zonaModel->id)
                        ->where('numero', $id)
                        ->first();
        if (!$grada) {
            return response()->json(['error' => 'Grada no encontrada'], 404);
        }

        return response()->json($grada);
    }
}
