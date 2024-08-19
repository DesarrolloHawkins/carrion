<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Torneos;

/*

GET /api/torneos?categorias=1
GET /api/torneos?categorias=1&categoriaDetalles=1
GET /api/torneos?categorias=1&duos=1
GET /api/torneos?categorias=1&duos=1&inscripciones=1
GET /api/torneos?categorias=1&partidos=1
GET /api/torneos?categorias=1&partidos=1&partidoResultados=1
GET /api/torneos?categorias=1&partidos=1&partidoResultados=1&partidoSets=1
GET /api/torneos?dias=1
GET /api/torneos?pistas=1
GET /api/torneos?clubes=1
-------------------------------
GET /api/torneos?categorias=1&duos=1&partidos=1&partidoResultados=1
GET /api/torneos?categorias=1&duos=1&partidos=1&partidoResultados=1&partidoSets=1
GET /api/torneos?categorias=1&categoriaDetalles=1&duos=1
GET /api/torneos?dias=1&pistas=1&clubes=1
GET /api/torneos?categorias=1&duos=1&partidos=1&partidoResultados=1&partidoSets=1&dias=1&pistas=1&clubes=1

*/



class TorneosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Comienza con la consulta básica de torneos
        $query = Torneos::query();
    
        // Verifica los parámetros de la solicitud y ajusta la consulta en consecuencia
        if ($request->has('categorias')) {
            $query->with('categorias');
    
            if ($request->has('categoriaDetalles')) {
                $query->with('categorias.categoria');
            }
    
            if ($request->has('duos')) {
                $query->with([
                    'categorias.duos.inscripcion.jugador',
                    'categorias.duos.inscripcion2.jugador',
                    'categorias.duos.disponibilidad',
                ]);
            }
    
            if ($request->has('partidos')) {
                $query->with([
                    'categorias.partidos.equipo1.inscripcion.jugador',
                    'categorias.partidos.equipo2.inscripcion.jugador',
                    'categorias.partidos.pista',
                    'categorias.partidos.torneosCategoria',
                    'categorias.partidos.resultado',
                ]);
    
                if ($request->has('partidoSets')) {
                    $query->with('categorias.partidos.resultado.sets');
                }
            }
        }
    
        if ($request->has('dias')) {
            $query->with('dias');
        }
    
        if ($request->has('pistas')) {
            $query->with('pistas');
        }
    
        if ($request->has('clubes')) {
            $query->with('clubes.club');
        }
    
        // Ejecuta la consulta y devuelve los resultados
        $torneos = $query->get();
    
        return response()->json($torneos);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Cargar un torneo específico junto con sus relaciones
        $torneo = Torneos::with(['equipos', 'partidos', 'categorias', 'monitores', 'clientes', 'socios', 'pistas', 'clubes'])->find($id);

        if (!$torneo) {
            return response()->json(['error' => 'Torneo no encontrado'], 404);
        }

        return response()->json($torneo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
