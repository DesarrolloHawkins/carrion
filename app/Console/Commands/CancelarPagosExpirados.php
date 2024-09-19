<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservas;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CancelarPagosExpirados extends Command
{
    // El nombre y la descripción del comando
    protected $signature = 'pagos:cancelar-expirados';
    protected $description = 'Cancelar pagos en reservas con procesando=true si han pasado más de 3 minutos desde updated_at';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Configurar Stripe con la clave secreta
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Obtener las reservas con procesando=true y que updated_at tenga más de 3 minutos
        $reservasExpiradas = Reservas::where('procesando', true)
            ->where('updated_at', '<', Carbon::now()->subMinutes(3))
            ->whereIn('estado', ['reservada', 'cancelada']) // Verificar si el estado es 'reservada' o 'cancelada'
            ->get();

        foreach ($reservasExpiradas as $reserva) {
            $paymentIntentId = $reserva->order;

            if ($paymentIntentId) {
                try {
                    // Cancelar el PaymentIntent en Stripe
                    $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
                    $paymentIntent->cancel();

                    // Actualizar el estado de la reserva a cancelada
                    $reserva->estado = 'cancelada';
                    $reserva->procesando = false;
                    $reserva->order = null;
                    $reserva->isCRM = false;
                    $reserva->save();

                    Log::info("Reserva ID {$reserva->id} cancelada correctamente debido a tiempo de espera excedido.");

                } catch (\Exception $e) {
                    Log::error("Error cancelando el PaymentIntent {$paymentIntentId} para la reserva ID {$reserva->id}: " . $e->getMessage());
                }
            } else {
                Log::warning("La reserva ID {$reserva->id} no tiene un paymentIntentId en la columna 'order'.");
            }
        }

        $this->info('Comando completado: Pagos expirados cancelados.');
    }
}
