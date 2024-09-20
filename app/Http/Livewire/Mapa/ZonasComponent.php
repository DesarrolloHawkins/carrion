<?php

namespace App\Http\Livewire\Mapa;

use App\Models\Gradas;
use App\Models\Palcos;
use App\Models\Reservas;
use App\Models\Sillas;
use App\Models\Zonas;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ZonasComponent extends Component
{
    use LivewireAlert;

    public $identificador;
    public $svgFile;
    public $palcos = []; // Arreglo para almacenar la información de los palcos
    public $gradas = []; // Arreglo para almacenar la información de las gradas
    // Propiedades para almacenar los datos seleccionados
    public $zonaID;
    public $pathsData = []; // Para recibir los paths del SVG
    public $selectedId;
    public $selectedZona;
    public $selectedType;
    public $selectedSector;

    protected $listeners = ['selectZone', 'zoneConfirmed'];

    public function mount($identificador)
    {
        $this->identificador = $identificador;
        
        // Seleccionar el archivo SVG basado en el identificador
        switch ($this->identificador) {
            case 1:
                $this->svgFile = 'arenal1.svg';
                break;
            case 2:
                $this->svgFile = 'lanceria-gallo-1.svg';
                break;  
            case 3:
                $this->svgFile = 'algarve-plaza-1.svg';
                break;  
            case 4:
                $this->svgFile = 'casino-santodomingo.svg';
                break;
            case 5:
                $this->svgFile = 'Marques-domecq.svg';
                break;
            case 6:
                $this->svgFile = 'eguiluz1.svg';
                break;
            case 7:
                $this->svgFile = 'ayuntamiento.svg';
                break;
            case 8:
                $this->svgFile = 'asuncion.svg';
                break;
            default:
                $this->svgFile = 'default.svg';
        }
        // $this->pathsData = $pathsData;
        // $this->checkIfFull(); // Verifica si los palcos o gradas están completos
        // Llamar a las funciones que verifican si los palcos o gradas están completos
        // $this->loadPalcos();
        // $this->loadGradas();
    }
    
    
    public function selectZone($id, $zona, $type, $sector)
    {
        // Almacenar los valores en propiedades del componente
        $this->selectedId = $id;
        $this->selectedZona = $zona;
        $this->selectedType = $type;
        $this->selectedSector = $sector;

        // Definir el mensaje personalizado basado en el tipo de zona
        switch($type) {
            case 'palco':
                $message = "Está usted seleccionando un Palco en la zona $zona (ID: $id) del sector $sector.";
                break;
            case 'silla':
                $message = "Está usted seleccionando una Silla en la zona $zona (ID: $id) del sector $sector.";
                break;
            case 'grada':
                $message = "Está usted seleccionando la Grada en la zona $zona (ID: $id) del sector $sector.";
                break;
            default:
                $message = "Está usted seleccionando una zona $zona (ID: $id) del sector $sector.";
        }

        // Mostrar alerta de confirmación con LivewireAlert
        $this->alert('question', '¿Deseas seleccionar esta zona?', [
            'text' => $message,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Sí, seleccionar',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancelar',
            'position' => 'center',
            'onConfirmed' => 'zoneConfirmed',
        ]);
    }

    public function zoneConfirmed()
    {
        // Usar las propiedades almacenadas para la redirección
        if ($this->selectedType === 'palco') {
            return redirect()->route('mapa.palcos', [
                'id' => $this->selectedId,
                'zona' => $this->selectedZona,
                'sector' => $this->selectedSector,
            ]);
        }

        //dd($this->selectedType);

        if($this->selectedType === 'grada') {
            return redirect()->route('mapa.gradas', [
                'id' => $this->selectedId,
                'zona' => $this->selectedZona,
            ]);
        }

        // Lógica adicional si se seleccionan otros tipos (sillas, gradas, etc.)
        $this->alert('success', 'Zona seleccionada', [
            'text' => 'Has seleccionado la zona correctamente.',
        ]);
    }

    public function render()
    {
        return view('livewire.mapa.zonas-component', [
            'svgFile' => $this->svgFile,
            'palcos' => $this->palcos,
            'gradas' => $this->gradas,
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
    
   
}
