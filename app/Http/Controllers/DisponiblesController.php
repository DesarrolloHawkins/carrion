<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DisponiblesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = '';
        // $user = Auth::user();

        return view('disponible.index', compact('response'));
    }
}
