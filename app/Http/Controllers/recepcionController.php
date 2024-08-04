<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Suministro;
use App\Models\OrdenesCompra;
use App\Models\RecepcionesMercancia;

class recepcionController extends Controller
{
    public function create()
    {
        $suministro = Suministro::all();
        $recepcion = RecepcionesMercancia::with('suministro')->get();
        return view('recepcion', compact('suministro', 'recepcion'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'numeroOrden' => 'required|numeric',
            'numeroRecepcion' => 'required|numeric',
            'fechaRecepcion' => 'required|date|date_equals:'.date('Y-m-d'),
            'cantidadRecibida' => 'required|integer|min:0',
            'proveedor' => 'required|string|max:255',
            'suministro' => 'required|string|max:255',
            'estado' => 'required|string|in:bueno,dañado,incompleto',
        ], [
            'numeroOrden.required' => 'El número de orden es obligatorio.',
            'numeroOrden.numeric' => 'El número de orden debe ser numérico.',
            'numeroRecepcion.required' => 'El número de recepción es obligatorio.',
            'numeroRecepcion.numeric' => 'El número de recepción debe ser numérico.',
            'fechaRecepcion.required' => 'La fecha de recepción es obligatoria.',
            'fechaRecepcion.date' => 'La fecha de recepción debe ser una fecha válida.',
            'fechaRecepcion.date_equals' => 'La fecha de recepción debe ser la fecha actual.',
            'cantidadRecibida.required' => 'La cantidad recibida es obligatoria.',
            'cantidadRecibida.integer' => 'La cantidad recibida debe ser un número entero.',
            'cantidadRecibida.min' => 'La cantidad recibida no puede ser negativa.',
            'proveedor.required' => 'El proveedor es obligatorio.',
            'suministro.required' => 'El suministro es obligatorio.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser uno de los siguientes: bueno, dañado, incompleto.',
        ]);

        $recepcion = new RecepcionesMercancia();
  
        $recepcion->fecha_recepcion = $request->input('fechaRecepcion');
        $recepcion->cantidad_recibida = $request->input('cantidadRecibida');
       
        if ($recepcion->save()) {
            
            $suministro = new Suministro();
            $suministro->nombre_suministro = $request->input('suministro');
            $suministro->save();

            $OrdenesCompra = new OrdenesCompra();
            $OrdenesCompra->idOrden_compra = $request->input('Numero_de_orden');
            $OrdenesCompra->save();

            return redirect()->route('recepcion.create')->with('success', 'Recepción creada exitosamente.');
        } else {
            return redirect()->route('recepcion.create')->with('error', 'No se pudo crear la recepción.');
        }
    }

    public function edit($id)
    {
        $recepcion = RecepcionesMercancia::find($id);
        if (!$recepcion) {
            return redirect()->route('recepcion.create')->with('error', 'Recepción no encontrada.');
        }

        $suministro = Suministro::all();
        return view('recepcionedit', compact('recepcion', 'suministro'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'numeroOrden' => 'required|numeric',
            'numeroRecepcion' => 'required|numeric',
            'fechaRecepcion' => 'required|date|date_equals:'.date('Y-m-d'),
            'cantidadRecibida' => 'required|integer|min:0',
            'proveedor' => 'required|string|max:255',
            'suministro' => 'required|string|max:255',
            'estado' => 'required|string|in:bueno,dañado,incompleto',
        ], [
            'numeroOrden.required' => 'El número de orden es obligatorio.',
            'numeroOrden.numeric' => 'El número de orden debe ser numérico.',
            'numeroRecepcion.required' => 'El número de recepción es obligatorio.',
            'numeroRecepcion.numeric' => 'El número de recepción debe ser numérico.',
            'fechaRecepcion.required' => 'La fecha de recepción es obligatoria.',
            'fechaRecepcion.date' => 'La fecha de recepción debe ser una fecha válida.',
            'fechaRecepcion.date_equals' => 'La fecha de recepción debe ser la fecha actual.',
            'cantidadRecibida.required' => 'La cantidad recibida es obligatoria.',
            'cantidadRecibida.integer' => 'La cantidad recibida debe ser un número entero.',
            'cantidadRecibida.min' => 'La cantidad recibida no puede ser negativa.',
            'proveedor.required' => 'El proveedor es obligatorio.',
            'suministro.required' => 'El suministro es obligatorio.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser uno de los siguientes: bueno, dañado, incompleto.',
        ]);

        $recepcion = RecepcionesMercancia::findOrFail($id);

        $recepcion->update([
            'fecha_recepcion' => $request->input('fechaRecepcion'),
            'cantidad_recibida' => $request->input('cantidadRecibida'),
            'estado' => $request->input('estado'),
        ]);

        $suministro = Suministro::find($recepcion->suministro_id);
        $suministro->nombre_suministro = $request->input('suministro');
        $suministro->save();

        $OrdenesCompra = OrdenesCompra::find($recepcion->orden_compra_id);
        $OrdenesCompra->idOrden_compra = $request->input('numeroOrden');
        $OrdenesCompra->save();

        return redirect()->route('recepcion.create')->with('success', 'Recepción actualizada correctamente.');
    }
}
