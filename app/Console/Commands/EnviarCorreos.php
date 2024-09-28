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
        $clientesSinEmailLogsQuery = Cliente::whereHas('reservas', function ($query) {
            $query->where('estado', 'pagada')
                  ->whereNotNull('order_id'); // Añadir condición de que el order_id no sea null
        })
        ->with(['reservas' => function ($query) {
            $query->where('estado', 'pagada')
                  ->whereNotNull('order_id') // Añadir condición de que el order_id no sea null
                  ->with('silla');
        }])
        ->whereNotNull('email')
        ->whereDoesntHave('emailLogs'); // Solo los que no tienen EmailLog

        $totalClientesSinEmailLog = $clientesSinEmailLogsQuery->count();
        Log::info('Clientes sin EmailLogs: ' . $totalClientesSinEmailLog);

        // Proceso Fase 1: Enviar correos a clientes que no tienen EmailLog
        $clientesSinEmailLogsQuery->chunk(10, function ($clientesSinEmailLogs) {
            foreach ($clientesSinEmailLogs as $cliente) {
                $this->enviarCorreo($cliente);
            }
        });

        // Proceso Fase 2: Enviar correos a clientes con EmailLog donde email_sent = false
        if ($totalClientesSinEmailLog == 0) { // Solo cuando todos los clientes sin EmailLog hayan sido procesados
            Log::info('Fase 2: Reintentando correos pendientes.');

            $clientesConEmailPendienteQuery = Cliente::whereHas('reservas', function ($query) {
                $query->where('estado', 'pagada')
                      ->whereNotNull('order_id'); // Añadir condición de que el order_id no sea null
            })
            ->with(['reservas' => function ($query) {
                $query->where('estado', 'pagada')
                      ->whereNotNull('order_id') // Añadir condición de que el order_id no sea null
                      ->with('silla');
            }])
            ->whereNotNull('email')
            ->whereHas('emailLogs', function ($query) {
                $query->where('email_sent', false); // Solo los que tienen email_sent = false
            });

            Log::info('Clientes con EmailLog pendiente: ' . $clientesConEmailPendienteQuery->count());

            $clientesConEmailPendienteQuery->chunk(10, function ($clientesConEmailPendiente) {
                foreach ($clientesConEmailPendiente as $cliente) {
                    $this->enviarCorreo($cliente);
                }
            });
        }

        $this->info('Proceso de envío de correos finalizado.');
    }

    private function enviarCorreo($cliente)
    {
        $reservas = $cliente->reservas;
        $sillas = $reservas->map(function($reserva) {
            return $reserva->silla;
        });

        $order = Order::where('cliente_id', $cliente->id)
                      ->where('status', 'paid') // Solo orders con estado 'paid'
                      ->first(); // Obtener el order_id asociado

        // Verificar si ya existe un EmailLog con email_sent = false
        $emailLogExistente = EmailLog::where('cliente_id', $cliente->id)
                                     ->where('order_id', $order->id)
                                     ->where('email_sent', false)
                                     ->first();

        if (!$emailLogExistente) {
            // Si no existe EmailLog, intentar enviar
            try {
                Mail::to($cliente->email)->send(new ReservaPagada($reservas, $sillas, $cliente));

                // Registrar el envío en email_logs
                EmailLog::create([
                    'cliente_id' => $cliente->id,
                    'order_id' => $order->id,
                    'email_content' => 'Contenido del correo guardado correctamente.',
                    'email_sent' => true,
                    'response' => 'Enviado exitosamente'
                ]);

                $this->info("Correo enviado a {$cliente->email}.");
            } catch (\Exception $e) {
                Log::error("Error al enviar correo al cliente ID {$cliente->id}: " . $e->getMessage());
                $this->error("Error al enviar correo al cliente ID {$cliente->id}: " . $e->getMessage());

                // Registrar el fallo en email_logs
                EmailLog::create([
                    'cliente_id' => $cliente->id,
                    'order_id' => $order->id,
                    'email_content' => '',
                    'email_sent' => false,
                    'response' => $e->getMessage()
                ]);
            }
        } else {
            // Si ya tiene un EmailLog con email_sent false, intentar enviar de nuevo
            try {
                Mail::to($cliente->email)->send(new ReservaPagada($reservas, $sillas, $cliente));

                // Actualizar el EmailLog existente
                $emailLogExistente->update([
                    'email_sent' => true,
                    'response' => 'Reenviado exitosamente'
                ]);

                $this->info("Correo reenviado a {$cliente->email}.");
            } catch (\Exception $e) {
                Log::error("Error al reenviar correo al cliente ID {$cliente->id}: " . $e->getMessage());
                $this->error("Error al reenviar correo al cliente ID {$cliente->id}: " . $e->getMessage());

                // Actualizar el EmailLog con el fallo
                $emailLogExistente->update([
                    'email_sent' => false,
                    'response' => $e->getMessage()
                ]);
            }
        }
    }

    // public function handle()
    // {
    //     Log::info('Comando de envío de correos iniciado.');

    //     // Subconsulta para seleccionar clientes que no tienen un EmailLog registrado
    //     $clientesConReservasPagadasQuery = Cliente::whereHas('reservas', function ($query) {
    //         $query->where('estado', 'pagada')
    //             ->whereNotNull('order_id'); // Añadir condición de que el order_id no sea null
    //     })
    //     ->with(['reservas' => function ($query) {
    //         $query->where('estado', 'pagada')
    //             ->whereNotNull('order_id') // Añadir condición de que el order_id no sea null
    //             ->with('silla');
    //     }])
    //     ->whereNotNull('email')
    //     ->whereDoesntHave('emailLogs');

    //     Log::info('Clientes pendientes de correo: ' . $clientesConReservasPagadasQuery->count());

    //     $clientesConReservasPagadasQuery->chunk(10, function ($clientesConReservasPagadas) {

    //         foreach ($clientesConReservasPagadas as $cliente) {
    //             $reservas = $cliente->reservas;
    //             $sillas = $reservas->map(function($reserva) {
    //                 return $reserva->silla;
    //             });
    //             $order = Order::where('cliente_id', $cliente->id)
    //             ->where('status', 'paid') // Solo orders con estado 'paid'
    //             ->first(); // Obtener el order_id asociado

    //             $existeEmailLog = EmailLog::where('cliente_id', $cliente->id)
    //             ->where('order_id', $order->id)
    //             ->exists();
    //             // Solo enviar correo si no existe un EmailLog previo
    //             if (!$existeEmailLog) {
    //                 try {
    //                     Mail::to($cliente->email)->send(new ReservaPagada($reservas, $sillas, $cliente));

    //                     // Registrar el envío en email_logs
    //                     EmailLog::create([
    //                         'cliente_id' => $cliente->id,
    //                         'order_id' => $order->id,
    //                         'email_content' => 'Contenido del correo guardado correctamente.', // Aquí puedes registrar lo necesario
    //                         'email_sent' => true,
    //                         'response' => 'Enviado exitosamente' // Puedes ajustar según la respuesta real del servidor de correo
    //                     ]);

    //                     $cliente->email_sent = true;
    //                     $cliente->save();
    //                     $this->info("Correo enviado a {$cliente->email}.");
    //                 } catch (\Exception $e) {
    //                     Log::error("Error al enviar correo al cliente ID {$cliente->id}: " . $e->getMessage());
    //                     $this->error("Error al enviar correo al cliente ID {$cliente->id}: " . $e->getMessage());

    //                     // Registrar el fallo en email_logs
    //                     EmailLog::create([
    //                         'cliente_id' => $cliente->id,
    //                         'order_id' => $order->id,
    //                         'email_content' => '', // No hay contenido en caso de fallo
    //                         'email_sent' => false,
    //                         'response' => $e->getMessage()
    //                     ]);
    //                 }

    //             } else {
    //                 $this->info("El cliente ID {$cliente->id} ya tiene un registro en EmailLog, no se enviará el correo.");
    //             }
    //         }
    //     });
    //     $this->info('Proceso de envío de correos finalizado.');
    // }
}
