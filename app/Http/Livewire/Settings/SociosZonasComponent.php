<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Zonas;

class SociosZonasComponent extends Component
{
    use LivewireAlert;

    public $dataTypes = [
        'trackTypes' => [
            'model' => Zonas::class,
            'items' => [],
            'newItem' => [
                'nombre' => '',
                'max_capacidad' => ''
            ]
        ],
    ];

    public $currentType = 'trackTypes';

    public function mount()
    {
        foreach ($this->dataTypes as $key => $dataType) {
            $this->dataTypes[$key]['items'] = $dataType['model']::all()->toArray();
        }
    }

    public function render()
    {
        return view('livewire.settings.socios-zonas-component');
    }

    public function submit()
    {
        $this->saveDataType($this->currentType);
        $this->alert('success', 'Datos guardados correctamente');
    }

    public function addItem()
    {
        $newItem = $this->dataTypes[$this->currentType]['newItem'];
        if ($newItem['nombre'] && $newItem['max_capacidad']) {
            $this->dataTypes[$this->currentType]['items'][] = $newItem;
            $this->dataTypes[$this->currentType]['newItem'] = [
                'nombre' => '',
                'max_capacidad' => ''
            ];
        }
    }

    public function removeItem($index)
    {
        unset($this->dataTypes[$this->currentType]['items'][$index]);
        $this->dataTypes[$this->currentType]['items'] = array_values($this->dataTypes[$this->currentType]['items']); // Reindexar el array
    }

    protected function saveDataType($key)
    {
        $model = $this->dataTypes[$key]['model'];
        $existingItems = $model::all()->keyBy('nombre')->toArray();

        $newItems = [];
        foreach ($this->dataTypes[$key]['items'] as $item) {
            $newItems[$item['nombre']] = $item;
        }

        // Eliminar los elementos que ya no están en la lista
        foreach ($existingItems as $existingItem) {
            if (!isset($newItems[$existingItem['nombre']])) {
                $model::where('nombre', $existingItem['nombre'])->delete();
            }
        }

        // Añadir los nuevos elementos
        foreach ($newItems as $item) {
            $model::updateOrCreate(
                ['nombre' => $item['nombre']],
                ['max_capacidad' => $item['max_capacidad']]
            );
        }
    }

    public function setCurrentType($type)
    {
        $this->currentType = $type;
    }
}
