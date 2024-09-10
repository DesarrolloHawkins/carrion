<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservas;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

use App\Models\Sillas;

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
            $reserva = Reserva::find($id);
    
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
