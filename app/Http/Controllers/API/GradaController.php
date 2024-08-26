<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Grada;
use Illuminate\Http\Request;

class GradaController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10); // Ajusta según sea necesario
        $gradas = Grada::with('sillas')->paginate($perPage);
        return response()->json($gradas);
    }

    public function show($id)
    {
        $grada = Grada::with('sillas')->findOrFail($id);
        return response()->json($grada);
    }
}