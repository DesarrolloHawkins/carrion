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
    public $selectedSilla;
    public $clienteSeleccionado;
    public $nuevoClienteNombre;
    public $nuevoClienteEmail;
    public $DNI;
    public $reservaPrecio;
    public $clientes;
    public $mostrarFormularioNuevoCliente = false;
    public $estadoSeleccionado;

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

    public function selectSilla($sillaId)
    {
        $this->clienteSeleccionado = null;
        $this->estadoSeleccionado = null;
        $this->selectedSilla = Sillas::findOrFail($sillaId);
        $this->reservaPrecio = $this->calcularPrecio($this->selectedSilla);

        //comprobar si la silla esta reservada
        $reservaSilla = Reservas::where('id_silla', $this->selectedSilla->id)
        ->where('id_evento', 1)
        ->where(function ($query) {
            $query->where('estado', 'reservada')
                  ->orWhere('estado', 'pagada');
        })
        ->first();

        //dd($reservaSilla);
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

    //reserva no encontrada
    if (!$reserva) {
        //livewire alert
        $this->alert('error', 'Reserva no encontrada', [
            'text' => 'No se ha encontrado la reserva de la silla seleccionada.',
            'position' => 'center',
        ]);
    }


    //cliente no seleccionado
    if (!$this->clienteSeleccionado) {
        //livewire alert
        $this->alert('error', 'Cliente requerido', [
            'text' => 'Debes seleccionar un cliente.',
            'position' => 'center',
        ]);
    }

    $cliente = Cliente::findOrFail($this->clienteSeleccionado);

    if(!$cliente){
        //livewire alert
        $this->alert('error', 'Cliente no encontrado', [
            'text' => 'No se ha encontrado el cliente seleccionado.',
            'position' => 'center',
        ]);
    }


    // Actualizar los detalles de la reserva
    $reserva->update([
        'id_cliente' => $this->clienteSeleccionado,
        'estado' => $this->estadoSeleccionado,
        'precio' => $this->reservaPrecio,
    ]);

    // Ocultar el modal y resetear los campos
    $this->dispatchBrowserEvent('hide-modal-editar-reserva');
    $this->reset(['clienteSeleccionado', 'selectedSilla', 'estadoSeleccionado']);
    
    //livewire alert
    $this->alert('success', 'Reserva actualizada', [
        'text' => 'La reserva ha sido actualizada con éxito.',
        'position' => 'center',
    ]);

    // Actualizar la lista de sillas
    $this->sillas = Sillas::where('id_grada', $this->grada->id)->with('reservas')
        ->get()
        ->sortBy(function ($silla) {
            return intval(str_replace('F', '', $silla->fila)); // Extraer el número de la fila
        })
        ->sortBy('numero');
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

    public function crearCliente()
    {

         //si falta nombre alerta
        if (!$this->nuevoClienteNombre) {
            //livewire alert
            $this->alert('error', 'Nombre requerido', [
                'text' => 'Debes ingresar el nombre del cliente.',
                'position' => 'center',
            ]);
        }

        //si falta email alerta
        if (!$this->nuevoClienteEmail) {
            //livewire alert
            $this->alert('error', 'Email requerido', [
                'text' => 'Debes ingresar el email del cliente.',
                'position' => 'center',
            ]);
        }


        //si falta DNI alerta
        if (!$this->DNI) {
            //livewire alert
            $this->alert('error', 'DNI requerido', [
                'text' => 'Debes ingresar el DNI del cliente.',
                'position' => 'center',
            ]);
        }


        // Crear un nuevo cliente
        $cliente = Cliente::create([
            'nombre' => $this->nuevoClienteNombre,
            'email' => $this->nuevoClienteEmail,
            'DNI' => $this->DNI, // Cambiar esto según sea necesario
        ]);

        // Actualizar la lista de clientes y seleccionar el nuevo cliente
        $this->clientes = Cliente::all();
        $this->clienteSeleccionado = $cliente->id;

        // Limpiar los campos de creación
        $this->reset(['nuevoClienteNombre', 'nuevoClienteEmail']);

        // Emitir evento para seleccionar el nuevo cliente en el dropdown
        $this->dispatchBrowserEvent('update-dropdown');
    }

    public function IsReservado($reservas){

        //recorre todas las reservas y mira si alguna esta en estado reservado o pagado
        foreach ($reservas as $reserva) {
            if ($reserva->estado == 'reservada' || $reserva->estado == 'pagada') {
                return true;
            }
        }
        return false;


    }

    public function reservarSilla()
    {

        //si la silla ya esta reservada para el evento retorna un mensaje de error

        $reservaSilla = Reservas::where('id_silla', $this->selectedSilla->id)
        ->where('id_evento', 1) // Cambia '1' al id_evento relevante
        ->where('estado', '!=', 'cancelada')
        ->first();

        if ($reservaSilla) {
           //livewire alert

            $this->alert('error', 'Silla no disponible', [
                'text' => 'La silla seleccionada ya ha sido reservada.',
                'position' => 'center',
            ]);

            return;


        }

        // Validar que un cliente haya sido seleccionado
        if (!$this->clienteSeleccionado) {
            session()->flash('error', 'Debes seleccionar o crear un cliente.');
            return;
        }

        // Buscar el cliente seleccionado
        $cliente = Cliente::findOrFail($this->clienteSeleccionado);
        if(!$this->estadoSeleccionado){
            session()->flash('error', 'Debes seleccionar un estado.');
            return;
        }
        // Crear la reserva
        Reservas::create([
            'id_silla' => $this->selectedSilla->id,
            'id_cliente' => $cliente->id,
            'id_evento' => 1, // Cambia esto según sea necesario
            'fecha' => now(),
            'año' => date('Y'),
            'precio' => $this->reservaPrecio,
            'estado' =>  $this->estadoSeleccionado,
        ]);

        // Ocultar el modal y resetear los campos
        $this->dispatchBrowserEvent('hide-modal');
        $this->reset(['clienteSeleccionado', 'selectedSilla']);

        //livewire alert
        $this->alert('success', 'Reserva creada', [
            'text' => 'La reserva ha sido creada con éxito.',
            'position' => 'center',
        ]);

        // Actualizar la lista de sillas
        $this->sillas = Sillas::where('id_grada', $this->grada->id)->with('reservas')
            ->get()
            ->sortBy(function ($silla) {
                return intval(str_replace('F', '', $silla->fila)); // Extraer el número de la fila
            })
            ->sortBy('numero');
    }

    public function render()
    {
        return view('livewire.mapa.grada-component');
    }
}