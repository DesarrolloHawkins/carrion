<?php

namespace App\Http\Livewire\Reservas;

use Livewire\Component;
use App\Models\Reservas;
use App\Models\Sillas;
use App\Models\Cliente;
use App\Models\Zonas;
use App\Models\Palcos;
use App\Models\Gradas;
use App\Models\Sectores;

class EditComponent extends Component
{
    public $reservaId;
    public $reserva;
    public $sillas;
    public $clientes;
    public $zonas;
    public $estado;

    protected $rules = [
        'reserva.id_cliente' => 'required',
        'reserva.estado' => 'required',
        'reserva.metodo_pago' => 'nullable|string',
    ];

    public function mount($reservaId)
    {
        $this->reserva = Reservas::findOrFail($reservaId);
        $this->clientes = Cliente::all();
        $this->estado = $this->reserva->estado;
    }

    public function updateReserva()
    {
        $this->validate();

        // Guardar los cambios
        $this->reserva->save();

        // Mostrar un mensaje de éxito
        session()->flash('message', 'Reserva actualizada exitosamente.');

        // Redirigir a la lista de reservas
        return redirect()->route('reservas.index');
    }

    public function render()
    {
        return view('livewire.reservas.edit-component');
    }
}
