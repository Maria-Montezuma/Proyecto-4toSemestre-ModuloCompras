<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\RecepcionesMercancia;

class DevolucionController extends Controller
{
    public function index()
{
    $empleados = Empleado::all();

    $recepciones = RecepcionesMercancia::with(['ordenes_compra.proveedore', 'detalles_recepciones_mercancias'])
    ->whereHas('detalles_recepciones_mercancias', function($query) {
        $query->where('status_recepcion', 0);
    })->get();

    return view('devolucion', compact('empleados', 'recepciones'));
}

public function getRecepcionDetails($id)
    {
        $recepcion = RecepcionesMercancia::with(['ordenes_compra.proveedore', 'detalles_recepciones_mercancias.suministro'])
            ->findOrFail($id);

        return response()->json($recepcion);
    }
}

