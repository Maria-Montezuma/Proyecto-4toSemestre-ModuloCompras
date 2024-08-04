@extends('layouts.layout')

@section('content')
<div class="container formulario-container mt-5">
    <h2 class="mb-4 text-center">Solicitar Devolucion</h2>
    <form>
        <div class="row mb-3">
            <div class="col-md-4 ">
                <label for="devolucion">ID de Devolucion</label>
                <input type="number" class="form-control mt-2" id="devolucion" required placeholder="00012">
            </div>
            <div class="col-md-4">
                <label for="recepcion">ID de Recepcion</label>
                <input type="text" class="form-control mt-2" id="recepcion" required placeholder="000123">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="suministro" class="form-label">Suministro</label>
                <input type="text" class="form-control" id="suministro" required>
            </div>
            <div class="col-md-4">
                <label for="cantidad">Cantidad</label>
                <input type="number" class="form-control mt-2" id="cantidad" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="proveedor" class="form-label">Proveedor</label>
                <input type="text" class="form-control" id="proveedor" required>
            </div>
            <div class="col-md-4 p-4">
                <textarea name="text" id="motivo">Motivo</textarea>
            </div>
        </div>
        <div>
    
            <button type="submit" class="btn btn-primary me-2" title="Limpiar"> Limpiar 
            <i class="fa-solid fa-broom"></i></button>
                <button type="button" class="btn btn-warning me-2" title="Enviar"> Enviar 
                <i class="fa-solid fa-location-arrow"></i>
                </button>
            </div>
    
    </form>
</div> 

<div class="container mt-5 ">
    <h3>Registro de Devoluciones</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Devolucion</th>
                <th>ID Recepcion</th>
                <th>Proveedor</th>
                <th>Estado</th>
                <th>Suministro</th>
                <th>Cantidad</th>
                <th>Accion</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>0001</td>
                <td>0005</td>
                <td>Tienda de Cocina</td>
                <td>Roto</td>
                <td>Cuchillos</td>
                <td>12</td>
                <td>
                <button class="btn btn-sm btn-secondary me-1" title="Bloquear"> Cancelar
                    <i class="fas fa-ban"></i>
                </button>
                </td>
            </tr>
           <!-- mas registros -->
        </tbody>
    </table>
</div>
@endsection