<?php

namespace App\Http\Livewire\Mapa;

use App\Models\Gradas;
use App\Models\Sillas;
use App\Models\Cliente;
use App\Models\Reservas;
use App\Models\Zonas;
use App\Models\Palcos;
use App\Models\Sectores;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Storage;  // Para guardar el PDF temporalmente
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Bus\Queueable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;


class GradaComponent extends Component
{
    use LivewireAlert;

    public $grada;
    public $sillas;
    public $selectedSillas = []; // Array para múltiples sillas seleccionadas
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

    public function mount($grada)
    {
        $this->grada = $grada;
        // Obtener todas las sillas de la grada
        $this->sillas = Sillas::where('id_grada', $this->grada->id)->with('reservas')
            ->get()
            ->sortBy(function ($silla) {
                return intval(str_replace('F', '', $silla->fila)); // Extraer el número de la fila
            })
            ->sortBy('numero');

        // Obtener todos los clientes para el dropdown
        $this->clientes = Cliente::all();
    }

    public function abrirModalReserva()
    {
        if (count($this->selectedSillas) > 0) {
            // Mostrar el modal de creación de reservas
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
                $query->where('estado', 'reservada')
                      ->orWhere('estado', 'pagada');
            })
            ->first();
    
        // Si la silla está reservada o pagada, abrimos el modal de edición
        if ($reserva) {
            $this->clienteSeleccionado = $reserva->id_cliente;
            $this->estadoSeleccionado = $reserva->estado;
            $this->reservaPrecio = $reserva->precio;
            $this->dispatchBrowserEvent('show-modal-editar-reserva');
        } else {
            // Si la silla no está reservada, se añade a la selección múltiple
            if (!in_array($sillaId, $this->selectedSillas)) {
                $this->selectedSillas[] = $sillaId;
            } else {
                // Si ya está seleccionada, la removemos
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
    
                $reservas[] = $reserva;
            } else {
                $reservaSilla->update([
                    'id_cliente' => $this->clienteSeleccionado,
                    'estado' => $this->estadoSeleccionado,
                    'precio' => $this->reservaPrecio,
                    'metodo_pago' => $this->metodoPago,
                    'isInvitado' => $this->isInvitado,
                    'isCRM' => true,
                ]);
    
                $reservas[] = $reservaSilla;
            }
        }
    
        if (count($reservas) > 0) {
            // Generar el PDF y obtener la URL
            $pdfUrl = $this->generarYDescargarPDF($reservas, Cliente::findOrFail($this->clienteSeleccionado));
    
            // Abrir el PDF en una nueva pestaña
            $this->dispatchBrowserEvent('open-pdf', ['url' => $pdfUrl]);
        }
    
        $this->dispatchBrowserEvent('hide-modal-editar-reserva');
        $this->dispatchBrowserEvent('hide-modal');
    
        $this->reset(['clienteSeleccionado', 'selectedSilla', 'estadoSeleccionado', 'selectedSillas']);
    
        $this->sillas = Sillas::where('id_grada', $this->grada->id)->with('reservas')
            ->get()
            ->sortBy(function ($silla) {
                return intval(str_replace('F', '', $silla->fila));
            })
            ->sortBy('numero');
    }
    

    public function reservarSillas()
    {
        if (!$this->clienteSeleccionado) {
            $this->alert('error', 'Debes seleccionar un cliente.', ['position' => 'center']);
            return;
        }
    
        $cliente = Cliente::findOrFail($this->clienteSeleccionado);
    
        foreach ($this->selectedSillas as $sillaId) {
            $silla = Sillas::findOrFail($sillaId);
            $reservaSilla = Reservas::where('id_silla', $silla->id)
                ->where('id_evento', 1)
                ->where('estado', '!=', 'cancelada')
                ->first();
    
            // Crear la reserva solo si la silla no está ya reservada
            if (!$reservaSilla) {
                Reservas::create([
                    'id_silla' => $silla->id,
                    'id_cliente' => $cliente->id,
                    'id_evento' => 1,
                    'fecha' => now(),
                    'año' => date('Y'),
                    'precio' => $this->calcularPrecio($silla),
                    'estado' => $this->estadoSeleccionado,
                    'metodo_pago' => $this->metodoPago,
                    'isInvitado' => $this->isInvitado,
                    'isCRM' => true,
                ]);
            }
        }
        // Generar el PDF para descargar
        // if ($reservas != null) {
                
        //     $this->generarYDescargarPDF($reservas, $cliente);
        // }
        $this->reset(['clienteSeleccionado', 'selectedSillas']);
        $this->dispatchBrowserEvent('hide-modal');
        //$this->alert('success', 'Reservas creadas con éxito', ['position' => 'center']);

       
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
    
        // Generar el código QR en formato SVG directamente
        $qrCodeSvg = QrCode::format('svg')
            ->size(200)
            ->generate(url('/reservas/' . $cliente->id));
        $QrFinal = $this->svgToBase64($qrCodeSvg);

        // Obtener la imagen del mapa según la zona
        $mapImage = $this->getMapImageByZona($zona->nombre);
        $mapImageBase64 = $this->imageToBase64($mapImage);
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
    
        // // Retornar la URL del archivo generado
        // return Storage::url('public/pdfs/' . $fileName);
        return $pdf->stream('reserva_cliente_' . $cliente->id . '.pdf');

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


     
    
    // Función para calcular el precio total de las sillas seleccionadas
    public function calcularPrecioMultiple($sillaIds)
    {
        $totalPrecio = 0;
        foreach ($sillaIds as $sillaId) {
            $silla = Sillas::findOrFail($sillaId);
            $totalPrecio += $this->calcularPrecio($silla);
        }
        return $totalPrecio;
    }

    // Aquí agregamos el método IsReservado
    public function IsReservado($reservas)
    {
        foreach ($reservas as $reserva) {
            if ($reserva->estado === 'reservada' || $reserva->estado === 'pagada') {
                return true;
            }
        }
        return false;
    }

    public function calcularPrecio($silla)
    {
        $numeroFila = intval(substr($silla->fila, 1));

        $precio = DB::table('precios_sillas')
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
    }

    public function render()
    {
        return view('livewire.mapa.grada-component');
    }
}
