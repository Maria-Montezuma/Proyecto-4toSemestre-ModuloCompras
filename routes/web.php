<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\recepcionController;
use App\Http\Controllers\OrdenCompraController;
use App\Models\Solicitude;

// Ruta principal
Route::get('/', [ProveedoresController::class, 'create'])->name('proveedores');

// Rutas para proveedores
Route::get('/proveedores', [ProveedoresController::class, 'create'])->name('proveedores.create');
Route::post('/proveedores', [ProveedoresController::class, 'store'])->name('proveedores.store');
Route::get('/proveedores/{proveedor}/edit', [ProveedoresController::class, 'edit'])->name('proveedores.edit');
Route::put('/proveedores/{id}', [ProveedoresController::class, 'update'])->name('proveedores.update');
Route::delete('/proveedores/{id}', [ProveedoresController::class, 'delete'])->name('proveedores.delete');
Route::get('/proveedores/solicitud', [SolicitudController::class, 'solicitud'])->name('solicitud');
Route::get('/proveedores/search', [ProveedoresController::class, 'search'])->name('proveedores.search');



// Rutas para otras vistas
Route::get('/ordencompra', function () {
    return view('ordencompra');
})->name('ordencompra');

Route::get('/ordencompra', [OrdenCompraController::class, 'create'])->name('ordencompra');
Route::post('/ordencompra', [OrdenCompraController::class, 'store'])->name('ordencompra.store');
Route::get('/ordencompra/create', [OrdenCompraController::class, 'create'])->name('ordencompra.create');
Route::get('/ordenescompra/search', [OrdenCompraController::class, 'search'])->name('ordenescompra.search');
Route::get('/ordenescompra/{id}', [OrdenCompraController::class, 'show'])->name('ordenescompra.show');
Route::patch('/ordenescompra/{id}/cancel', [OrdenCompraController::class, 'cancel'])->name('ordenescompra.cancel');


// ruta de suminsitrosXproveedor
Route::get('/get-suministros-por-proveedor/{idProveedor}', [OrdenCompraController::class, 'getSuministrosPorProveedor']);

Route::get('/recepcion', function () {
    return view('recepcion');
})->name('recepcion');

Route::get('/recepcion', function () {
    return view('recepcion');
})->name('recepcion');

Route::get('/recepcion', [recepcionController::class, 'create'])->name('recepcion');
Route::get('/recepcion/create', [RecepcionController::class, 'create'])->name('recepcion.create');
Route::post('/recepcion/store', [RecepcionController::class, 'store'])->name('recepcion.store');
Route::get('/recepcion/search', [RecepcionController::class, 'search'])->name('recepcion.search');
Route::get('/recepcion/{id}/edit', [RecepcionController::class, 'edit'])->name('recepcion.edit');
Route::put('/recepcion/{id}', [RecepcionController::class, 'update'])->name('recepcion.update');

Route::get('/api/orden-compra/{id}', [RecepcionController::class, 'getOrdenCompraDetails']);


Route::get('/devolucion', function () {
    return view('devolucion');
})->name('devolucion');

// Solicitudes
Route::get('/solicitud', [SolicitudController::class, 'create'])->name('solicitud.create');
Route::post('/solicitud', [SolicitudController::class, 'store'])->name('solicitud.store');