<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Sillas;
use App\Models\Zonas;
class ReservaPagada extends Mailable
{
    use Queueable, SerializesModels;

    public $reservas;
    public $sillas; // Agregamos las sillas para detalles adicionales
    public $cliente; // Agregamos el cliente para enviar información personalizada
    public $zonas;
    public $detallesReservas = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reservas, $sillas, $cliente)
    {
        $this->reservas = $reservas;
        $this->sillas = $sillas;
        $this->cliente = $cliente;

        $this->zonas = [];

        foreach ($reservas as $reserva) {
            $silla = Sillas::find($reserva->id_silla); // Obtener la silla correspondiente
            $zona = Zonas::find($silla->id_zona); // Obtener la zona correspondiente

            // Crear un array con toda la información estructurada
            $this->detallesReservas[] = [
                'asiento' => $silla->numero ?? 'N/A',
                'sector' => $zona->nombre ?? 'N/A',
                'fecha' => $reserva->fecha,
                'año' => $reserva->año,
                'precio' => $reserva->precio,
                'fila' => $silla->fila ?? 'N/A',
                'order' => $reserva->order,
            ];
        }
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Confirmación de Reserva')
                    ->markdown('emails.reserva_pagada')
                    ->with([
                        'reservas' => $this->reservas,
                        'sillas' => $this->sillas,
                        'cliente' => $this->cliente,
                        'detallesReservas' => $this->detallesReservas,
                        'zonas' => $this->zonas,
                    ]);
    }
}
