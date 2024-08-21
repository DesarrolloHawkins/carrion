<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SillasController extends Controller
{
    

    public function index()
    {
        return view('sillas.index');
    }
}
