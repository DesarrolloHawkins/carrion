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
    public $selectedSilla;
    public $clienteSeleccionado;
    public $nuevoClienteNombre;
    public $nuevoClienteEmail;
    public $DNI;
    public $reservaPrecio;
    public $clientes;
    public $mostrarFormularioNuevoCliente = false;
    public $estadoSeleccionado;

    public function mount($palco)
    {
        $this->palco = $palco;

        // Verificar si el palco ya tiene sillas generadas, si no, generarlas.
        if ($this->palco->sillas()->count() === 0) {
            for ($i = 1; $i <= 8; $i++) {
                Sillas::create([
                    'numero' => $i,
                    'id_palco' => $this->palco->id,
                    'fila' => $i <= 4 ? 1 : 2, // Las primeras 4 sillas están en la fila 1, las siguientes 4 en la fila 2.
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

    public function selectSilla($sillaId)
    {
        $this->clienteSeleccionado = null;
        $this->estadoSeleccionado = null;
        $this->selectedSilla = Sillas::findOrFail($sillaId);
        $this->reservaPrecio = $this->calcularPrecio($this->selectedSilla);

        // Comprobar si la silla está reservada
        $reservaSilla = Reservas::where('id_silla', $this->selectedSilla->id)
            ->where('id_evento', 1)
            ->where(function ($query) {
                $query->where('estado', 'reservada')
                      ->orWhere('estado', 'pagada');
            })
            ->first();

        if ($reservaSilla) {
            // Configurar datos iniciales para el modal de edición
            $this->clienteSeleccionado = $reservaSilla->id_cliente;
            $this->estadoSeleccionado = $reservaSilla->estado;
            $this->dispatchBrowserEvent('show-modal-editar-reserva');
            return;
        }

        $this->dispatchBrowserEvent('show-modal'); // Emitir evento para mostrar el modal
    }

    public function editarReserva()
    {
        // Obtener la reserva existente
        $reserva = Reservas::where('id_silla', $this->selectedSilla->id)
        ->where('id_evento', 1)
        ->where(function ($query) {
            $query->where('estado', 'reservada')
                  ->orWhere('estado', 'pagada');
        })
        ->first();

        if (!$reserva) {
            $this->alert('error', 'Reserva no encontrada', [
                'text' => 'No se ha encontrado la reserva de la silla seleccionada.',
                'position' => 'center',
            ]);
            return;
        }

        if (!$this->clienteSeleccionado) {
            $this->alert('error', 'Cliente requerido', [
                'text' => 'Debes seleccionar un cliente.',
                'position' => 'center',
            ]);
            return;
        }

        $cliente = Cliente::findOrFail($this->clienteSeleccionado);

        if (!$cliente) {
            $this->alert('error', 'Cliente no encontrado', [
                'text' => 'No se ha encontrado el cliente seleccionado.',
                'position' => 'center',
            ]);
            return;
        }

        // Actualizar los detalles de la reserva
        $reserva->update([
            'id_cliente' => $this->clienteSeleccionado,
            'estado' => $this->estadoSeleccionado,
            'precio' => $this->reservaPrecio,
        ]);

        $this->dispatchBrowserEvent('hide-modal-editar-reserva');
        $this->reset(['clienteSeleccionado', 'selectedSilla', 'estadoSeleccionado']);
        
        $this->alert('success', 'Reserva actualizada', [
            'text' => 'La reserva ha sido actualizada con éxito.',
            'position' => 'center',
        ]);

        // Actualizar la lista de sillas
        $this->sillas = Sillas::where('id_palco', $this->palco->id)->with('reservas')
            ->get()
            ->sortBy('numero');
    }

    public function calcularPrecio($silla)
    {
        // Asumiendo que el precio de las sillas en el palco es fijo, si no, puedes modificar esta lógica
        return 100; // Precio fijo como ejemplo
    }

    public function crearCliente()
    {
        if (!$this->nuevoClienteNombre) {
            $this->alert('error', 'Nombre requerido', [
                'text' => 'Debes ingresar el nombre del cliente.',
                'position' => 'center',
            ]);
            return;
        }

        if (!$this->nuevoClienteEmail) {
            $this->alert('error', 'Email requerido', [
                'text' => 'Debes ingresar el email del cliente.',
                'position' => 'center',
            ]);
            return;
        }

        if (!$this->DNI) {
            $this->alert('error', 'DNI requerido', [
                'text' => 'Debes ingresar el DNI del cliente.',
                'position' => 'center',
            ]);
            return;
        }

        // Crear un nuevo cliente
        $cliente = Cliente::create([
            'nombre' => $this->nuevoClienteNombre,
            'email' => $this->nuevoClienteEmail,
            'DNI' => $this->DNI,
        ]);

        // Actualizar la lista de clientes y seleccionar el nuevo cliente
        $this->clientes = Cliente::all();
        $this->clienteSeleccionado = $cliente->id;

        // Limpiar los campos de creación
        $this->reset(['nuevoClienteNombre', 'nuevoClienteEmail', 'DNI']);

        $this->dispatchBrowserEvent('update-dropdown');
    }

    public function IsReservado($reservas)
    {
        foreach ($reservas as $reserva) {
            if ($reserva->estado == 'reservada' || $reserva->estado == 'pagada') {
                return true;
            }
        }
        return false;
    }

    public function reservarSilla()
    {
        $reservaSilla = Reservas::where('id_silla', $this->selectedSilla->id)
        ->where('id_evento', 1) // Cambia '1' al id_evento relevante
        ->where('estado', '!=', 'cancelada')
        ->first();

        if ($reservaSilla) {
            $this->alert('error', 'Silla no disponible', [
                'text' => 'La silla seleccionada ya ha sido reservada.',
                'position' => 'center',
            ]);
            return;
        }

        if (!$this->clienteSeleccionado) {
           //livewire alert
            $this->alert('error', 'Cliente requerido', [
                'text' => 'Debes seleccionar un cliente.',
                'position' => 'center',
            ]);
            return;
        }

        $cliente = Cliente::findOrFail($this->clienteSeleccionado);
        if (!$this->estadoSeleccionado) {
            //livewire alert
            $this->alert('error', 'Estado requerido', [
                'text' => 'Debes seleccionar un estado para la reserva.',
                'position' => 'center',
            ]);
            return;
        }

        Reservas::create([
            'id_silla' => $this->selectedSilla->id,
            'id_cliente' => $cliente->id,
            'id_evento' => 1, // Cambia esto según sea necesario
            'fecha' => now(),
            'año' => date('Y'),
            'precio' => $this->reservaPrecio,
            'estado' => $this->estadoSeleccionado,
        ]);

        $this->dispatchBrowserEvent('hide-modal');
        $this->reset(['clienteSeleccionado', 'selectedSilla']);

        $this->alert('success', 'Reserva creada', [
            'text' => 'La reserva ha sido creada con éxito.',
            'position' => 'center',
        ]);

        $this->sillas = Sillas::where('id_palco', $this->palco->id)->with('reservas')
            ->get()
            ->sortBy('numero');
    }

    public function render()
    {
        return view('livewire.mapa.palco-component');
    }
}
