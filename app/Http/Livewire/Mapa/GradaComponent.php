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

        // Crear la reserva si no está ya reservada
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

            //Alerta de éxito
            $this->alert('success', 'Reserva creada', [
                'text' => 'La reserva ha sido creada con éxito.',
                'position' => 'center',
            ]);

            // Añadir la reserva al array para generar el PDF
            $reservas[] = $reserva;
            
        } else {
            // Si ya está reservada, actualiza la reserva existente
            $reservaSilla->update([
                'id_cliente' => $this->clienteSeleccionado,
                'estado' => $this->estadoSeleccionado,
                'precio' => $this->reservaPrecio,
                'metodo_pago' => $this->metodoPago,
                'isInvitado' => $this->isInvitado,
                'isCRM' => true,
            ]);

           // Alerta de éxito
            $this->alert('success', 'Reserva actualizada', [
                'text' => 'La reserva ha sido actualizada con éxito.',
                'position' => 'center',
            ]);

            // Añadir la reserva al array para generar el PDF
            $reservas[] = $reservaSilla;
        }

        
    }

    // Generar el PDF para descargar
    if (count($reservas) > 0) {
        $this->generarYDescargarPDF($reservas, Cliente::findOrFail($this->clienteSeleccionado));
    }

    // Cerrar el modal y resetear los campos
    $this->dispatchBrowserEvent('hide-modal-editar-reserva');
    $this->dispatchBrowserEvent('hide-modal');

    $this->reset(['clienteSeleccionado', 'selectedSilla', 'estadoSeleccionado', 'selectedSillas']);

    // Actualizar las sillas
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
        if (count($reservas) > 0) {
                
            $this->generarYDescargarPDF($reservas, $cliente);
        }
        $this->reset(['clienteSeleccionado', 'selectedSillas']);
        $this->dispatchBrowserEvent('hide-modal');
        //$this->alert('success', 'Reservas creadas con éxito', ['position' => 'center']);

       
    }
    public function generarYDescargarPDF($reservas, $cliente)
    {
        // Preparar los detalles de las reservas para el PDF
        $detallesReservas = [];
        $zona = null;

        $zonas = [];
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
        $mapImage = $this->getMapImageByZona($zona->nombre);

        $mapImageBase64 = $this->imageToBase64( $mapImage);

        // Generar el código QR y almacenarlo como base64
        $qrCodeBase64 = base64_encode(QrCode::format('png')
            ->size(200)
            ->generate(url('/reservas/' . $cliente->id)));

            $pdf = PDF::loadView('pdf.reserva_qr', [
                'detallesReservas' => $detallesReservas,
                'qrCodeBase64' => $qrCodeBase64,
                'cliente' => $cliente,
                'mapImage' => $mapImageBase64, // Imagen seleccionada según la zona
    
            ])->setPaper('a4', 'vertical');

            return response()->streamDownload(
                fn () => print($pdf),
                "reserva_.pdf"
            );

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
    }

    public function render()
    {
        return view('livewire.mapa.grada-component');
    }
}
