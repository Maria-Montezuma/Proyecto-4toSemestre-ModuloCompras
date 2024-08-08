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

/**
 * Determina el estado general de la recepción de mercancía.
 *
 * @param array $estados Estado de cada suministro (0 = Rechazado, 1 = Aceptado)
 * @param array $cantidades Cantidades recibidas de cada suministro
 * @return int Estado general de la recepción (0 = Todos rechazados, 1 = Todos aceptados, 2 = Parcial)
 */
private function determinarEstadoGeneral($estados, $cantidades)
{
    $aceptados = 0;
    $rechazados = 0;
    $total_items = count($estados);

    foreach ($estados as $key => $estado) {
        if ($estado == 1) {
            $aceptados++;
            $cantidades[$key] = $cantidades[$key]; // Mantener la cantidad recibida
        } elseif ($estado == 0) {
            $rechazados++;
            $cantidades[$key] = 0; // Establecer la cantidad recibida a 0 para los rechazados
        }
    }

    if ($aceptados === $total_items) {
        return 1; // Todos aceptados
    } elseif ($rechazados === $total_items) {
        return 0; // Todos rechazados
    } else {
        return 2; // Parcial
    }
}


   public function create()
{
    $recepcionesMercancia = RecepcionesMercancia::with(['ordenes_compra.proveedore', 'empleado'])->orderBy('idRecepcion_mercancia', 'desc')->get();
        $empleados = Empleado::all();

        // Obtener solo las órdenes de compra que no han sido recibidas
        $ordenesCompra = OrdenesCompra::where('status', 2)
            ->whereNotIn('idOrden_compra', function ($query) {
                $query->select('Ordenes_compras_idOrden_compra')
                      ->from('recepciones_mercancias');
            })
            ->get();

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

         // Verificar si la orden de compra ya ha sido registrada
         $ordenCompraId = $validatedData['Ordenes_compras_idOrden_compra'];
         $recepcionExistente = RecepcionesMercancia::where('Ordenes_compras_idOrden_compra', $ordenCompraId)->first();
 
         if ($recepcionExistente) {
             return redirect()->route('recepcion.create')->with('error', 'Esta orden de compra ya ha sido registrada en una recepción.');
         }
 
         // Si no existe, proceder con el registro
         $recepcion = new RecepcionesMercancia;
         $recepcion->fecha_recepcion = Carbon::now();
         $recepcion->Empleados_idEmpleados = $validatedData['Empleados_idEmpleados'];
         $recepcion->Ordenes_compras_idOrden_compra = $validatedData['Ordenes_compras_idOrden_compra'];
 
         // Calcular la cantidad total recibida
         $cantidadTotalRecibida = array_sum($validatedData['cantidadRecibida']);
         $recepcion->cantidad_recibida = $cantidadTotalRecibida;
 
         // Determinar el estado general de la recepción
         $recepcion->status = $this->determinarEstadoGeneral($validatedData['estado'], $validatedData['cantidadRecibida']);
 
         $recepcion->save();
 
         return redirect()->route('recepcion.create')->with('success', 'Recepción de mercancía registrada exitosamente');
     }

    // cierre de prueba

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


public function edit($id)
    {
        $recepcion = RecepcionesMercancia::with(['ordenes_compra.detalles_ordenes_compras.suministro', 'empleado'])->findOrFail($id);
        $empleados = Empleado::all();

        // Obtener los detalles de la recepción
        $detallesRecepcion = $recepcion->ordenes_compra->detalles_ordenes_compras->map(function ($detalle) use ($recepcion) {
            return [
                'Suministro_idSuministro' => $detalle->Suministro_idSuministro,
                'nombre_suministro' => $detalle->suministro->nombre_suministro,
                'cantidad_pedida' => $detalle->cantidad_pedida,
                'cantidad_recibida' => $recepcion->cantidad_recibida, // Esto asume que la cantidad recibida es la misma para todos los suministros
                'status' => $recepcion->status
            ];
        });

        return view('recepcionedit', compact('recepcion', 'empleados', 'detallesRecepcion'));
    }

    public function update(Request $request, $id)
{
    $recepcion = RecepcionesMercancia::findOrFail($id);

    $validatedData = $request->validate([
        'fecha_recepcion' => 'required|date',
        'Empleados_idEmpleados' => 'required|exists:empleados,idEmpleados',
        'cantidad_recibida' => 'required|array',
        'cantidad_recibida.*' => 'required|integer|min:0',
        'estado' => 'required|array',
        'estado.*' => 'required|in:0,1',
    ]);

    $recepcion->fecha_recepcion = $validatedData['fecha_recepcion'];
    $recepcion->Empleados_idEmpleados = $validatedData['Empleados_idEmpleados'];

    // Update the received quantities and statuses
    $totalRecibido = 0;
    foreach ($validatedData['cantidad_recibida'] as $key => $cantidad) {
        $recepcion->ordenes_compra->detalles_ordenes_compras[$key]->cantidad_recibida = $cantidad;
        $recepcion->ordenes_compra->detalles_ordenes_compras[$key]->status = $validatedData['estado'][$key];
        $totalRecibido += $cantidad;
    }

    $recepcion->cantidad_recibida = $totalRecibido;

    // Determine the overall status of the reception
    $recepcion->status = $this->determinarEstadoGeneral($validatedData['estado'], $validatedData['cantidad_recibida']);

    $recepcion->save();

    return redirect()->route('recepcion.create')->with('success', 'Recepción de mercancía actualizada exitosamente');
}
}
