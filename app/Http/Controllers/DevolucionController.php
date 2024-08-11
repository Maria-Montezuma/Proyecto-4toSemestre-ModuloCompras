<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\RecepcionesMercancia;

class DevolucionController extends Controller
{
    public function index()
    {
        $empleados = Empleado::all(); // Obtener todos los empleados
        $recepciones = RecepcionesMercancia::all();
        return view('devolucion', compact('empleados', 'recepciones'));
    }
}

