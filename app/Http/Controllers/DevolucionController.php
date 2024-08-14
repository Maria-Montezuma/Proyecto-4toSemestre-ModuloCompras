<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\RecepcionesMercancia;
use App\Models\Devolucione;
use App\Models\DetallesDevolucione;
use App\Models\Suministro;


class DevolucionController extends Controller
{
    public function index()
{
    $empleados = Empleado::all();
    $recepciones = RecepcionesMercancia::with(['ordenes_compra.proveedore', 'detalles_recepciones_mercancias'])
        ->whereHas('detalles_recepciones_mercancias', function($query) {
            $query->where('status_recepcion', 0);
        })->get();
    $suministros = Suministro::all(); 
    $devoluciones = Devolucione::with(['detalles_devoluciones.suministro', 'empleado', 'recepciones_mercancia'])->get();
    
    // Verifica los datos
    // dd($empleados, $recepciones, $devoluciones, $suministros);

    return view('devolucion', compact('empleados', 'recepciones', 'devoluciones', 'suministros'));
}

public function store(Request $request)
{
    $request->validate([
        'fecha_devolucion' => 'required|date',
        'empleado_id' => 'required|exists:empleados,idEmpleados',
        'recepcion_id' => 'required|exists:recepciones_mercancias,idRecepcion_mercancia',
        'detalles' => 'required|array',
        'detalles.*.suministro_id' => 'required|exists:suministros,idSuministro',
        'detalles.*.cantidad_devuelta' => 'required|integer',
        'detalles.*.motivo' => 'required|string',
        'detalles.*.status_devolucion' => 'required|string',
    ]);

    // Crear la devolución
    $devolucion = Devolucione::create([
        'fecha_devolucion' => $request->fecha_devolucion,
        'Empleados_idEmpleados' => $request->empleado_id,
        'Recepciones_mercancias_idRecepcion_mercancia' => $request->recepcion_id,
    ]);

    // Crear los detalles de la devolución
    foreach ($request->detalles as $detalle) {
        DetallesDevolucione::create([
            'cantidad_devuelta' => $detalle['cantidad_devuelta'],
            'motivo' => $detalle['motivo'],
            'Devoluciones_idDevoluciones' => $devolucion->idDevoluciones,
            'status_devolucion' => $detalle['status_devolucion'],
            'Suministros_idSuministro' => $detalle['suministro_id'],
        ]);
    }

    return redirect()->route('devoluciones.index')->with('success', 'Devolución registrada con éxito.');
}

public function getRecepcionDetails($id)
{
    $recepcion = RecepcionesMercancia::with([
        'detalles_recepciones_mercancias.suministro', 
        'empleado',
        'ordenes_compra.detalles_ordenes_compras.suministro',
        'ordenes_compra.proveedore' // Cargar el proveedor relacionado con la orden
    ])->find($id);

    if ($recepcion) {
        return response()->json([
            'idRecepcion_mercancia' => $recepcion->idRecepcion_mercancia,
            'fecha_recepcion' => $recepcion->fecha_recepcion->format('d/m/Y'),
            'empleado' => $recepcion->empleado ? $recepcion->empleado->nombre_empleado . ' ' . $recepcion->empleado->apellido_empleado : 'Desconocido',
            'empleado_orden' => $recepcion->ordenes_compra->empleado ? $recepcion->ordenes_compra->empleado->nombre_empleado . ' ' . $recepcion->ordenes_compra->empleado->apellido_empleado : 'Desconocido',
            'proveedor' => $recepcion->ordenes_compra->proveedore ? $recepcion->ordenes_compra->proveedore->nombre_empresa : 'Desconocido',
            'detalles_recepciones_mercancias' => $recepcion->detalles_recepciones_mercancias->map(function ($detalle) {
                return [
                    'suministro' => $detalle->suministro ? $detalle->suministro->nombre_suministro : 'Desconocido',
                    'cantidad_recibida' => $detalle->cantidad_recibida,
                    'estado' => $detalle->status_recepcion == 1 ? 'Aceptado' : 'Rechazado'
                ];
            }),
            'detalles_ordenes_compras' => $recepcion->ordenes_compra->detalles_ordenes_compras->map(function ($detalle) {
                return [
                    'suministro' => $detalle->suministro ? $detalle->suministro->nombre_suministro : 'Desconocido',
                    'cantidad_pedida' => $detalle->cantidad_pedida
                ];
            })
        ]);
    }

    return response()->json(['error' => 'Recepción no encontrada'], 404);
}

}

