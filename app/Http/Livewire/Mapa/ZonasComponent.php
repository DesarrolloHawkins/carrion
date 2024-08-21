<?php

namespace App\Http\Livewire\Mapa;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ZonasComponent extends Component
{
    use LivewireAlert;

    public $identificador;
    public $svgFile;

    protected $listeners = ['selectZone'];

    public function mount($identificador)
    {
        $this->identificador = $identificador;
        
        // Seleccionar el archivo SVG basado en el identificador
        switch ($this->identificador) {
            case 1:
                $this->svgFile = 'arenal1.svg';
                break;
            case 2:
                $this->svgFile = 'arenal2.svg'; // Otro ejemplo, puedes añadir más casos
                break;
            // Añade más casos según los identificadores y los archivos SVG correspondientes
            default:
                $this->svgFile = 'default.svg'; // Un SVG por defecto si el identificador no coincide
        }
    }

    public function selectZone($id, $zona)
    {
        // Mostrar alerta de confirmación con LivewireAlert
        $this->alert('question', '¿Deseas seleccionar esta zona?', [
            'text' => "Zona: $zona ($id)",
            'showConfirmButton' => true,
            'confirmButtonText' => 'Sí, seleccionar',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancelar',
            'position' => 'center', // Asegura que la alerta esté centrada
            'onConfirmed' => 'zoneConfirmed', // Esto dispara un evento cuando se confirma
        ]);
    }

     // Función para cuando se llama a la alerta
     public function getListeners()
     {
         return [
             'zoneConfirmed',
             'selectZone'
         ];
     }

    public function zoneConfirmed()
    {


        // Lógica para manejar la confirmación
        $this->alert('success', 'Zona seleccionada', [
            'text' => 'Has seleccionado la zona correctamente.',
        ]);
    }
    
    public function render()
    {
        return view('livewire.mapa.zonas-component', [
            'svgFile' => $this->svgFile,
        ]);
    }
}
