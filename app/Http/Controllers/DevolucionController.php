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

// RecepcionMercanciaController.php

// RecepcionMercanciaController.php

public function getRecepcionDetails($id)
{
    $recepcion = RecepcionesMercancia::with('detalles_recepciones_mercancias.suministro', 'empleado')->find($id);

    if ($recepcion) {
        return response()->json([
            'idRecepcion_mercancia' => $recepcion->idRecepcion_mercancia,
            'fecha_recepcion' => $recepcion->fecha_recepcion->format('d/m/Y'),
            'empleado' => $recepcion->empleado ? $recepcion->empleado->nombre_empleado . ' ' . $recepcion->empleado->apellido_empleado : 'Desconocido',
            'detalles_recepciones_mercancias' => $recepcion->detalles_recepciones_mercancias->map(function ($detalle) {
                return [
                    'suministro' => $detalle->suministro ? $detalle->suministro->nombre_suministro : 'Desconocido',
                    'cantidad_recibida' => $detalle->cantidad_recibida,
                    'estado' => $detalle->status_recepcion == 1 ? 'Aceptado' : 'Rechazado'
                ];
            })
        ]);
    }

    return response()->json(['error' => 'Recepción no encontrada'], 404);
}



}

