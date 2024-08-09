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
}