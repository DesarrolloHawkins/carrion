<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\CategoriaJugadores;

class JugadorComponent extends Component
{
    use LivewireAlert;

    public $dataTypes = [
        'trackTypes' => [
            'model' => CategoriaJugadores::class,
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
        return view('livewire.settings.jugador-component');
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
