<?php

namespace App\Http\Controllers;

use App\Models\RecepcionesMercancia;
use App\Models\OrdenesCompra;
use App\Models\Suministro;
use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RecepcionController extends Controller
{
    public function create()
{
    $recepcionesMercancia = RecepcionesMercancia::with(['ordenes_compra', 'empleado'])->get();
    $empleados = Empleado::all();
    // Filtrar las órdenes de compra con status 'recibida' (asumiendo que es 2)
    $ordenesCompra = OrdenesCompra::where('status', 2)->get();
    $suministros = Suministro::all(['idSuministro', 'nombre_suministro']);
    
    return view('recepcion', compact('empleados', 'ordenesCompra', 'suministros', 'recepcionesMercancia'));
}

public function store(Request $request)
{
    $validatedData = $request->validate([
        'fecha_recepcion' => 'required|date',
        'Empleados_idEmpleados' => 'required|exists:empleados,idEmpleados',
        'Ordenes_compras_idOrden_compra' => 'required|exists:ordenes_compras,idOrden_compra',
        'cantidadPedida' => 'required|array',
        'cantidadPedida.*' => 'required|numeric|min:0',
        'cantidadRecibida' => 'required|array',
        'cantidadRecibida.*' => 'required|numeric|min:0',
        'estado' => 'required|array',
        'estado.*' => 'required|in:aceptar,rechazar',
    ], [
            'fecha_recepcion.required' => 'La fecha de recepción es obligatoria.',
            'fecha_recepcion.date' => 'La fecha de recepción debe ser una fecha válida.',
            'Empleados_idEmpleados.required' => 'El empleado es obligatorio.',
            'Empleados_idEmpleados.exists' => 'El empleado seleccionado no existe.',
            'Ordenes_compras_idOrden_compra.required' => 'La orden de compra es obligatoria.',
            'Ordenes_compras_idOrden_compra.exists' => 'La orden de compra seleccionada no existe.',
            'cantidadPedida.required' => 'Debe especificar la cantidad pedida para cada suministro.',
            'cantidadPedida.*.required' => 'La cantidad pedida es obligatoria para cada suministro.',
            'cantidadPedida.*.numeric' => 'La cantidad pedida debe ser un número.',
            'cantidadPedida.*.min' => 'La cantidad pedida debe ser un valor positivo.',
            'cantidadRecibida.required' => 'Debe especificar la cantidad recibida para cada suministro.',
            'cantidadRecibida.*.required' => 'La cantidad recibida es obligatoria para cada suministro.',
            'cantidadRecibida.*.numeric' => 'La cantidad recibida debe ser un número.',
            'cantidadRecibida.*.min' => 'La cantidad recibida debe ser un valor positivo.',
            'estado.required' => 'Debe especificar el estado para cada suministro.',
            'estado.*.required' => 'El estado es obligatorio para cada suministro.',
            'estado.*.in' => 'El estado debe ser "aceptar" o "rechazar".',
        ]);

        $recepcion = new RecepcionesMercancia;
    $recepcion->fecha_recepcion = Carbon::now();
    $recepcion->Empleados_idEmpleados = $validatedData['Empleados_idEmpleados'];
    $recepcion->Ordenes_compras_idOrden_compra = $validatedData['Ordenes_compras_idOrden_compra'];
    
    // Calcular la cantidad total recibida
    $cantidadTotalRecibida = array_sum($validatedData['cantidadRecibida']);
    $recepcion->cantidad_recibida = $cantidadTotalRecibida;
    
    // Determinar el estado general de la recepción
    $estadoGeneral = in_array('rechazar', $validatedData['estado']) ? 0 : 1;
    $recepcion->status = $estadoGeneral;
    
    $recepcion->save();

    return redirect()->route('recepcion.create')->with('success', 'Recepción de mercancía registrada exitosamente');
    }

    public function getOrdenCompraDetails($id)
{
    $ordenCompra = OrdenesCompra::with(['detalles_ordenes_compras.suministro', 'proveedore'])
        ->findOrFail($id);
    
    Log::info('Orden de compra: ', ['orden' => $ordenCompra->toArray()]);
    Log::info('Proveedor: ', ['proveedor' => $ordenCompra->proveedore ? $ordenCompra->proveedore->toArray() : 'null']);

    $detalles = $ordenCompra->detalles_ordenes_compras->map(function ($detalle) {
        return [
            'Suministro_idSuministro' => $detalle->Suministro_idSuministro,
            'nombre_suministro' => $detalle->suministro->nombre_suministro,
            'cantidad_pedida' => $detalle->cantidad_pedida,
            'precio_unitario' => $detalle->precio_unitario,
            'subtotal' => $detalle->subtotal
        ];
    });

    return response()->json([
        'orden_compra' => [
            'id' => $ordenCompra->idOrden_compra,
            'fecha_emision' => $ordenCompra->fecha_emision->format('Y-m-d'),
            'fecha_entrega' => $ordenCompra->fecha_entraga->format('Y-m-d'),
            'proveedor' => $ordenCompra->proveedore ? $ordenCompra->proveedore->nombre_empresa : 'No especificado',
            'subtotal' => $ordenCompra->subtotal_pagar,
            'total' => $ordenCompra->total_pagar,
        ],
        'detalles' => $detalles
    ]);
}
}
