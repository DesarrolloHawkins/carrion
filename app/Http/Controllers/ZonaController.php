<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zonas;
use App\Models\Palcos;
use App\Models\Gradas;
use App\Models\Sillas;
use App\Models\Reservas;

class ZonaController extends Controller
{
    public function checkIfFull(Request $request)
    {
        // Recibir datos del path enviados desde el frontend
        $dataId = $request->input('data-id');
        $dataType = $request->input('data-type');

        // Verificar si es un palco o grada y si está completo
        if ($dataType === 'palco') {
            $completo = $this->checkPalcoCompleto($dataId);
        } elseif ($dataType === 'grada') {
            $completo = $this->checkGradaCompleto($dataId);
        } else {
            return response()->json(['id' => $dataId, 'completo' => false]);
        }

        return response()->json(['id' => $dataId, 'completo' => $completo]);
    }

public function checkPalcoCompleto($palcoId)
{
    // Obtener el número total de sillas en el palco
    $palco = Palcos::find($palcoId);
    
    if (!$palco) {
        return false;
    }

    // Número total de sillas en el palco
    $totalSillas = $palco->num_sillas;
    $sillasReservadas = Reservas::where('id_palco')->where('estado', ['reservada', 'pagada'])->count();
    // Contar cuántas sillas en este palco tienen reservas activas (reservada o pagada)


    // Verificar si todas las sillas están reservadas
    return $totalSillas === $sillasReservadas;
}

public function checkGradaCompleto($gradaId)
{
    // Contar el número total de sillas en la grada
    $totalSillas = Sillas::where('id_grada', $gradaId)->count();

    if ($totalSillas == 0) {
        return false; // Si no hay sillas en esta grada, no puede estar completa
    }

    $sillasReservadas = Reservas::where('id_zona')->where('estado', ['reservada', 'pagada'])->count();
    // Contar cuántas sillas en esta grada tienen reservas activas (reservada o pagada)
    // $sillasReservadas = Reservas::whereHas('silla', function ($query) use ($gradaId) {
    //     $query->where('id_grada', $gradaId);
    // })->whereIn('estado', ['reservada', 'pagada'])->count();

    // Verificar si todas las sillas están reservadas
    return $totalSillas === $sillasReservadas;
}

}
