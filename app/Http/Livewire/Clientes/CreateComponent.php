<?php

namespace App\Http\Livewire\Clientes;

use App\Models\Cliente;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;
use Ramsey\Uuid\Type\Integer;
use Illuminate\Support\Facades\Auth;

class CreateComponent extends Component
{

    use LivewireAlert;

    public $nombre;
    public $apellido;
    public $telefono;
    public $email1;
    public $calle;
    public $genero;
    public $fecha_nacimiento;
    public $pais;
    public $ciudad;
    public $categoria_id;
    public $DNI;
    public $nickName;



    public function mount()
    {
        $this->clientes = Cliente::all();
    }

    public function crearClientes()
    {
        return Redirect::to(route("clientes.create"));
    }



    public function render()
    {
        return view('livewire.clientes.create-component');
    }

    // Al hacer submit en el formulario
    public function submit()
    {
        // Validación de datos
        $validatedData = $this->validate(
            [
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'telefono' => 'required|string|max:255',
                'email1' => 'required|string|max:255',
                'calle' => 'nullable',
                'genero' => 'required|string|max:255',
                'fecha_nacimiento' => 'required|date',
                'pais' => 'nullable',
                'ciudad' => 'nullable',
                'categoria_id' => 'required|integer',
                'DNI' => 'required|string|max:255',
                'nickName' => 'required|string|max:255',


            ],
            // Mensajes de error
            [
                'nombre.required' => 'El campo nombre es obligatorio',
                'apellido.required' => 'El campo apellido es obligatorio',
                'telefono.required' => 'El campo teléfono es obligatorio',
                'email1.required' => 'El campo email es obligatorio',
                'genero.required' => 'El campo género es obligatorio',
                'fecha_nacimiento.required' => 'El campo fecha de nacimiento es obligatorio',
                'categoria_id.required' => 'El campo categoría es obligatorio',
                'DNI.required' => 'El campo DNI es obligatorio',
                'nickName.required' => 'El campo nickName es obligatorio',


            ]
        );

        // Guardar datos validados
        $clienteSave = Cliente::create($validatedData);

        event(new \App\Events\LogEvent(Auth::user(), 8, $clienteSave->id));

        // Alertas de guardado exitoso
        if ($clienteSave) {
            $this->alert('success', '¡Usuario registrado correctamente!', [
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
    }

    // Función para cuando se llama a la alerta
    public function getListeners()
    {
        return [
            'confirmed',
            'submit'
        ];
    }

    // Función para cuando se llama a la alerta
    public function confirmed()
    {
        // Do something
        return redirect()->route('clientes.index');
    }
}
