<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cliente;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservaPagada; // Asegúrate de tener la clase Mail correcta
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
        $clientesConReservasPagadas = Cliente::whereHas('reservas', function($query) {
            $query->where('estado', 'pagada');
        })
        ->with(['reservas' => function($query) {
            $query->where('estado', 'pagada')->with('silla');
        }])
        ->where('email_sent', false)
        ->whereNotNull('email')
        ->get();

        foreach ($clientesConReservasPagadas as $cliente) {
            $reservas = $cliente->reservas;
            $sillas = $reservas->map(function($reserva) {
                return $reserva->silla;
            });

            try {
                Mail::to($cliente->email)->send(new ReservaPagada($reservas, $sillas, $cliente));
                $cliente->email_sent = true;
                $cliente->save();
                $this->info("Correo enviado a {$cliente->email}.");
            } catch (\Exception $e) {
                Log::error("Error al enviar correo al cliente ID {$cliente->id}: " . $e->getMessage());
                $this->error("Error al enviar correo al cliente ID {$cliente->id}: " . $e->getMessage());
            }
        }

        $this->info('Proceso de envío de correos finalizado.');
    }
}
