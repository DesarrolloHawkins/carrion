<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Palco;
use Illuminate\Http\Request;

class PalcoController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10); // Ajusta según sea necesario
        $palcos = Palco::with('sillas')->paginate($perPage);
        return response()->json($palcos);
    }

    public function show($id)
    {
        $palco = Palco::with('sillas')->findOrFail($id);
        return response()->json($palco);
    }
}