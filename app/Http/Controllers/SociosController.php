<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SociosController extends Controller
{
    

    public function index()
    {
        return view('socios.index');
    }

}
