<?php

namespace App\Http\Livewire\Mapa;

use App\Models\Gradas;
use App\Models\Sillas;
use App\Models\Cliente;
use App\Models\Reservas;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

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
    foreach ($this->selectedSillas as $sillaId) {
        $silla = Sillas::findOrFail($sillaId);
        $reservaSilla = Reservas::where('id_silla', $silla->id)
            ->where('id_evento', 1)
            ->where('estado', '!=', 'cancelada')
            ->first();

        // Crear la reserva si no está ya reservada
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
            ]);
        } else {
            // Si ya está reservada, actualiza la reserva existente
            $reservaSilla->update([
                'id_cliente' => $this->clienteSeleccionado,
                'estado' => $this->estadoSeleccionado,
                'precio' => $this->reservaPrecio,
                'metodo_pago' => $this->metodoPago,
            ]);

            // Alerta de éxito
            $this->alert('success', 'Reserva actualizada', [
                'text' => 'La reserva ha sido actualizada con éxito.',
                'position' => 'center',
            ]);
        }
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
                ]);
            }
        }
    
        $this->reset(['clienteSeleccionado', 'selectedSillas']);
        $this->dispatchBrowserEvent('hide-modal');
        $this->alert('success', 'Reservas creadas con éxito', ['position' => 'center']);
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

        return $precio ?: 0;
    }

    public function render()
    {
        return view('livewire.mapa.grada-component');
    }
}
