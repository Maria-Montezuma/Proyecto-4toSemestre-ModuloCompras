<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\RecepcionesMercancia;
use App\Models\DetallesRecepcionesMercancia;

class DevolucionController extends Controller
{
    public function index()
    {
        $empleados = Empleado::all(); // Obtener todos los empleados

        // Obtener recepciones con detalles que tengan estatus 0
        $recepciones = RecepcionesMercancia::whereHas('detalles_recepciones_mercancias', function($query) {
            $query->where('status_recepcion', 0);
        })->get();

        return view('devolucion', compact('empleados', 'recepciones'));
    }
}

