<?php

namespace App\Http\Livewire\Mapa;

use App\Models\Palcos;
use App\Models\Sillas;
use App\Models\Cliente;
use App\Models\Reservas;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

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
        foreach ($this->selectedSillas as $sillaId) {
            $silla = Sillas::findOrFail($sillaId);
            $reservaSilla = Reservas::where('id_silla', $silla->id)
                ->where('id_evento', 1)
                ->where('estado', '!=', 'cancelada')
                ->first();

            if (!$reservaSilla) {
                Reservas::create([
                    'id_silla' => $silla->id,
                    'id_cliente' => $this->clienteSeleccionado,
                    'id_evento' => 1,
                    'fecha' => now(),
                    'año' => date('Y'),
                    'precio' => $this->calcularPrecio($silla),
                    'estado' => $this->estadoSeleccionado,
                    'metodo_pago' => $this->metodoPago,
                    'isInvitado' => $this->isInvitado,
                ]);
            } else {
                $reservaSilla->update([
                    'id_cliente' => $this->clienteSeleccionado,
                    'estado' => $this->estadoSeleccionado,
                    'precio' => $this->reservaPrecio,
                    'metodo_pago' => $this->metodoPago,
                    'isInvitado' => $this->isInvitado,
                ]);

                $this->alert('success', 'Reserva actualizada', [
                    'text' => 'La reserva ha sido actualizada con éxito.',
                    'position' => 'center',
                ]);
            }
        }

        $this->dispatchBrowserEvent('hide-modal-editar-reserva');
        $this->dispatchBrowserEvent('hide-modal');
        $this->reset(['clienteSeleccionado', 'selectedSilla', 'estadoSeleccionado', 'selectedSillas']);
        $this->sillas = Sillas::where('id_palco', $this->palco->id)->with('reservas')->get()->sortBy('numero');
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
