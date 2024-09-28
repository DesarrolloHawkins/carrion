<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cliente;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservaPagada; // Asegúrate de tener la clase Mail correcta
use App\Models\EmailLog;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class EnviarCorreos extends Command
{
    protected $signature = 'reservas:send-mails';
    protected $description = 'Envía correos a clientes con reservas pagadas cuyo email aún no ha sido enviado.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('Comando de envío de correos iniciado.');

        // Subconsulta para seleccionar clientes que no tienen un EmailLog registrado
        $clientesConReservasPagadasQuery = Cliente::whereHas('reservas', function ($query) {
            $query->where('estado', 'pagada');
        })
        ->with(['reservas' => function ($query) {
            $query->where('estado', 'pagada')->with('silla');
        }])
        ->whereNotNull('email')
        ->whereDoesntHave('emailLogs'); // Asegurarse de que no tienen un emailLog

        Log::info('Clientes pendientes de correo: ' . $clientesConReservasPagadasQuery->count());

        $clientesConReservasPagadasQuery->chunk(50, function ($clientesConReservasPagadas) {

            foreach ($clientesConReservasPagadas as $cliente) {
                $reservas = $cliente->reservas;
                $sillas = $reservas->map(function($reserva) {
                    return $reserva->silla;
                });
                $order = Order::where('cliente_id', $cliente->id)->first(); // Obtener el order_id asociado

                $existeEmailLog = EmailLog::where('cliente_id', $cliente->id)
                ->where('order_id', $order->id)
                ->exists();
                // Solo enviar correo si no existe un EmailLog previo
                if (!$existeEmailLog) {
                    try {
                        Mail::to($cliente->email)->send(new ReservaPagada($reservas, $sillas, $cliente));

                        $emailContent = view('emails.reserva_pagada', ['reservas' => $reservas, 'sillas' => $sillas, 'cliente' => $cliente])->render();
                        // Registrar el envío en email_logs
                        EmailLog::create([
                            'cliente_id' => $cliente->id,
                            'order_id' => $order->id,
                            'email_content' => $emailContent,
                            'email_sent' => true,
                            'response' => 'Enviado exitosamente' // Puedes ajustar según la respuesta real del servidor de correo
                        ]);

                        $cliente->email_sent = true;
                        $cliente->save();
                        $this->info("Correo enviado a {$cliente->email}.");
                    } catch (\Exception $e) {
                        Log::error("Error al enviar correo al cliente ID {$cliente->id}: " . $e->getMessage());
                        $this->error("Error al enviar correo al cliente ID {$cliente->id}: " . $e->getMessage());

                        // Registrar el fallo en email_logs
                        EmailLog::create([
                            'cliente_id' => $cliente->id,
                            'order_id' => $order->id,
                            'email_content' => '', // No hay contenido en caso de fallo
                            'email_sent' => false,
                            'response' => $e->getMessage()
                        ]);
                    }

                } else {
                    $this->info("El cliente ID {$cliente->id} ya tiene un registro en EmailLog, no se enviará el correo.");
                }
            }
        });
        $this->info('Proceso de envío de correos finalizado.');
    }
}
