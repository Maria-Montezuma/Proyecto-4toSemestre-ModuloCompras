<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Suministro;
use App\Models\OrdenesCompra;
use App\Models\DetallesRecepcionesMercancia;
use Carbon\Carbon;
use App\Models\RecepcionesMercancia;

class RecepcionMercanciaController extends Controller
{
    // mostrar empleados y id de orden de compra en recepcion
    public function create()
    {
        $recepcionesMercancia = RecepcionesMercancia::with(['ordenes_compra.proveedore', 'empleado'])->orderBy('idRecepcion_mercancia', 'desc')->get();
        $empleados = Empleado::all();
        
        // Obtener solo las órdenes de compra que no han sido recibidas
        $ordenesCompra = OrdenesCompra::where('status', 2)
            ->whereNotIn('idOrden_compra', function($query) {
                $query->select('Ordenes_compras_idOrden_compra')
                      ->from('recepciones_mercancias');
            })
            ->get();
        
        $suministros = Suministro::all(['idSuministro', 'nombre_suministro']);
        
        return view('recepcion', compact('empleados', 'ordenesCompra', 'suministros', 'recepcionesMercancia'));
    }


    // traer los detalles de orden de compra a recepcion
    public function getDetails($id)
{
    $ordenCompra = OrdenesCompra::with(['proveedore', 'detalles_ordenes_compras.suministro'])
        ->findOrFail($id);

    $detalles = [
        'fecha_emision' => Carbon::parse($ordenCompra->fecha_emision)->format('d/m/Y'),
        'fecha_entraga' => Carbon::parse($ordenCompra->fecha_entraga)->format('d/m/Y'),
        'proveedor' => $ordenCompra->proveedore->nombre_empresa,
        'subtotal_pagar' => number_format($ordenCompra->subtotal_pagar, 2),
        'total_pagar' => number_format($ordenCompra->total_pagar, 2),
        'productos' => $ordenCompra->detalles_ordenes_compras->map(function($detalle) {
            return [
                'nombre_suministro' => $detalle->suministro->nombre_suministro,
                'cantidad' => $detalle->cantidad_pedida,
                'precio_unitario' => number_format($detalle->precio_unitario, 2),
                'subtotal' => number_format($detalle->subtotal, 2)
            ];
        })
    ];

    return response()->json($detalles);
}
public function store(Request $request)
{
    $validatedData = $request->validate([
        'Ordenes_compras_idOrden_compra' => 'required|exists:ordenes_compras,idOrden_compra',
        'Empleados_idEmpleados' => 'required|exists:empleados,idEmpleados',
        'suministro' => 'required|array|min:1',  
        'suministro.*' => 'required|exists:suministros,idSuministro',  
        'cantidad_recibida' => 'required|array|min:1',  
        'cantidad_recibida.*' => 'required|integer|min:1',  
        'status' => 'required|array|min:1',  
        'status.*' => 'required|in:aceptar,rechazar',
         "rechazar"
    ],[
        'Ordenes_compras_idOrden_compra' => 'La orden de compra debe ser existente',
        'Empleados_idEmpleados' => 'El empleado seleccionado no exite',
        'suministro' => 'La cantidad minima es 1',  
        'suministro.*' => 'El suministro seleccionado no exite',  
        'cantidad_recibida' => 'La cantidad recibida minimo debe ser 1',  
        'cantidad_recibida.*' => 'La cantidad recibida debe ser un numero entero',  
        'status' => 'Debe seleccionar el estatus',  
        'status.*' => 'El estatus debe ser aceptar o rechazar',
         "rechazar"
    ]);

    $recepcion = new RecepcionesMercancia();
    $recepcion->fecha_recepcion = Carbon::now()->setTimezone('America/Caracas');
    $recepcion->Empleados_idEmpleados = $validatedData['Empleados_idEmpleados'];
    $recepcion->save();

    // Crea la recepción de mercancía
    $recepcion = RecepcionesMercancia::create([
        'Ordenes_compras_idOrden_compra' => $request->Ordenes_compras_idOrden_compra,
        'Empleados_idEmpleados' => $request->Empleados_idEmpleados,
        'fecha_recepcion' => $request->fecha_recepcion,
    ]);

    // Crea los detalles de la recepción de mercancía
    foreach ($request->suministro as $index => $idSuministro) {
        DetallesRecepcionesMercancia::create([
            'cantidad_recibida' => $request->cantidad_recibida[$index],
            'status_recepcion' => $request->status[$index] === 'aceptar' ? 1 : 0,
            'Recepciones_mercancias_idRecepcion_mercancia' => $recepcion->idRecepcion_mercancia,
            'Suministros_idSuministro' => $idSuministro
        ]);
    }

    return redirect()->route('recepcion')->with('success', 'Recepción de mercancía creada exitosamente.');
}


public function show($id)
{
    $recepcion = RecepcionesMercancia::with([
        'empleado', 
        'ordenes_compra.detalles_ordenes_compras.suministro',
        'ordenes_compra.proveedore',
        'detalles_recepciones_mercancias.suministro'
    ])->findOrFail($id);

    $detallesOrdenCompra = $recepcion->ordenes_compra->detalles_ordenes_compras->keyBy('Suministro_idSuministro');

    $detallesRecepcion = $recepcion->detalles_recepciones_mercancias->map(function ($detalle) use ($detallesOrdenCompra) {
        $detalleOrdenCompra = $detallesOrdenCompra->get($detalle->Suministros_idSuministro);
        
        return [
            'suministro_pedido' => $detalleOrdenCompra ? $detalleOrdenCompra->suministro->nombre_suministro : 'N/A',
            'suministro_recibido' => $detalle->suministro->nombre_suministro,
            'cantidad_pedida' => $detalleOrdenCompra ? $detalleOrdenCompra->cantidad_pedida : 'N/A',
            'cantidad_recibida' => $detalle->cantidad_recibida,
            'precio_unitario' => $detalleOrdenCompra ? $detalleOrdenCompra->precio_unitario : 'N/A',
            'subtotal' => $detalleOrdenCompra ? $detalleOrdenCompra->subtotal : 'N/A',
            'status_recepcion' => $detalle->status_recepcion
        ];
    });

    $recepcion = $recepcion->toArray();
    $recepcion['detalles'] = $detallesRecepcion;

    return response()->json($recepcion);
}
// editar recepcion
public function edit($id)
{
    $recepcion = RecepcionesMercancia::with(['empleado', 'ordenes_compra.proveedore', 'detalles_recepciones_mercancias.suministro'])->findOrFail($id);
    $empleados = Empleado::all();
    $ordenesCompra = OrdenesCompra::all();
    $suministros = Suministro::all();

    return view('recepcionedit', compact('recepcion', 'empleados', 'ordenesCompra', 'suministros'));
}

public function update(Request $request, $id)
{
    $recepcion = RecepcionesMercancia::findOrFail($id);

    $validatedData = $request->validate([
        'Ordenes_compras_idOrden_compra' => 'required|exists:ordenes_compras,idOrden_compra',
        'Empleados_idEmpleados' => 'required|exists:empleados,idEmpleados',
        'fecha_recepcion' => 'required|date',
        'suministro' => 'required|array',
        'cantidad_recibida' => 'required|array',
        'status' => 'required|array',
    ]);

    $recepcion->update([
        'Ordenes_compras_idOrden_compra' => $validatedData['Ordenes_compras_idOrden_compra'],
        'Empleados_idEmpleados' => $validatedData['Empleados_idEmpleados'],
        'fecha_recepcion' => $validatedData['fecha_recepcion'],
    ]);

    // Actualizar o crear detalles de recepción
    foreach ($request->suministro as $index => $suministroId) {
        $recepcion->detalles_recepciones_mercancias()->updateOrCreate(
            ['Suministros_idSuministro' => $suministroId],
            [
                'cantidad_recibida' => $request->cantidad_recibida[$index],
                'status_recepcion' => $request->status[$index] == 'aceptar' ? 1 : 0,
            ]
        );
    }

    return redirect()->route('recepcion.create')->with('success', 'Recepción actualizada exitosamente.');
}
}