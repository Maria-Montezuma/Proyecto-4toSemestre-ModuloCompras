<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\OrdenesCompra;
use App\Models\Proveedore;
use App\Models\DetallesOrdenesCompra;
use Carbon\Carbon;
use App\Models\ProveedoresHasSuministro;

class OrdenCompraController extends Controller
{
    public function create()
    {
        $ordenesCompra = OrdenesCompra::with(['proveedore', 'empleado'])->orderBy('idOrden_compra', 'desc')->get();

        // Actualizar el estado de las órdenes si es necesario
    foreach ($ordenesCompra as $orden) {
    $orden->actualizarEstadoSiNecesario();
    }

    $empleados = Empleado::all();
    $proveedores = Proveedore::all();
    return view('ordencompra', compact('empleados', 'proveedores', 'ordenesCompra'));

    
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
         'fecha_entraga' => 'required|date|after_or_equal:today',
        'Empleados_idEmpleados' => 'required|exists:empleados,idEmpleados',
        'Proveedores_idProveedores' => 'required|exists:proveedores,idProveedores',
        'suministros' => 'required|array',
        'cantidades' => 'required|array',
        'precios' => 'required|array',
        'subtotales' => 'required|array',
    ], [
        'fecha_entraga.after_or_equal' => 'La fecha de entrega no puede ser antes de la fecha actual',
        'fecha_entraga.required' => 'La fecha de entrega es obligatoria.',
        'fecha_entraga.date' => 'La fecha de entrega debe ser una fecha válida.',
        'Empleados_idEmpleados.required' => 'El empleado es obligatorio.',
        'Empleados_idEmpleados.exists' => 'El empleado seleccionado no existe.',
        'Proveedores_idProveedores.required' => 'El proveedor es obligatorio.',
        'Proveedores_idProveedores.exists' => 'El proveedor seleccionado no existe.',
        'suministros.required' => 'Los suministros son obligatorios.',
        'suministros.array' => 'Los suministros deben ser una lista.',
        'cantidades.required' => 'Las cantidades son obligatorias.',
        'cantidades.array' => 'Las cantidades deben ser una lista.',
        'precios.required' => 'Los precios son obligatorios.',
        'precios.array' => 'Los precios deben ser una lista.',
        'subtotales.required' => 'Los subtotales son obligatorios.',
        'subtotales.array' => 'Los subtotales deben ser una lista.',
    ]);

    $ordenCompra = new OrdenesCompra();
    $ordenCompra->fecha_emision = Carbon::now()->setTimezone('America/Caracas');
    $ordenCompra->fecha_entraga = $validatedData['fecha_entraga'];
    $ordenCompra->status = 1;
    $ordenCompra->Empleados_idEmpleados = $validatedData['Empleados_idEmpleados'];
    $ordenCompra->Proveedores_idProveedores = $validatedData['Proveedores_idProveedores'];
    $subtotal_pagar = array_sum($request->subtotales);
    $ordenCompra->subtotal_pagar = $subtotal_pagar;
    $ordenCompra->total_pagar = $subtotal_pagar; // Aquí puedes agregar los impuestos si es necesario
     
    $ordenCompra->enviado_at = now();
    
    $ordenCompra->save();

    // Guardar los detalles de la orden de compra
    for ($i = 0; $i < count($request->suministros); $i++) {
        $detalle = new DetallesOrdenesCompra();
        $detalle->Ordenes_compra_idOrden_compra = $ordenCompra->idOrden_compra;
        $detalle->Suministro_idSuministro = $request->suministros[$i];
        $detalle->cantidad_pedida = $request->cantidades[$i];
        $detalle->precio_unitario = $request->precios[$i];
        $detalle->subtotal = $request->subtotales[$i];
        $detalle->save();
    }

    return redirect()->route('ordencompra')->with('success', 'Orden de compra creada exitosamente');
}

    public function getSuministrosPorProveedor($idProveedor)
    {
        $suministros = ProveedoresHasSuministro::where('Proveedores_idProveedores', $idProveedor)
            ->with('suministro')
            ->get()
            ->map(function ($item) {
                return $item->suministro;
            });
        
        return response()->json($suministros);
    }

    public function search(Request $request)
{
    $query = OrdenesCompra::query();

    if ($request->has('proveedor')) {
        $query->where('Proveedores_idProveedores', $request->proveedor);
    }

    $ordenesCompra = $query->get();

    $proveedores = Proveedore::all();

    return view('ordenescompra.index', compact('ordenesCompra', 'proveedores'));
}

    // prueba
    public function cancel($id)
    {
        $ordenCompra = OrdenesCompra::findOrFail($id);
        $ordenCompra->actualizarEstadoSiNecesario();
        if (!$ordenCompra->esCancelable()) {
        return redirect()->route('ordencompra')->with('error', 'Esta orden ya no puede ser cancelada.');
    }

    $ordenCompra->status = 0;
    $ordenCompra->save();

    return redirect()->route('ordencompra')->with('success', 'Orden de compra cancelada exitosamente');
}

public function show($id)
{
    $orden = OrdenesCompra::findOrFail($id);
    $response = [
        'id' => $orden->idOrden_compra,
        'fecha_emision' => $orden->fecha_emision,
        'fecha_entraga' => $orden->fecha_entraga,
        'status' => $orden->status,
        'total_pagar' => $orden->total_pagar,
        'proveedor' => $orden->proveedore ? [
            'id' => $orden->proveedore->idProveedores,
            'nombre' => $orden->proveedore->nombre_empresa,
        ] : null,
        'empleado' => $orden->empleado ? [
            'id' => $orden->empleado->idEmpleados,
            'nombre' => $orden->empleado->nombre_empleado . ' ' . $orden->empleado->apellido_empleado,
        ] : null,
        'detalles' => $orden->detalles_ordenes_compras->map(function($detalle) {
            return [
                'id' => $detalle->idDetalles_Ordenes_compra,
                'cantidad' => $detalle->cantidad_pedida,
                'precio_unitario' => $detalle->precio_unitario,
                'subtotal' => $detalle->subtotal,
                'suministro' => $detalle->suministro ? [
                    'id' => $detalle->suministro->idSuministro,
                    'nombre' => $detalle->suministro->nombre_suministro,
                ] : null,
            ];
        }),
    ];
    
    return response()->json($response);
}

}