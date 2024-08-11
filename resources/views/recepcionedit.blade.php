@extends('layouts.layout')

@section('content')
<div class="container formulario-container mt-5">
    <h2 class="mb-4 text-center">Editar Recepción de Mercancía</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('recepcion.update', $recepcion->idRecepcion_mercancia) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row mb-3">
        <!-- Orden de compra -->
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <label for="ordenCompra" class="form-label">N° Orden de compra</label>
            <select class="form-control" id="Ordenes_compras_idOrden_compra" name="Ordenes_compras_idOrden_compra" required>
                @foreach($ordenesCompra as $ordenCompra)
                <option value="{{ $ordenCompra->idOrden_compra }}" {{ $recepcion->Ordenes_compras_idOrden_compra == $ordenCompra->idOrden_compra ? 'selected' : '' }}>
                    {{ $ordenCompra->idOrden_compra }} - {{ $ordenCompra->proveedore->nombre_empresa }}
                </option>
                @endforeach
            </select>
        </div>
        <!-- Empleado -->
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <label for="empleado" class="form-label">Empleado</label>
            <select class="form-control" id="Empleados_idEmpleados" name="Empleados_idEmpleados" required>
                @foreach($empleados as $empleado)
                <option value="{{ $empleado->idEmpleados }}" {{ $recepcion->Empleados_idEmpleados == $empleado->idEmpleados ? 'selected' : '' }}>
                    {{ $empleado->nombre_empleado }} {{ $empleado->apellido_empleado }}
                </option>
                @endforeach
            </select>
        </div>
        <!-- Fecha de Recepcion de Mercancia  -->
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <label for="fecha_recepcion" class="form-label">Fecha de Recepcion</label>
            <input type="date" class="form-control" id="fecha_recepcion" name="fecha_recepcion" value="{{ $recepcion->fecha_recepcion->format('Y-m-d') }}" required>
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
        @foreach($recepcion->detalles_recepciones_mercancias as $detalle)
<tr class="product-row">
    <td>
    <select class="form-control" name="suministro[]" required>
        @foreach($suministros as $suministro)
        <option value="{{ $suministro->idSuministro }}" {{ $detalle->Suministros_idSuministro == $suministro->idSuministro ? 'selected' : '' }}>
            {{ $suministro->nombre_suministro }}
        </option>
        @endforeach
    </select>
    </td>
    <td>
        <input type="number" class="form-control" name="cantidad_recibida[]" value="{{ $detalle->cantidad_recibida }}" required min=1>
    </td>
    <td>
        <select class="form-select" name="status[]" required>
            <option value="aceptar" {{ $detalle->status_recepcion == 1 ? 'selected' : '' }}>Aceptar</option>
            <option value="rechazar" {{ $detalle->status_recepcion == 0 ? 'selected' : '' }}>Rechazar</option>
        </select>
    </td>
</tr>
@endforeach
        </tbody>
    </table>
    <div>
        <button type="button" id="addRow" class="btn btn-dark mt-2" title="Agregar Fila">Agregar Fila </button>
        <button type="submit" class="btn btn-success me-2 mt-2" title="Guardar"> Actualizar <i class="fa-solid fa-box-archive"></i></button>
        <a href="{{ route('proveedores.create') }}" class="btn btn-secondary mt-2" title="Cancelar">Cancelar <i class="fa-solid fa-times"></i></a>
        </div> 
</form>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Add new row to the table
    $('#addRow').click(function() {
        var newRow = $('#productTable tbody tr:first').clone();
        newRow.find('input').val('');
        newRow.find('select').val('');
        $('#productTable tbody').append(newRow);
    });
});
</script>

@endsection