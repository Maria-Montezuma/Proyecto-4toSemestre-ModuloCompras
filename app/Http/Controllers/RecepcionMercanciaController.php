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
}