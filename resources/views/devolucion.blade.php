@extends('layouts.layout')

@section('content')
<div class="container formulario-container mt-5">
    <h2 class="mb-4 text-center">Solicitar Devolucion</h2>
    <form>
    <div class="row mb-3">
        <!-- Orden de compra -->
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <label for="ordenCompra" class="form-label">N° Orden de Recepcion</label>
            <select class="form-control" id="Recepciones_mercancias_idRecepcion_mercancia" name="Recepciones_mercancias_idRecepcion_mercancia" required>
    <option value="">Seleccione una Recepción de Mercancía</option>
    @foreach ($recepciones as $recepcion)
        <option value="{{ $recepcion->idRecepcion_mercancia }}">
            {{ $recepcion->idRecepcion_mercancia }} - {{ $recepcion->fecha_recepcion->format('d/m/Y') }}
        </option>
    @endforeach
</select>
        </div>
        <!-- Empleado -->
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <label for="empleado" class="form-label">Empleado</label>
            <select class="form-control" id="Empleados_idEmpleados" name="Empleados_idEmpleados" required>
    <option value="">Seleccione un empleado</option>
    @foreach ($empleados as $empleado)
        <option value="{{ $empleado->id }}">{{ $empleado->nombre_empleado }} {{ $empleado->apellido_empleado }}</option>
    @endforeach
</select>
        </div>
        <!-- Fecha de Recepcion de Mercancia  -->
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <label for="fecha_recepcion" class="form-label">Fecha de Devolucion</label>
            <input type="date" class="form-control" id="fecha_devolucion" name="fecha_recepcion" required>
        </div>
    </div>
    
    <table id="productTable" class="table table-bordered">
        <thead>
            <tr>
                <th>Suministro</th>
                <th>Cantidad Recibida</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <tr class="product-row">
                <td>
                    <select class="form-control" name="suministro[]" required>
                        <option value="">Seleccione un suministro</option>
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control" name="cantidad_recibida[]" required min=1>
                </td>
                <td>
                    <select class="form-select" name="status[]" required>
                        <option value="aceptar">Aceptar</option>
                        <option value="rechazar">Rechazar</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>

<!-- Motivo -->
<div class="row mb-3">
        <div class="col-12">
            <label for="motivo" class="form-label">Motivo</label>
            <textarea class="form-control" id="motivo" name="motivo" rows="3"></textarea>
        </div>
    </div>

    <div>
        <button type="button" id="addRow" class="btn btn-dark mt-2" title="Agregar Fila">Agregar Fila</button>
        <button type="submit" class="btn btn-success me-2 mt-2" title="Guardar">Actualizar <i class="fa-solid fa-box-archive"></i></button>
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