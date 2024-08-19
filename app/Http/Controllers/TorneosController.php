<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Torneos;

class TorneosController extends Controller
{
    public function index()
    {
        return view('torneos.index');
    }

    //edit
    public function edit($id)
    {
        
        return view('torneos.edit', compact('id'));
    }

    //editduos
    public function editduos($id)
    {
        
        return view('torneos.editduos', compact('id'));
    }
}
