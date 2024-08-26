<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Zonas;
use App\Models\Sectores;
use App\Models\Palcos;
use App\Models\Sillas;
use App\Models\Gradas;
use Illuminate\Http\Request;

class MapApiController extends Controller
{
    // Obtener información de las sillas con filtros opcionales
    public function getSillas(Request $request)
    {
        $query = Sillas::query();

        // Filtrar por zona
        if ($request->has('zona')) {
            $zona = Zonas::where('nombre', $request->zona)->first();
            if ($zona) {
                $query->where('id_zona', $zona->id);
            } else {
                return response()->json(['error' => 'Zona no encontrada'], 404);
            }
        }

        // Filtrar por sector
        if ($request->has('sector')) {
            $sector = Sectores::where('nombre', $request->sector)->first();
            if ($sector) {
                $query->where('id_sector', $sector->id);
            } else {
                return response()->json(['error' => 'Sector no encontrado'], 404);
            }
        }

        // Filtrar por número de palco
        if ($request->has('palco')) {
            $palco = Palcos::where('numero', $request->palco)->first();
            if ($palco) {
                $query->where('id_palco', $palco->id);
            } else {
                return response()->json(['error' => 'Palco no encontrado'], 404);
            }
        }

        // Filtrar por número de grada
        if ($request->has('grada')) {
            $grada = Gradas::where('id', $request->grada)->first();
            if ($grada) {
                $query->where('id_grada', $grada->id);
            } else {
                return response()->json(['error' => 'Grada no encontrada'], 404);
            }
        }

        // Obtener los resultados paginados
        if($request->has('per_page')){
            $perPage = $request->get('per_page');
            $sillas = $query->paginate($perPage);
        } else {
            
            $sillas = $query->get();

        }
        // Ajusta el número de elementos por página según sea necesario

        return response()->json($sillas);
    }

    // Obtener información de un palco específico
    public function getPalco($id, $zona, $sector)
    {
        $zonaModel = Zonas::where('nombre', $zona)->first();
        if (!$zonaModel) {
            return response()->json(['error' => 'Zona no encontrada'], 404);
        }

        $sectorModel = Sectores::where('nombre', $sector)
                               ->where('id_zona', $zonaModel->id)
                               ->first();
        if (!$sectorModel) {
            return response()->json(['error' => 'Sector no encontrado'], 404);
        }

        $palco = Palcos::where('id_zona', $zonaModel->id)
                        ->where('id_sector', $sectorModel->id)
                        ->where('numero', $id)
                        ->first();
        if (!$palco) {
            return response()->json(['error' => 'Palco no encontrado'], 404);
        }

        return response()->json($palco);
    }

    // Obtener información de una grada específica
    public function getGrada($id, $zona)
    {
        $zonaModel = Zonas::where('nombre', $zona)->first();
        if (!$zonaModel) {
            return response()->json(['error' => 'Zona no encontrada'], 404);
        }

        $grada = Gradas::where('id_zona', $zonaModel->id)
                        ->where('numero', $id)
                        ->first();
        if (!$grada) {
            return response()->json(['error' => 'Grada no encontrada'], 404);
        }

        return response()->json($grada);
    }
}
