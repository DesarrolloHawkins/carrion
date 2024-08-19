<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PistasController extends Controller
{
    

    public function index()
    {
        return view('pistas.index');
    }

    public function create()
    {
        return view('pistas.create');
    }


    public function edit($id)
    {
        return view('pistas.edit', compact('id'));
    }

    public function show($id)
    {
        return view('pistas.show', compact('id'));
    }

    
}
