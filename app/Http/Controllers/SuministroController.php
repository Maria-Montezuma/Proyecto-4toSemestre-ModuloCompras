<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Suministro;
use App\Models\ProveedoresHasSuministro;

class SuministroController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'Proveedores_idProveedores' => 'required|exists:proveedores,idProveedores',
            'nombre_suministro' => 'required|string|max:255',
            'precio_unitario' => 'required|numeric|min:0',
            'categorias_idcategorias' => 'required|exists:categorias,idcategorias',
        ]);

        $suministro = new Suministro();
        $suministro->nombre_suministro = $request->nombre_suministro;
        $suministro->precio_unitario = $request->precio_unitario;
        $suministro->categorias_idcategorias = $request->categorias_idcategorias;
        $suministro->save();

        // Crear la relación en la tabla pivot
        ProveedoresHasSuministro::create([
            'Proveedores_idProveedores' => $request->Proveedores_idProveedores,
            'Suministro_idSuministro' => $suministro->idSuministro,
        ]);

        return redirect()->back()->with('success', 'Suministro registrado correctamente.');
    }
}
