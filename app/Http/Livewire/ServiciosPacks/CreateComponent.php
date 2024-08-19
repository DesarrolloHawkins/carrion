<?php

namespace App\Http\Livewire\ServiciosPacks;

use App\Models\ServicioPack;
use App\Models\Servicio;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CreateComponent extends Component
{

    use LivewireAlert;

    public $nombre;

    public $servicios;
    public $servicio;
    public $serviciosPack = [];
    public $servicioSeleccionado;
    public $serviciosSeleccionados = [];
    public $serviciosPackIDs = [];


    public function mount()
    {
        $this->servicios = Servicio::all();
    }

    public function removeServ($servicioId)
    {
        $this->serviciosSeleccionados = array_filter($this->serviciosSeleccionados, function ($id) use ($servicioId) {
            return $id != $servicioId;
        });
    }

    public function addServ()
    {
        if (!in_array($this->servicioSeleccionado, $this->serviciosSeleccionados)) {
            $this->serviciosSeleccionados[] = $this->servicioSeleccionado;
        }
    }

    private function loadServiciosPack()
    {
        // Cargar servicios que pertenecen a este pack
        $this->serviciosPack = Servicio::whereJsonContains('id_pack', $this->identificador)->get();
    }
    public function render()
    {
        return view('livewire.servicios_packs.create-component');
    }

    // Al hacer submit en el formulario
    public function submit()
    {
        // Validación de datos
        $validatedData = $this->validate(
            [
                'nombre' => 'required',

            ],
            // Mensajes de error
            [
                'nombre.required' => 'El nombre es obligatorio.',
            ]
        );

        // Guardar datos validados
        $packSave = ServicioPack::create($validatedData);

        // Alertas de guardado exitoso
        if ($packSave) {

            foreach ($this->serviciosSeleccionados as $servicioId) {
                $servicio = Servicio::find($servicioId);
                if ($servicio) {
                    $idPacks = $servicio->id_pack ?? [];
                    // Castigar el ID a cadena y luego agregarlo al arreglo
                    $idPacks[] = (string)$packSave->id;
                    $servicio->id_pack = $idPacks;
                    $servicio->save();
                }
            }

            $this->alert('success', 'Paquete registrado correctamente!', [
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
        return redirect()->route('servicios-packs.index');
    }
}
