<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reserva;
use Carbon\Carbon;
use App\Models\Reservas;
class CancelOldReservations extends Command
{
    protected $signature = 'reservas:cancel-expired';
    protected $description = 'Cancela reservas que han estado reservadas por más de 15 minutos sin confirmación de pago.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $expirationTime = Carbon::now()->subMinutes(15);
        
        // Encuentra todas las reservas que no han sido actualizadas en los últimos 15 minutos
        $expiredReservations = Reservas::where('estado', 'reservada')
            ->where('isCRM', false)
            ->where('updated_at', '<', $expirationTime)
            ->get();

        foreach ($expiredReservations as $reserva) {
            $reserva->estado = 'cancelada';
            $reserva->save();
            $this->info("Reserva con ID {$reserva->id} cancelada.");
        }

        $this->info('Reservas expiradas canceladas.');
    }

}

