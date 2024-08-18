<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\RecepcionMercanciaController;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\SuministroController;

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

// Rutas para orden de compra
Route::get('/ordencompra', [OrdenCompraController::class, 'create'])->name('ordencompra');
Route::post('/ordencompra', [OrdenCompraController::class, 'store'])->name('ordencompra.store');
Route::get('/ordencompra/create', [OrdenCompraController::class, 'create'])->name('ordencompra.create');
Route::get('/ordenescompra/search', [OrdenCompraController::class, 'search'])->name('ordenescompra.search');
Route::get('/ordenescompra/{id}', [OrdenCompraController::class, 'show'])->name('ordenescompra.show');
Route::patch('/ordenescompra/{id}/cancel', [OrdenCompraController::class, 'cancel'])->name('ordenescompra.cancel');

// Ruta de suminsitrosXproveedor
Route::get('/get-suministros-por-proveedor/{idProveedor}', [OrdenCompraController::class, 'getSuministrosPorProveedor']);

//Rutas de recepcion 
Route::get('/recepcion', [RecepcionMercanciaController::class, 'create'])->name('recepcion');
Route::get('/recepcion/create', [RecepcionMercanciaController::class, 'create'])->name('recepcion.create');
Route::post('/recepcion/store', [RecepcionMercanciaController::class, 'store'])->name('recepcion.store');
Route::get('/recepcion/{id}/edit', [RecepcionMercanciaController::class, 'edit'])->name('recepcion.edit');
Route::put('/recepcion/{id}', [RecepcionMercanciaController::class, 'update'])->name('recepcion.update');
Route::get('/recepcion/{id}', [RecepcionMercanciaController::class, 'show']);
Route::get('/get-orden-compra-details/{id}', [RecepcionMercanciaController::class, 'getDetails']);

// Rutas de devolucion 
Route::get('/devolucion', [DevolucionController::class, 'index'])->name('devolucion');
Route::post('/devolucion/store', [DevolucionController::class, 'store'])->name('devolucion.store');
Route::delete('/devolucion/{id}/cancel', [DevolucionController::class, 'cancel'])->name('devolucion.cancel');
Route::get('/recepcion-details/{id}', [DevolucionController::class, 'getRecepcionDetails']);

// Ruta para solicitud
Route::get('/solicitud', [SolicitudController::class, 'create'])->name('solicitud.create');
Route::post('/solicitud', [SolicitudController::class, 'store'])->name('solicitud.store');
Route::post('/suministro', [SuministroController::class, 'store'])->name('suministro.store');
Route::get('/proveedor/{id}/categorias', [SuministroController::class, 'getCategoriasPorProveedor'])
->name('proveedor.categorias');
