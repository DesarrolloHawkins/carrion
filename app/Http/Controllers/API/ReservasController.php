<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservas;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use App\Models\Sillas;
use App\Models\Sectores;
use App\Models\Gradas;
use App\Models\Order;
use App\Models\Palcos;
use App\Models\Zonas;
use Carbon\Carbon;

class ReservasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //reservas with all relations
        $reservas = Reservas::with('pista', 'cliente', 'monitor', 'torneo', 'partido')->get();

        return response()->json($reservas);
    }
    public function getSillasReservadas($idCliente)
    {
        $reservas = Reservas::where('id_cliente', $idCliente)->where('estado','pagada')->get();
        $data = [];
        if (count($reservas) > 0) {
            foreach ($reservas as $reserva) {
                array_push($data, $reserva->id_silla);
            }
        }
        return response()->json($data);
    }

    public function getReservasCliente($clienteId)
    {
        // Contamos cuántas sillas ha reservado este cliente
        $sillasReservadas = Reservas::where('id_cliente', $clienteId)
                                    ->where('estado', 'pagada')
                                    ->count();

        return response()->json([
            'reservadas' => $sillasReservadas
        ]);
    }

    public function getZonasOcupadas($zonaId){
        $zonas = [];
        $sectores = [];
        if($zonaId == 1){
            $zonas = ['Arenal I', 'Arenal II', 'Arenal III' ,  'Arenal IV' , 'Arenal V'];
        }else if($zonaId == 2){
            $sectores = ['Lancería-Gallo Azul', 'Lancería-Gallo Azul I'];
        }else if($zonaId == 3){
            $sectores = ['Algarve-Plaza del Banco I', 'Algarve-Plaza del Banco II'];
        }else if($zonaId == 4){
            $sectores = ['Grada I', 'Grada II', 'Grada III' ,  'Grada IV' , 'Grada V'];
        }



    }

    public function getTotalReservasCliente($clienteId)
{
    // Obtener las reservas del cliente
    $reservas = Reservas::where('id_cliente', $clienteId)
                        ->where('estado', 'pagada')
                        ->get();

    // Inicializar arrays y variables
    $detallesReservas = [];
    $zonas = [];
    $palco = null;
    $grada = null;

    // Recorrer cada reserva para obtener detalles adicionales
    foreach ($reservas as $reserva) {
        $silla = Sillas::find($reserva->id_silla);
        $zona = null;

        if ($silla->id_palco != null) {
            $palco = Palcos::find($silla->id_palco);
            $zona = Sectores::find($palco->id_sector);
        } elseif ($silla->id_grada != null) {
            $grada = Gradas::find($silla->id_grada);
            $zona = Zonas::find($grada->id_zona);
        } else {
            $zona = Zonas::find($silla->id_zona);
        }

        $detallesReservas[] = [
            'asiento' => $silla->numero ?? 'N/A',
            'sector' => $zona->nombre ?? 'N/A',
            'fecha' => $reserva->fecha,
            'año' => $reserva->año,
            'precio' => $reserva->precio,
            'fila' => $silla->fila ?? 'N/A',
            'order' => $reserva->order,
            'palco' => $palco->numero ?? '',
            'grada' => $grada->numero ?? '',
        ];
    }

    // Obtener detalles de la primera reserva para incluir en la respuesta
    $reserva1 = $reservas->first();
    $silla = Sillas::find($reserva1->id_silla);
    $zona = null;

    if ($silla->id_palco != null) {
        $palco = Palcos::find($silla->id_palco);
        $zona = Zonas::find($palco->id_sector);
    } elseif ($silla->id_grada != null) {
        $grada = Gradas::find($silla->id_grada);
        $zona = Zonas::find($grada->id_zona);
    } else {
        $zona = Zonas::find($silla->id_zona);
    }

    return response()->json([
        'reservas' => $detallesReservas,
        'cliente' => Cliente::find($clienteId),
        'zona' => $zona,
        'palco' => $palco,
        'grada' => $grada
    ]);
}
    public function getSillasDisponibles($clienteId)
    {
        // Obtener el cliente por su ID
        $cliente = Cliente::find($clienteId);

        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        // Verificamos el tipo de abonado
        $maxSillasPermitidas = ($cliente->abonado && $cliente->tipo_abonado === 'palco' ) ? 8 : 4;

        // Contamos cuántas sillas ha reservado el cliente
        $sillasReservadas = Reservas::where('id_cliente', $clienteId)
            ->where('estado', 'pagada') // Excluir reservas canceladas
            ->count();

        // Calculamos cuántas sillas puede reservar aún
        $sillasDisponibles = $maxSillasPermitidas - $sillasReservadas;

        return response()->json([
            'maxSillasPermitidas' => $maxSillasPermitidas,
            'sillasReservadas' => $sillasReservadas,
            'sillasDisponibles' => $sillasDisponibles,
        ]);
    }
    public function getFechaInicioReservas()
    {
        return response()->json([
            'fechaInicioReservas' => env('FECHA_INICIO_RESERVAS')
        ]);
    }

    public function reservarSilla(Request $request)
    {
        $sillaId = $request->input('silla_id');
        $clienteId = $request->input('cliente_id');
        $estado = $request->input('estado');
        $fecha = $request->input('fecha');  // La fecha para la cual se está reservando la silla

        try {
            DB::transaction(function () use ($sillaId, $clienteId, $fecha, $estado, $request) {  // Aquí incluimos $request
                // Buscamos la silla con un bloqueo pesimista
                $silla = Sillas::where('id', $sillaId)->lockForUpdate()->first();

                // Verificar si la silla ya está reservada en esa fecha
                if ($silla->estaReservada($fecha)) {
                    throw new \Exception('La silla ya está reservada.');
                }

                // Si la silla no está reservada, creamos la reserva
                Reservas::create([
                    'id_cliente' => $clienteId,
                    'id_silla' => $sillaId,
                    'fecha' => $fecha,
                    'año' => date('Y', strtotime($fecha)),
                    'id_evento' => $request->input('evento_id'),
                    'precio' => $request->input('precio'),
                    'estado' => $estado,
                ]);
            });

            return response()->json(['message' => 'Silla reservada correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 409);
        }
    }

    public function reservarSillaTemporal(Request $request)
{
    $sillasSeleccionadas = $request->input('sillas'); // Array de sillas seleccionadas (pueden ser solo IDs)
    $clienteId = $request->input('cliente_id');
    $estado = $request->input('estado');
    $fecha = Carbon::now()->toDateString();  // La fecha para la cual se están reservando las sillas, sin la hora
    $eventoId = $request->input('evento_id');
    $totalEnviar = 0;
    $orderId = null; // Inicializamos la variable para almacenar el ID de la orden

    try {
        DB::transaction(function () use ($sillasSeleccionadas, $clienteId, $fecha, $estado, $eventoId, &$totalEnviar, &$orderId) { // Pasamos $totalEnviar y $orderId por referencia

            // Verificar si ya existe una orden pendiente para el cliente
            $order = Order::firstOrCreate(
                ['cliente_id' => $clienteId, 'status' => 'pending'], // Crea una orden solo si no hay una "pendiente"
                ['total' => 0] // Inicializamos la orden con total 0
            );

            $orderId = $order->id; // Guardamos el ID de la orden

            $totalReserva = 0;

            foreach ($sillasSeleccionadas as $sillaSeleccionada) {
                // Si las sillas seleccionadas son simplemente IDs:
                $sillaId = is_array($sillaSeleccionada) ? $sillaSeleccionada['id'] : $sillaSeleccionada;

                // Bloqueo pesimista para asegurarnos de que la silla no esté siendo reservada simultáneamente
                $silla = Sillas::where('id', $sillaId)->lockForUpdate()->first();

                // Verificar si la silla ya está reservada en esa fecha
                if ($silla->estaReservada($fecha)) {
                    throw new \Exception('La silla ya está reservada.');
                }

                // Calcular el precio de la silla
                $precio = $this->calcularPrecio($silla);

                // Crear la reserva y asociarla a la orden
                Reservas::create([
                    'id_cliente' => $clienteId,
                    'id_silla' => $sillaId,
                    'fecha' => $fecha,
                    'estado' => 'reservada',
                    'año' => date('Y', strtotime($fecha)),
                    'id_evento' => $eventoId,
                    'precio' => $precio,
                    'order_id' => $order->id, // Asociamos la reserva con la orden
                ]);

                // Sumar el precio de la silla al total de la reserva
                $totalReserva += $precio;
            }

            // Actualizar el total de la orden
            $order->total += $totalReserva;
            $order->save();

            // Actualizamos $totalEnviar con el total de la reserva
            $totalEnviar += $totalReserva;
        });

        // Devolver el total calculado correctamente y el ID de la orden
        return response()->json(['totalAmount' => $totalEnviar, 'order' => $orderId], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 409);
    }
}




    public function calcularPrecio($silla)
    {
        $numeroFila = intval(substr($silla->fila, 1));

        if($silla->id_grada){

            $precio = \DB::table('precios_sillas')
            ->where('tipo_asiento', 'grada')
            ->where(function ($query) use ($numeroFila) {
                $query->where('fila_inicio', '<=', $numeroFila)
                    ->where(function ($query) use ($numeroFila) {
                        $query->where('fila_fin', '>=', $numeroFila)
                            ->orWhereNull('fila_fin');
                    });
            })
            ->value('precio');

            return $precio ?: 12;
        }else{
            $palcoIds = [
                16, 17, 18, 19, 20,21,22,23,24,25,26,27,28,122,121,120,119,118,117,116,115,114,113,112,111,110,109,108,220,221,222,223,224,225,226,227,228,229,230,231,232,233,234,
                534, 533, 532, 531, 530, 529, 528, 527, 526, 525, 524, 523, 522, 521, 520, 519, 518, 517, 516, 515, 514, 513, 512, 511, 510, 509, 508, 507, 506, 505, 504, 503, 502, 501, 500, 499, 498, 497, 496, 495, 494, 493,

            ];

            $palco = Palcos::find($silla->id_palco);

            if ($palco) {
                if (in_array($palco->numero, $palcoIds)) {
                    return 18;
                } else {
                    return 20;
                }
            }
        }
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
            $reserva = Reservas::find($id);

            return response()->json($reserva);
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
