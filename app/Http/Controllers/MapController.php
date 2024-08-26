<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zonas;
use App\Models\Sectores;
use App\Models\Palcos;
use App\Models\Sillas;
use App\Models\Gradas;
class MapController extends Controller
{
    public function index()
    {
        return view('mapa.index');
    }

    public function zona(request $request)
    {
        //id del request 
        $identificador = $request->id;

        return view('mapa.zonas', compact('identificador'));
    }

    public function sillas($id)
    {
        return view('mapa.sillas');
    }


    public function palcos($id, $zona, $sector)
    {
        // Buscar la zona por nombre
        //dd($zona);
        $zonaModel = Zonas::where('nombre', $zona)->first();
        //dd($zonaModel);
        if (!$zonaModel) {
            return abort(404, 'Zona no encontrada');
        }

        // Buscar el sector por nombre y el id_zona
        $sectorModel = Sectores::where('nombre', $sector)
                               ->where('id_zona', $zonaModel->id)
                               ->first();
        if (!$sectorModel) {
            return abort(404, 'Sector no encontrado');
        }

        // Buscar el palco con el id del sector, id de la zona y el id del palco
        $palco = Palcos::where('id_zona', $zonaModel->id)
                        ->where('id_sector', $sectorModel->id)
                        ->where('numero', $id)
                        ->first();
        if (!$palco) {
            return abort(404, 'Palco no encontrado');
        }

        // Renderizar la vista con los datos del palco
        return view('mapa.palcos', compact('palco'));
    }

    public function gradas($id, $zona)
    {
        // Buscar la zona por su nombre
        $zonaModel = Zonas::where('nombre', $zona)->firstOrFail();
        
        // Buscar la grada por id_zona y número
        $grada = Gradas::where('id_zona', $zonaModel->id)
                        ->where('numero', $id)
                        ->firstOrFail();

        return view('mapa.gradas', compact('grada'));
    }

}
