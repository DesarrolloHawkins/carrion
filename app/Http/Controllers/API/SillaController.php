<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Silla;
use Illuminate\Http\Request;

class SillaController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15); // Puedes ajustar el valor predeterminado
        $sillas = Silla::paginate($perPage);
        return response()->json($sillas);
    }

    public function show($id)
    {
        $silla = Silla::findOrFail($id);
        return response()->json($silla);
    }
}

