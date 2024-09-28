<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Cliente;
use App\Models\Reservas;
use App\Mail\ReservaPagada;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class SendEmailsController  extends Controller
{


    public function obtenerClientesConExcesoDeReservas()
{
    // Array donde guardaremos los clientes y reservas que exceden el límite
    $clientesConExcesoReservas = [];

    // Obtener todas las reservas que tienen un order_id no nulo
    $reservasConOrder = Reservas::whereNotNull('order_id')->with('clientes')->get();

    // Agrupar las reservas por order_id
    $reservasAgrupadasPorOrder = $reservasConOrder->groupBy('order_id');

    // Recorrer cada grupo de reservas
    foreach ($reservasAgrupadasPorOrder as $order_id => $reservas) {
        // Obtener el primer cliente asociado a las reservas (ya que todas las reservas comparten el mismo cliente)
        $cliente = $reservas->first()->clientes;

        // Determinar el límite de sillas permitidas
        $limiteSillas = ($cliente->abonado && $cliente->tipo_abonado === 'palco') ? 8 : 4;

        // Contar cuántas sillas ha reservado el cliente para este order_id
        $cantidadSillas = $reservas->count();

        // Si excede el límite, añadir al array
        if ($cantidadSillas > $limiteSillas) {
            $clientesConExcesoReservas[] = [
                'cliente' => [
                    'id' => $cliente->id,
                    'nombre' => $cliente->nombre,
                    'apellidos' => $cliente->apellidos,
                    'abonado' => $cliente->abonado,
                    'tipo_abonado' => $cliente->tipo_abonado,
                    'order_id' => $order_id, // Incluir el order_id en la información del cliente
                ],
                'reservas' => $reservas->pluck('id')->toArray(), // Solo agregar los IDs de las reservas
            ];
        }
    }

    // Devolver el array de clientes con reservas que exceden el límite
    return $clientesConExcesoReservas;
}

    public function sendEmails()
    {

    // Obtener los clientes con reservas pagadas, cuyo email_sent sea false
    $clientesConReservasPagadas = Cliente::whereHas('reservas', function($query) {
        $query->where('estado', 'pagada');
    })
    ->with(['reservas' => function($query) {
        $query->where('estado', 'pagada')->with('silla'); // Cargar las sillas junto con las reservas pagadas
    }])
    ->where('email_sent', false) // Solo clientes que no han recibido el correo
    ->whereNotNull('email') // Asegurarse de que el email no sea null
    ->get();

    // Enviar los correos y actualizar la columna 'email_sent'
    foreach ($clientesConReservasPagadas as $cliente) {
        // Obtener las reservas pagadas del cliente
        $reservas = $cliente->reservas;

        // Obtener las sillas asociadas a las reservas pagadas
        $sillas = $reservas->map(function($reserva) {
            return $reserva->silla;
        });

        try {
            // Enviar el correo al cliente
            Mail::to($cliente->email)->send(new ReservaPagada($reservas, $sillas, $cliente));
            // Mail::to('ivan.mayol@hawkins.es')->send(new ReservaPagada($reservas, $sillas, $cliente));

            // Actualizar la columna 'email_sent' a true después de enviar el correo
            $cliente->email_sent = true;
            $cliente->save();

        } catch (\Exception $e) {
            // Manejar cualquier error durante el envío del correo
            Log::error("Error al enviar el correo al cliente ID {$cliente->id}: " . $e->getMessage());
        }
    }

    return response()->json(['message' => 'Emails enviados y estado actualizado.']);
}


}
