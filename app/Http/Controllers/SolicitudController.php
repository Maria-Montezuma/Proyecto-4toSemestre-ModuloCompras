<?php

namespace App\Http\Controllers;

use App\Models\Proveedore;
use App\Models\Solicitude;
use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Suministro;
use App\Models\Categoria;
use Carbon\Carbon;

    class SolicitudController extends Controller
{
    // Muestra el formulario de solicitud
    public function solicitud()
    {
        $proveedores = Proveedore::all();
        $empleados = Empleado::all();
        $solicitudes = Solicitude::with(['empleado', 'proveedores'])->get();
        $categorias = Categoria::all();  // Añade esta línea
        return view('solicitud', compact('proveedores', 'empleados', 'solicitudes', 'categorias'));
    }

    public function create()
    {
        $proveedores = Proveedore::all();
        $empleados = Empleado::all();
        $solicitudes = Solicitude::with(['empleado', 'proveedores'])->get();
        $categorias = Categoria::all();  // Añade esta línea
        return view('solicitud', compact('proveedores', 'empleados', 'solicitudes', 'categorias'));
    }

    // El método index() ya está correcto, pero puedes quitar el dd() si ya no lo necesitas
    public function index()
    {
        $solicitudes = Solicitude::with(['empleado', 'proveedores.suministros'])->get();
        $categorias = Categoria::all();
        // dd($categorias);  // Puedes quitar esta línea si ya no la necesitas
        return view('solicitud', compact('solicitudes', 'categorias'));
    }

    // Procesa la solicitud
    public function store(Request $request)
    {
        // Mensajes personalizados para las validaciones
        $messages = [
            'fecha_solicitud.required' => 'La fecha de solicitud es obligatoria.',
            'fecha_solicitud.date_equals' => 'La fecha de solicitud debe ser la fecha actual.',
            'Empleados_idEmpleados.required' => 'El ID del empleado es obligatorio.',
            'idProveedores.required' => 'El ID del proveedor es obligatorio.',
            'condicion.string' => 'La condición debe ser un texto.',
            'condicion.string' => 'La condición debe ser un texto, si se proporciona.',
            'cotizacion.required' => 'La cotización es obligatoria.',
            'cotizacion.string' => 'La cotización debe ser un texto.',
        ];
    
        // Valida los datos del formulario
        $validatedData = $request->validate([
            'fecha_solicitud' => 'required|date|date_equals:' . Carbon::now()->tz('America/Caracas')->toDateString(),
            'Empleados_idEmpleados' => 'required|exists:empleados,idEmpleados',
            'idProveedores' => 'required|exists:proveedores,idProveedores',
            'condicion' => 'nullable|string',
            'cotizacion' => 'required|string',
        ], $messages);

        // Crea una nueva solicitud
        $solicitud = Solicitude::create([
            'fecha_solicitud' => $validatedData['fecha_solicitud'],
            'Empleados_idEmpleados' => $validatedData['Empleados_idEmpleados'],
            'condicion' => $validatedData['condicion'],
            'cotizacion' => $validatedData['cotizacion'],
        ]);

        // Asocia el proveedor con la solicitud
        $solicitud->proveedores()->attach($validatedData['idProveedores']);

        // Redirige con un mensaje de éxito
        return redirect()->route('solicitud.create')->with('success', 'Solicitud procesada con éxito.');
    }

    public function storeSuministro(Request $request)
{
    $validatedData = $request->validate([
        'solicitud_id' => 'required|exists:solicitudes,idSolicitudes',
        'proveedor_id' => 'required|exists:proveedores,idProveedores',
        'nombre_suministro' => 'required|string|max:255',
        'precio_unitario' => 'required|numeric|min:0',
        'categoria_id' => 'required|exists:categorias,idcategorias',
    ]);

    $suministro = Suministro::create([
        'nombre_suministro' => $validatedData['nombre_suministro'],
        'precio_unitario' => $validatedData['precio_unitario'],
        'categorias_idcategorias' => $validatedData['categoria_id'],
    ]);

    // Asociar el suministro con el proveedor
    $suministro->proveedores()->attach($validatedData['proveedor_id']);

    // Aquí puedes agregar lógica adicional si necesitas asociar el suministro con la solicitud

    return redirect()->back()->with('success', 'Suministro registrado y asociado al proveedor con éxito.');
}


};