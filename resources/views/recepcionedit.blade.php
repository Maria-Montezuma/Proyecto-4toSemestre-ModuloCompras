@extends('layouts.layout')

@section('content')
<div class="container formulario-container mt-5">
    <h2 class="mb-4 text-center">Editar Recepción de Mercancía</h2>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
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
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <label for="ordenCompra" class="form-label">N° Orden de compra</label>
            <input type="text" class="form-control" value="{{ $recepcion->ordenes_compra->idOrden_compra }} - {{ $recepcion->ordenes_compra->proveedore->nombre_empresa }}" readonly>
        </div>
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
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <label for="fecha_recepcion" class="form-label">Fecha de Recepcion</label>
            <input type="date" class="form-control" id="fecha_recepcion" name="fecha_recepcion" value="{{ $recepcion->fecha_recepcion->format('Y-m-d') }}" required>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Suministro</th>
                <th>Cantidad Pedida</th>
                <th>Cantidad Recibida</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detallesRecepcion as $detalle)
            <tr>
                <td>
                    <input type="text" class="form-control" value="{{ $detalle['nombre_suministro'] }}" readonly>
                    <input type="hidden" name="suministro[]" value="{{ $detalle['Suministro_idSuministro'] }}">
                </td>
                <td>
                    <input type="number" class="form-control" name="cantidad_pedida[]" value="{{ $detalle['cantidad_pedida'] }}" readonly>
                </td>
                <td>
                    <input type="number" class="form-control" name="cantidad_recibida[]" value="{{ $detalle['cantidad_recibida'] }}" required>
                </td>
                <td>
                    <select class="form-select" name="estado[]" required>
                        <option value="1" {{ $detalle['status'] == 1 ? 'selected' : '' }}>Aceptado</option>
                        <option value="0" {{ $detalle['status'] == 0 ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        <button type="submit" class="btn btn-success me-2 mt-2" title="Actualizar">Actualizar <i class="fa-solid fa-check"></i></button>
        <a href="{{ route('recepcion.create') }}" class="btn btn-secondary mt-2" title="Cancelar">Cancelar <i class="fa-solid fa-times"></i></a>
    </div>
</form>

</div>
@endsection