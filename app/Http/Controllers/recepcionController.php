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
    $recepcionesMercancia = RecepcionesMercancia::with(['ordenCompra', 'empleado', 'proveedor', 'suministro'])->get();
    $empleados = Empleado::all();
    // Filtrar las órdenes de compra con status 'recibida' (asumiendo que es 2)
    $ordenesCompra = OrdenesCompra::where('status', 2)->get();
    $suministros = Suministro::all();
    return view('recepcion', compact('empleados', 'ordenesCompra', 'suministros', 'recepcionesMercancia'));
}

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fechaRecepcion' => 'required|date|after_or_equal:today',
            'cantidadRecibida' => 'required|numeric|min:0',
            'Empleados_idEmpleados' => 'required|exists:empleados,idEmpleados',
            'Ordenes_compras_idOrden_compra' => 'required|exists:ordenes_compras,idOrden_compra',
            'estado' => 'required|string',
        ], [
            'fechaRecepcion.required' => 'La fecha de recepción es obligatoria.',
            'fechaRecepcion.date' => 'La fecha de recepción debe ser una fecha válida.',
            'fechaRecepcion.after_or_equal' => 'La fecha de recepción no puede ser antes de la fecha actual.',
            'cantidadRecibida.required' => 'La cantidad recibida es obligatoria.',
            'cantidadRecibida.numeric' => 'La cantidad recibida debe ser un número.',
            'cantidadRecibida.min' => 'La cantidad recibida debe ser un valor positivo.',
            'Empleados_idEmpleados.required' => 'El empleado es obligatorio.',
            'Empleados_idEmpleados.exists' => 'El empleado seleccionado no existe.',
            'Ordenes_compras_idOrden_compra.required' => 'La orden de compra es obligatoria.',
            'Ordenes_compras_idOrden_compra.exists' => 'La orden de compra seleccionada no existe.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.string' => 'El estado debe ser una cadena de texto válida.',
        ]);

        $recepcion = new RecepcionesMercancia;
        $recepcion->fecha_recepcion = Carbon::now();
        $recepcion->cantidad_recibida = $validatedData['cantidadRecibida'];
        $recepcion->Empleados_idEmpleados = $validatedData['Empleados_idEmpleados'];
        $recepcion->Ordenes_compras_idOrden_compra = $validatedData['Ordenes_compras_idOrden_compra'];
        $recepcion->Suministros_idSuministros = $request->input('suministro');
        $recepcion->status = $validatedData['estado'];
        $recepcion->save();

        return redirect()->route('recepcion.create')->with('success', 'Recepción de mercancia registrada exitosamente');
    }

    public function getOrdenCompraDetails($id)
{
    $ordenCompra = OrdenesCompra::with(['detalles_ordenes_compras.suministro', 'proveedore'])
        ->findOrFail($id);
    
    Log::info('Orden de compra: ', ['orden' => $ordenCompra->toArray()]);
    Log::info('Proveedor: ', ['proveedor' => $ordenCompra->proveedore ? $ordenCompra->proveedore->toArray() : 'null']);

    $detalles = $ordenCompra->detalles_ordenes_compras->map(function ($detalle) {
        return [
            'suministro_id' => $detalle->Suministro_idSuministro,
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
