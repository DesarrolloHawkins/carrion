<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display the settings index page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings.index');
    }

    /**
     * Display the pista settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function pista_view()
    {
        return view('settings.pista');
    }


    public function jugador_view()
    {
        return view('settings.jugador');
    }

    public function socios_view()
    {
        return view('settings.socios');
    }

    public function socios_ventajas_view()
    {
        return view('settings.socios-ventajas');
    }

    public function socios_zonas_view()
    {
        return view('settings.socios-zonas');
    }

    public function festivos_view()
    {
        return view('settings.festivos');
    }

    public function precios_view()
    {
        return view('settings.precios');
    }

    public function club_view()
    {
        return view('settings.club');
    }

}
