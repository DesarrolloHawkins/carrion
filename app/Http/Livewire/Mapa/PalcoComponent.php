<?php

namespace App\Http\Livewire\Mapa;

use App\Models\Palcos;
use App\Models\Sillas;
use App\Models\Cliente;
use App\Models\Gradas;
use App\Models\Reservas;
use App\Models\Sectores;
use App\Models\Zonas;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PalcoComponent extends Component
{
    use LivewireAlert;

    public $palco;
    public $sillas;
    public $selectedSillas = []; // Para selección múltiple de sillas
    public $clienteSeleccionado;
    public $nuevoClienteNombre;
    public $nuevoClienteEmail;
    public $DNI;
    public $reservaPrecio;
    public $clientes;
    public $mostrarFormularioNuevoCliente = false;
    public $estadoSeleccionado;
    public $selectedSilla;
    public $metodoPago;
    public $isInvitado = false;

    public function mount($palco)
    {
        $this->palco = $palco;

        // Generar las sillas si no existen
        if ($this->palco->sillas()->count() === 0) {
            for ($i = 1; $i <= 8; $i++) {
                Sillas::create([
                    'numero' => $i,
                    'id_palco' => $this->palco->id,
                    'fila' => $i <= 4 ? 1 : 2,
                ]);
            }
        }

        // Obtener todas las sillas del palco
        $this->sillas = Sillas::where('id_palco', $this->palco->id)->with('reservas')
            ->get()
            ->sortBy('numero');

        // Obtener todos los clientes para el dropdown
        $this->clientes = Cliente::all();
    }

    public function IsReservado($reservas)
    {
        foreach ($reservas as $reserva) {
            if ($reserva->estado === 'reservada' || $reserva->estado === 'pagada') {
                return true;
            }
        }
        return false;
    }

    public function abrirModalReserva()
    {
        if (count($this->selectedSillas) > 0) {
            $this->dispatchBrowserEvent('show-modal');
        } else {
            $this->alert('error', 'Selecciona al menos una silla', ['position' => 'center']);
        }
    }

    public function selectSilla($sillaId)
    {
        $this->selectedSilla = Sillas::findOrFail($sillaId);
        $reserva = Reservas::where('id_silla', $this->selectedSilla->id)
            ->where('id_evento', 1)
            ->where(function ($query) {
                $query->where('estado', 'reservada')->orWhere('estado', 'pagada');
            })
            ->first();

        if ($reserva) {
            $this->clienteSeleccionado = $reserva->id_cliente;
            $this->estadoSeleccionado = $reserva->estado;
            $this->reservaPrecio = $reserva->precio;
            $this->dispatchBrowserEvent('show-modal-editar-reserva');
        } else {
            if (!in_array($sillaId, $this->selectedSillas)) {
                $this->selectedSillas[] = $sillaId;
            } else {
                $this->selectedSillas = array_diff($this->selectedSillas, [$sillaId]);
            }
        }
    }

    public function editarReserva()
{
    $reservas = [];

    foreach ($this->selectedSillas as $sillaId) {
        $silla = Sillas::findOrFail($sillaId);
        $reservaSilla = Reservas::where('id_silla', $silla->id)
            ->where('id_evento', 1)
            ->where('estado', '!=', 'cancelada')
            ->first();

        if (!$reservaSilla) {
            // Crear una nueva reserva si no existe
            $reserva = Reservas::create([
                'id_silla' => $silla->id,
                'id_cliente' => $this->clienteSeleccionado,
                'id_evento' => 1,
                'fecha' => now(),
                'año' => date('Y'),
                'precio' => $this->calcularPrecio($silla),
                'estado' => $this->estadoSeleccionado,
                'metodo_pago' => $this->metodoPago,
                'isInvitado' => $this->isInvitado,
                'isCRM' => true,
            ]);

            $reservas[] = $reserva; // Añadir la reserva al array para generar el PDF

        } else {
            // Actualizar la reserva existente si ya estaba reservada
            $reservaSilla->update([
                'id_cliente' => $this->clienteSeleccionado,
                'estado' => $this->estadoSeleccionado,
                'precio' => $this->reservaPrecio,
                'metodo_pago' => $this->metodoPago,
                'isInvitado' => $this->isInvitado,
                'isCRM' => true,
            ]);

            $reservas[] = $reservaSilla; // Añadir la reserva actualizada al array para generar el PDF
        }
    }

    // Generar el PDF para descargar si se han creado o actualizado reservas
    if (count($reservas) > 0) {
        $pdfUrl = $this->generarYDescargarPDF($reservas, Cliente::findOrFail($this->clienteSeleccionado));

        // Abrir el PDF en una nueva pestaña
        $this->dispatchBrowserEvent('open-pdf', ['url' => $pdfUrl]);

        // Mostrar alerta de éxito
        $this->alert('success', 'Reserva actualizada', [
            'text' => 'La reserva ha sido actualizada con éxito.',
            'position' => 'center',
        ]);
    }

    // Cerrar los modales y resetear los campos
    $this->dispatchBrowserEvent('hide-modal-editar-reserva');
    $this->dispatchBrowserEvent('hide-modal');
    $this->reset(['clienteSeleccionado', 'selectedSilla', 'estadoSeleccionado', 'selectedSillas']);

    // Actualizar las sillas del palco
    $this->sillas = Sillas::where('id_palco', $this->palco->id)->with('reservas')->get()->sortBy('numero');
}



    public function generarYDescargarPDF($reservas, $cliente, $tasas = null)
    {
        // Preparar los detalles de las reservas para el PDF
        $detallesReservas = [];
        $zona = null;
    
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
    
            $detallesReservas[] = [
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
    
        // Si $zona no está definida, establecer un valor por defecto
        if (!$zona) {
            $zona = new \stdClass();
            $zona->nombre = 'default';
        }
        // dd($zona->nombre);
    
        // Generar el código QR en formato SVG directamente
        $qrCodeSvg = QrCode::format('svg')
            ->size(200)
            ->generate(url('/reservas/' . $cliente->id));
        // dd($zona->nombre);
        $QrFinal = $this->svgToBase64($qrCodeSvg);

        // Obtener la imagen del mapa según la zona
        $mapImage = $this->getMapImageByZona($zona->nombre);
        $mapImageBase64 = $this->imageToBase64($mapImage);
        // dd($mapImageBase64);
        // Cálculo del total de precios
        $totalReservas = array_sum(array_column($detallesReservas, 'precio'));
        $totalPagado = $tasas;
    
        // Generar el PDF
        $pdf = PDF::loadView('pdf.reserva_qr_2', [
            'detallesReservas' => $detallesReservas,
            'qrCodeSvg' => $QrFinal,
            'cliente' => $cliente,
            'mapImage' => $mapImageBase64,
            'totalReservas' => $totalReservas,
            'tasas' => $tasas,
            'totalPagado' => $totalPagado,
        ])->setPaper('a4', 'portrait');
    
        // // Guardar el PDF en una ubicación temporal
        // $fileName = 'reserva_cliente_' . $cliente->id . '.pdf';
        // Storage::put('public/pdfs/' . $fileName, $pdf->output());
    
        // Retornar la URL del archivo generado
        return $pdf->stream('reserva_cliente_' . $cliente->id . '.pdf');
    }
    function svgToBase64($svgContent) {
        $output = base64_encode($svgContent);
        return 'data:image/svg+xml;base64,' . $output;
    }

    private function imageToBase64($path)
    {
        if (file_exists(public_path($path))) {
            $imageData = file_get_contents(public_path($path));
            return base64_encode($imageData);
        }
        return null;
    }
    private function getMapImageByZona($zonaNombre)
    {
        // Normalizar el nombre de la zona: eliminar espacios y convertir todo a minúsculas
        $zonaNombre = trim(strtolower($zonaNombre));

        switch ($zonaNombre) {
            case '01- asunción (protocolo)':
            case 'plaza asunción (protocolo)':
                return '/images/zonas/asuncion.png';
                
            case '02.- consistorio':
            case 'consistorio ii':
            case 'consistorio i':
                return '/images/zonas/consistorio.png';
                
            case '03. arenal':
            case 'arenal ii':
            case 'arenal i':
            case 'arenal iii':
            case 'arenal iv':
            case 'arenal v':
            case 'arenal vi':
                return '/images/zonas/arenal.png';
                
            case '04.- lancería-gallo azul':
            case 'lancería-gallo azul':
            case 'lancería-gallo azul i':
                return '/images/zonas/lanceria.png';
                
            case '05.- algarve-plaza del banco i':
            case '05.- algarve-plaza del banco':
            case 'algarve-plaza del banco':
                return '/images/zonas/larga.png';
                
            case '06.- rotonda de los casinos-santo domingo':
            case 'rotonda de los casinos-santo domingo':
            case 'rotonda de los casinos-santo domingo ii':
                return '/images/zonas/casinos.png';
                
            case '07.- marqués de casa domecq':
            case 'marqués de casa domecq ii':
            case 'marqués de casa domecq i':
                return '/images/zonas/santodomingo.png';
                
            case '08.- eguiluz':
            case 'eguiluz ii':
            case 'eguiluz i':
                return '/images/zonas/domecq.png';
                
            default:
                return '/images/zonas/default.png';
        }
    }

    public function calcularPrecio($silla)
    {
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

    public function render()
    {
        return view('livewire.mapa.palco-component');
    }
}
