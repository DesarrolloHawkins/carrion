<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
