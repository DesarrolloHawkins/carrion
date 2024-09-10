<?php

namespace App\Http\Livewire\Mapa;

use Livewire\Component;

class IndexComponent extends Component
{


    protected $listeners = ['captureMap']; // Escuchar el evento emitido desde el JS
    public function render()
    {

        return view('livewire.mapa.index-component');
    }

    public function captureMap($imageData)
{
    // Asegurarse de que el dato de imagen está presente
    if ($imageData) {
        // Eliminar el encabezado "data:image/png;base64," de la cadena base64
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);

        // Decodificar la imagen base64
        $image = base64_decode($imageData);

        // Generar un nombre único para la imagen
        $imageName = 'map_capture_' . time() . '.png';

        // Definir el path donde se guardará la imagen
        $imagePath = storage_path('app/public/map_captures/' . $imageName);

        // Asegurarse de que la carpeta existe, si no, crearla
        if (!file_exists(storage_path('app/public/map_captures'))) {
            mkdir(storage_path('app/public/map_captures'), 0755, true);
        }

        // Guardar la imagen en el path especificado
        file_put_contents($imagePath, $image);

        // Enviar un mensaje de éxito
        session()->flash('message', 'Mapa capturado y guardado correctamente.');
    } else {
        session()->flash('error', 'No se pudo capturar el mapa.');
    }
}



}
