<?php

namespace App\Http\Livewire\ServiciosPacks;

use App\Models\Servicio;
use App\Models\ServicioPack;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class EditComponent extends Component
{
    use LivewireAlert;

    public $identificador;

    public $nombre;
    public $servicios;
    public $servicio;
    public $serviciosPack = [];
    public $serviciosPackIDs = [];

    public function mount()
    {
        $pack = ServicioPack::find($this->identificador);
        $this->servicios = Servicio::all();
        $this->serviciosPack = Servicio::whereJsonContains('id_pack', $this->identificador)->get();
        $this->nombre = $pack->nombre;

    }

    public function render()
    {
        return view('livewire.servicios_packs.edit-component');
    }

    public function removeServ($servicioId)
    {
        $servicio = Servicio::find($servicioId);

        // Eliminar id del pack del array id_pack del servicio
        // Asegúrate de que id_pack sea un array o trátalo como un array vacío
        $idPacks = is_array($servicio->id_pack) ? $servicio->id_pack : [];
        if (($key = array_search($this->identificador, $idPacks)) !== false) {
            unset($idPacks[$key]);
            $servicio->id_pack = $idPacks;
            $servicio->save();
        }

        // Recargar los servicios del pack
        $this->loadServiciosPack();
    }

    public function addServ()
    {
        $servicio = Servicio::find($this->servicio);
        // Asegúrate de que id_pack sea un array o trátalo como un array vacío
        $idPacks = is_array($servicio->id_pack) ? $servicio->id_pack : [];

        if (!in_array($this->identificador, $idPacks)) {
            $idPacks[] = $this->identificador;
            $servicio->id_pack = $idPacks;
            $servicio->save();
        }

        // Recargar los servicios del pack
        $this->loadServiciosPack();
    }

    private function loadServiciosPack()
    {
        // Cargar servicios que pertenecen a este pack
        $this->serviciosPack = Servicio::whereJsonContains('id_pack', $this->identificador)->get();
    }
    // Al hacer update en el formulario
    public function update()
{
    // Validación de datos
    $this->validate([
        'nombre' => 'required',
    ],
    [
        'nombre.required' => 'El nombre es obligatorio.',
    ]);

    // Encuentra el identificador
    $servicioPack = ServicioPack::find($this->identificador);

    // Guardar datos validados
    $servicioSave = $servicioPack->update([
        'nombre' => $this->nombre,
    ]);

    if ($servicioSave) {
        // Actualizar id_pack para cada servicio
        foreach ($this->serviciosPack as $servicio) {
            $servicioActual = Servicio::find($servicio['id']);
            if ($servicioActual) {
                $idPacks = $servicioActual->id_pack ?? [];
                if (!in_array($servicioPack->id, $idPacks)) {
                    $idPacks[] = $servicioPack->id;
                }
                $servicioActual->id_pack = $idPacks;
                $servicioActual->save();
            }
        }

        $this->alert('success', 'Pack actualizado correctamente!', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'confirmed',
            'confirmButtonText' => 'ok',
            'timerProgressBar' => true,
        ]);
    } else {
        $this->alert('error', '¡No se ha podido guardar la información del pack!', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => false,
        ]);
    }

    session()->flash('message', 'Pack actualizado correctamente.');

    $this->emit('eventUpdated');
}

      // Eliminación
      public function destroy(){

        $this->alert('warning', '¿Seguro que desea borrar el pack? No hay vuelta atrás', [
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
            'confirmDelete',
            'update'
        ];
    }

    // Función para cuando se llama a la alerta
    public function confirmed()
    {
        // Do something
        return redirect()->route('servicios-packs.index');

    }
    // Función para cuando se llama a la alerta
    public function confirmDelete()
    {
        Servicio::where("id_pack", $this->identificador)->update(["id_pack" => null]);
        $pack = ServicioPack::find($this->identificador);
        $pack->delete();
        return redirect()->route('servicios-packs.index');

    }
}
