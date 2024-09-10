<?php
namespace App\Mail;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Sillas;
use App\Models\Zonas;
use App\Models\Reservas;
use App\Models\Gradas;
use App\Models\Palcos;
use PDF; // Importar la clase PDF

class ReservaPagada extends Mailable
{
    use Queueable, SerializesModels;

    public $reservas;
    public $sillas;
    public $cliente;
    public $zonas;
    public $detallesReservas = [];
    public $qrCodeBase64; // Para almacenar el código QR en base64


    

    public function __construct($reservas, $sillas, $cliente)
    {
        $this->reservas = $reservas;
        $this->sillas = $sillas;
        $this->cliente = $cliente;

        $this->zonas = [];
        $palco = null;
        $grada = null;

        foreach ($reservas as $reserva) {
            $silla = Sillas::find($reserva->id_silla); 
            $zona = Zonas::find($silla->id_zona); 
            if ($silla->id_palco != null) {
                $palco = Palcos::find($silla->id_palco);
                $zona = Sectores::find($palco->id_sector);
            } elseif ($silla->id_grada != null) {
                $grada = Gradas::find($silla->id_grada);
                $zona = Zonas::find($grada->id_zona);
            }
            $this->detallesReservas[] = [
                'asiento' => $silla->numero ?? 'N/A',
                'sector' => $zona->nombre ?? 'N/A',
                'fecha' => $reserva->fecha,
                'año' => $reserva->año,
                'precio' => $reserva->precio,
                'fila' => $silla->fila ?? 'N/A',
                'order' => $reserva->order,
                'palco' => $palco->numero ?? '',
                'grada' => $grada->numero ?? '',
            ];
        }

        $reserva1 = $reservas[0];
        $silla = Sillas::find($reserva1->id_silla); 
        if($silla->id_palco != null){
            $palco = Palcos::find($silla->id_palco);
            $zona = zonas::find($palco->id_zona);
        }elseif($silla->id_grada != null){
            $grada = Gradas::find($silla->id_grada);
            $zona = Zonas::find($grada->id_zona);
        }else{
            $zona = Zonas::find($silla->id_zona);
        }

        //$zona = Zonas::find($silla->id_zona);

        // Obtener la imagen del mapa según la zona
        $this->mapImage = $this->getMapImageByZona($zona->nombre);


        // Generar el código QR y almacenarlo como base64
        $this->qrCodeBase64 = base64_encode(QrCode::format('png')
            ->size(200)
            ->generate(url('/reservas/' . $cliente->id)));
    }

    // Función para obtener la imagen según el nombre de la zona
    private function getMapImageByZona($zonaNombre)
    {
        switch ($zonaNombre) {
            case '01- Asunción (Protocolo)':
            case 'Plaza Asunción (Protocolo)':
                return asset('images/zonas/asuncion.png');
            case '02.- Consistorio':
            case 'Consistorio II':
            case 'Consistorio I':
            case '':
                return asset('images/zonas/consistorio.png');
            case '03. Arenal':
            case 'Arenal II':
            case 'Arenal I':
            case 'Arenal III':
            case 'Arenal IV':
            case 'Arenal V':
            case 'Arenal VI':
                return asset('images/zonas/arenal.png');
            case '04.- Lancería-Gallo Azul':
            case 'Lancería-Gallo Azul':
                return asset('images/zonas/lanceria.png');
            case '05.- Algarve-Plaza del Banco':
            case 'Algarve-Plaza del Banco':
                return asset('images/zonas/domecq.png');
            case '06.- Rotonda de los Casinos-Santo Domingo':
            case 'Rotonda de los Casinos-Santo Domingo':
            case 'Rotonda de los Casinos-Santo Domingo II':
                return asset('images/zonas/casinos.png');
            case '07.- Marqués de Casa Domecq':
            case 'Marqués de Casa Domecq II':
            case 'Marqués de Casa Domecq':
            case 'Marqués de Casa Domecq I':
                return asset('images/zonas/santodomingo.png');
            case '08.- Eguiluz':
            case 'Eguiluz II':
            case 'Eguiluz I':
            case 'Eguiluz':
                return asset('images/zonas/larga.png');
            default:
                return asset('images/zonas/default.png');
        }
    }

    public function build()
    {
        // Generar el PDF
        $pdf = PDF::loadView('pdf.reserva_qr', [
            'detallesReservas' => $this->detallesReservas,
            'qrCodeBase64' => $this->qrCodeBase64,
            'cliente' => $this->cliente,
            
        ]);

        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Confirmación de Reserva')
                    ->markdown('emails.reserva_pagada')
                    ->with([
                        'reservas' => $this->reservas,
                        'sillas' => $this->sillas,
                        'cliente' => $this->cliente,
                        'detallesReservas' => $this->detallesReservas,
                        'zonas' => $this->zonas,
                        'mapImage' => $this->mapImage, // Imagen seleccionada según la zona

                    ])
                    ->attachData($pdf->output(), 'reserva_qr.pdf', [
                        'mime' => 'application/pdf',
                    ]); // Adjuntar el PDF
    }
}
