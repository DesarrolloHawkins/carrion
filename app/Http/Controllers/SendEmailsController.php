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
