<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Reservas;
use App\Models\Sillas;
use App\Models\Zonas;
use App\Models\Gradas;
use App\Models\Palcos;
use App\Models\Sectores;


class ReservasController extends Controller
{
    



    //index
    public function index()
    {
        return view('reservas.index');
    }


    public function show($clienteId)
{
    // Buscar al cliente por su ID
    $cliente = Cliente::find($clienteId);

    // Si el cliente no existe, redirigir con error
    if (!$cliente) {
        return redirect()->back()->with('error', 'Cliente no encontrado.');
    }

    // Obtener todas las reservas del cliente
    $reservas = Reservas::where('id_cliente', $clienteId)->get();

    // Inicializar array para los detalles de las reservas
    $detallesReservas = [];

    // Recorrer las reservas para obtener los detalles de cada una
    foreach ($reservas as $reserva) {
        // Buscar la silla asociada a la reserva
        $silla = Sillas::find($reserva->id_silla);
        
        // Buscar la zona correspondiente a la silla
        $zona = Zonas::find($silla->id_zona);
        
        // Verificar si la silla pertenece a un palco o una grada
        $palco = null;
        $grada = null;

        if ($silla->id_palco) {
            $palco = Palcos::find($silla->id_palco);
            $zona = Sectores::find($palco->id_sector); // Asegurarse de obtener el sector del palco
        } elseif ($silla->id_grada) {
            $grada = Gradas::find($silla->id_grada);
            $zona = Zonas::find($grada->id_zona);
        }

        // Añadir los detalles de la reserva al array
        $detallesReservas[] = [
            'asiento' => $silla->numero ?? 'N/A',
            'sector' => $zona->nombre ?? 'N/A',
            'fecha' => $reserva->fecha,
            'año' => $reserva->año,
            'precio' => $reserva->precio,
            'fila' => $silla->fila ?? 'N/A',
            'order' => $reserva->order,
            'palco' => $palco ? $palco->numero : '',
            'grada' => $grada ? $grada->numero : '',
            'estado' => ucfirst($reserva->estado)
        ];
    }

    // Retornar la vista con los detalles de las reservas
    return view('reservas.show', compact('detallesReservas', 'cliente'));
}

   

}
