<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\PistaTipo;
use App\Models\PistaTamano;
use App\Models\PistaCarasteristicas;
use App\Models\Deporte;

class PistaComponent extends Component
{
    use LivewireAlert;

    public $dataTypes = [
        'trackTypes' => [
            'model' => PistaTipo::class,
            'items' => [],
            'newItem' => ''
        ],
        'trackSizes' => [
            'model' => PistaTamano::class,
            'items' => [],
            'newItem' => ''
        ],
        'trackFeatures' => [
            'model' => PistaCarasteristicas::class,
            'items' => [],
            'newItem' => ''
        ],
        'sports' => [
            'model' => Deporte::class,
            'items' => [],
            'newItem' => ''
        ],
    ];

    public $currentType = 'trackTypes';

    public function mount()
    {
        foreach ($this->dataTypes as $key => $dataType) {
            $this->dataTypes[$key]['items'] = $dataType['model']::all()->pluck('nombre')->toArray();
        }
    }

    public function render()
    {
        return view('livewire.settings.pista-component');
    }

    public function submit()
    {
        foreach ($this->dataTypes as $key => $dataType) {
            $this->saveDataType($key);
        }
        $this->alert('success', 'Datos guardados correctamente');
    }

    public function addItem($key)
    {
        if ($this->dataTypes[$key]['newItem']) {
            $this->dataTypes[$key]['items'][] = $this->dataTypes[$key]['newItem'];
            $this->dataTypes[$key]['newItem'] = '';
        }
    }

    public function removeItem($key, $index)
    {
        unset($this->dataTypes[$key]['items'][$index]);
        $this->dataTypes[$key]['items'] = array_values($this->dataTypes[$key]['items']); // Reindexar el array
    }

    protected function saveDataType($key)
    {
        $model = $this->dataTypes[$key]['model'];
        $existingItems = $model::all()->pluck('nombre')->toArray();

        // Eliminar los elementos que ya no están en la lista
        foreach ($existingItems as $existingItem) {
            if (!in_array($existingItem, $this->dataTypes[$key]['items'])) {
                $model::where('nombre', $existingItem)->delete();
            }
        }

        // Añadir los nuevos elementos
        foreach ($this->dataTypes[$key]['items'] as $item) {
            if (!in_array($item, $existingItems)) {
                $model::create(['nombre' => $item]);
            }
        }
    }

    public function setCurrentType($type)
    {
        $this->currentType = $type;
    }
}
