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
use App\Models\Sectores;

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
    public $mapImageBase64;
    public $montoCobrar;



    public function __construct($reservas, $sillas, $cliente)
    {
        $this->reservas = $reservas;
        $this->sillas = $sillas;
        $this->cliente = $cliente;

        $this->zonas = [];
        $palco = null;
        $grada = null;

        $totalPrecio = 0;
        foreach ($reservas as $reserva) {
            $totalPrecio += $reserva->precio;
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

        $this->mapImageBase64 = $this->imageToBase64( $this->mapImage);

        // Generar el código QR y almacenarlo como base64
        $this->qrCodeBase64 = base64_encode(QrCode::format('png')
            ->size(200)
            ->generate(url('/reservas/' . $cliente->id)));


        //tasas
        $montoObjetivo = $totalPrecio;  // Monto objetivo que deseas recibir
        $tarifaFija = 0.30;      // Tarifa fija de Stripe (en dólares)
        $tarifaPorcentaje = 0.029; // Tarifa porcentual de Stripe (2.9% es 0.029)
        $this->montoCobrar = $this->calcularMontoCobrar($montoObjetivo, $tarifaFija, $tarifaPorcentaje);
    }


    public function calcularMontoCobrar($montoObjetivo, $tarifaFija, $tarifaPorcentaje) {
        // Fórmula: Pcarga = (Pobjetivo + Ffijo) / (1 - Fporcentaje)
        $montoCobrar = ($montoObjetivo + $tarifaFija) / (1 - $tarifaPorcentaje);
        return round($montoCobrar, 2); // Redondeamos a 2 decimales
    }

    public function calcularPrecioMultiple($sillaIds)
    {
        $totalPrecio = 0;
        foreach ($sillaIds as $sillaId) {
            $silla = Sillas::findOrFail($sillaId);
            $totalPrecio += $this->calcularPrecio($silla);
        }
        return $totalPrecio;
    }

    public function calcularPrecio($silla)
    {
        $numeroFila = intval(substr($silla->fila, 1));

        if($silla->id_grada){

            $precio = \DB::table('precios_sillas')
            ->where('tipo_asiento', 'grada')
            ->where(function ($query) use ($numeroFila) {
                $query->where('fila_inicio', '<=', $numeroFila)
                    ->where(function ($query) use ($numeroFila) {
                        $query->where('fila_fin', '>=', $numeroFila)
                            ->orWhereNull('fila_fin');
                    });
            })
            ->value('precio');

            return $precio ?: 12;
        }else{
            $palcoIds = [
                16, 17, 18, 19, 20,21,22,23,24,25,26,27,28,122,121,120,119,118,117,116,115,114,113,112,111,110,109,108,220,221,222,223,224,225,226,227,228,229,230,231,232,233,234,
                534, 533, 532, 531, 530, 529, 528, 527, 526, 525, 524, 523, 522, 521, 520, 519, 518, 517, 516, 515, 514, 513, 512, 511, 510, 509, 508, 507, 506, 505, 504, 503, 502, 501, 500, 499, 498, 497, 496, 495, 494, 493,

            ];

            $palco = Palcos::find($silla->id_palco);

            if ($palco) {
                if (in_array($palco->numero, $palcoIds)) {
                    return 18;
                } else {
                    return 20;
                }
            }
        }


    }


    // Función para obtener la imagen según el nombre de la zona
    private function getMapImageByZona($zonaNombre)
    {
        switch ($zonaNombre) {
            case '01- Asunción (Protocolo)':
            case 'Plaza Asunción (Protocolo)':
                return '/images/zonas/asuncion.png';
            case '02.- Consistorio':
            case 'Consistorio II':
            case 'Consistorio I':
            case '':
                return '/images/zonas/consistorio.png';
            case '03. Arenal':
            case 'Arenal II':
            case 'Arenal I':
            case 'Arenal III':
            case 'Arenal IV':
            case 'Arenal V':
            case 'Arenal VI':
                return '/images/zonas/arenal.png';
            case '04.- Lancería-Gallo Azul':
            case 'Lancería-Gallo Azul':
                return '/images/zonas/lanceria.png';
            case '05.- Algarve-Plaza del Banco':
            case 'Algarve-Plaza del Banco':
                return '/images/zonas/larga.png';
            case '06.- Rotonda de los Casinos-Santo Domingo':
            case 'Rotonda de los Casinos-Santo Domingo':
            case 'Rotonda de los Casinos-Santo Domingo II':
                return '/images/zonas/casinos.png';
            case '07.- Marqués de Casa Domecq':
            case 'Marqués de Casa Domecq II':
            case 'Marqués de Casa Domecq':
            case 'Marqués de Casa Domecq I':
                return '/images/zonas/santodomingo.png';
            case '08.- Eguiluz':
            case 'Eguiluz II':
            case 'Eguiluz I':
            case 'Eguiluz':
                return '/images/zonas/domecq.png';
            default:
                return '/images/zonas/default.png';
        }
    }

    private function imageToBase64($path)
{
    if (file_exists(public_path($path))) {
        $imageData = file_get_contents(public_path($path));
        return base64_encode($imageData);
    }
    return null;
}

    public function build()
    {
        // Generar el PDF
        $pdf = PDF::loadView('pdf.reserva_qr', [
            'detallesReservas' => $this->detallesReservas,
            'qrCodeBase64' => $this->qrCodeBase64,
            'cliente' => $this->cliente,
            'mapImage' => $this->mapImageBase64, // Imagen seleccionada según la zona
            'tasas' => $this->montoCobrar
        ]);

        return $this->from('info@unionhermandadesjerez.hawkins.es', 'Unión de Hermandades de Jerez') // Configura el alias aquí
                    ->subject('Confirmación de Reserva')
                    ->markdown('emails.reserva_pagada')
                    ->with([
                        'reservas' => $this->reservas,
                        'sillas' => $this->sillas,
                        'cliente' => $this->cliente,
                        'detallesReservas' => $this->detallesReservas,
                        'zonas' => $this->zonas,
                        'mapImage' => $this->mapImageBase64, // Imagen seleccionada según la zona
                        'tasas' => $this->montoCobrar

                    ])
                    ->attachData($pdf->output(), 'reserva_qr.pdf', [
                        'mime' => 'application/pdf',
                    ]); // Adjuntar el PDF
    }
}
