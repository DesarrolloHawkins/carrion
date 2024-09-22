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

class ReservaPagada2 extends Mailable
{
    use Queueable, SerializesModels;



    public function __construct()
    {

    }


    public function build()
    {


        return $this->from('noreply@unionhermandades.com', 'Unión de Hermandades de Jerez') // Configura el alias aquí
                    ->subject('Confirmación de Reserva')
                    ->markdown('pdf.devuelta');
    }
}
