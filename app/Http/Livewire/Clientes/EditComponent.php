<?php

namespace App\Http\Livewire\Clientes;

use App\Models\Cliente;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class EditComponent extends Component
{
    use LivewireAlert;

    public $identificador;
    public $nombre;
    public $apellido;
    public $tlf1;
    public $email1;
    public $calle;
    public $genero;
    public $fecha_nacimiento;
    public $pais;
    public $ciudad;
    public $categoria_id;

    public function mount()
    {
        $cliente = Cliente::find($this->identificador);


        $this->nombre = $cliente->nombre;
        $this->apellido = $cliente->apellido;
        $this->tlf1 = $cliente->tlf1;
        $this->email1 = $cliente->email1;
        $this->calle = $cliente->calle;
        $this->genero = $cliente->genero;
        $this->fecha_nacimiento = $cliente->fecha_nacimiento;
        $this->pais = $cliente->pais;
        $this->ciudad = $cliente->ciudad;
        $this->categoria_id = $cliente->categoria_id;
    }


    public function render()
    {
        return view('livewire.clientes.edit-component');
    }


    // Al hacer update en el formulario
    public function update()
    {
        // Validación de datos
        $this->validate([
           'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'tlf1' => 'nullable',
            'email1' => 'required|string|max:255',
            'calle' => 'nullable',
            'genero' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'pais' => 'nullable',
            'ciudad' => 'nullable',
            'categoria_id' => 'nullable',

        ],
            // Mensajes de error
            [
               
                'nombre.required' => 'El campo nombre es obligatorio',
                'nombre.string' => 'El campo nombre debe ser un texto',
                'nombre.max' => 'El campo nombre no debe exceder los 255 caracteres',
                'apellido.required' => 'El campo apellido es obligatorio',
                'apellido.string' => 'El campo apellido debe ser un texto',
                'apellido.max' => 'El campo apellido no debe exceder los 255 caracteres',


            ]);

        // Encuentra el identificador
        $cliente = Cliente::find($this->identificador);

        // Guardar datos validados
        $clienteSave = $cliente->update([
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'tlf1' => $this->tlf1,
            'email1' => $this->email1,
            'calle' => $this->calle,
            'genero' => $this->genero,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'pais' => $this->pais,
            'ciudad' => $this->ciudad,
            'categoria_id' => $this->categoria_id,
        ]);
        event(new \App\Events\LogEvent(Auth::user(), 9, $cliente->id));

        if ($clienteSave) {
            $this->alert('success', 'Usuario actualizado correctamente!', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => false,
                'showConfirmButton' => true,
                'onConfirmed' => 'confirmed',
                'confirmButtonText' => 'ok',
                'timerProgressBar' => true,
            ]);
        } else {
            $this->alert('error', '¡No se ha podido guardar la información del usuario!', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => false,
            ]);
        }

        session()->flash('message', 'cliente actualizado correctamente.');

        $this->emit('eventUpdated');
    }

      // Eliminación
      public function destroy(){

        $this->alert('warning', '¿Seguro que desea borrar el usuario? No hay vuelta atrás', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'confirmDelete',
            'confirmButtonText' => 'Sí',
            'showDenyButton' => true,
            'denyButtonText' => 'No',
            'timerProgressBar' => true,
        ]);

    }

    // Función para cuando se llama a la alerta
    public function getListeners()
    {
        return [
            'confirmed',
            'update',
            'destroy',
            'confirmDelete'
        ];
    }

    // Función para cuando se llama a la alerta
    public function confirmed()
    {
        // Do something
        return redirect()->route('clientes.index');

    }
    // Función para cuando se llama a la alerta
    public function confirmDelete()
    {
        $cliente = Cliente::find($this->identificador);
        event(new \App\Events\LogEvent(Auth::user(), 10, $cliente->id));
        $cliente->delete();
        return redirect()->route('clientes.index');

    }
}
