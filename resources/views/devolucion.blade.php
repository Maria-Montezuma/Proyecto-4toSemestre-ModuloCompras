@extends('layouts.layout')

@section('content')
<div class="container formulario-container mt-5">
    <h2 class="mb-4 text-center">Solicitar Devolución</h2>
    
    <!-- Detalles Combinados (Ocultos por defecto) -->
    <div id="recepcion-details" class="mb-4 p-3 " style="display: none;">
        <div class="row">
            <div class="col-md-6">
                <p><strong>ID Recepción:</strong> <span id="recepcion-id"></span></p>
                <p><strong>Fecha Recepción:</strong> <span id="recepcion-fecha"></span></p>
                <p><strong>Proveedor:</strong> <span id="recepcion-proveedor"></span></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h5 class="mb-3">Detalles de Orden de Compra</h5>
                <table class="table table-bordered table-m">
                    <thead>
                        <tr>
                            <th>Suministro</th>
                            <th>Cantidad Pedida</th>
                        </tr>
                    </thead>
                    <tbody id="orden-detalles-tbody">
                        <!-- Detalles de orden se insertarán aquí -->
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <h5 class="mb-3">Detalles de Recepción</h5>
                <table class="table table-bordered table-m">
                    <thead>
                        <tr>
                            <th>Suministro</th>
                            <th>Cantidad Recibida</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="recepcion-detalles-tbody">
                        <!-- Detalles de recepción se insertarán aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form action="{{ route('devolucion.store') }}" method="POST">
    @csrf
    <div class="row mb-3">
        <!-- Recepción de Mercancía -->
        <div class="col-lg-4 mb-3 mb-lg-0">
            <label for="recepcion" class="form-label">N° Orden de Recepción</label>
            <select class="form-control" id="Recepciones_mercancias_idRecepcion_mercancia" name="Recepciones_mercancias_idRecepcion_mercancia" required>
                <option value="">Seleccione una recepción</option>
                @foreach ($recepciones as $recepcion)
                    <option value="{{ $recepcion->idRecepcion_mercancia }}">
                        {{ $recepcion->idRecepcion_mercancia }} - {{ $recepcion->fecha_recepcion->format('d/m/Y') }}
                    </option>
                @endforeach
            </select>
        </div>
        <!-- Empleado -->
        <div class="col-lg-4 mb-3 mb-lg-0">
            <label for="empleado" class="form-label">Empleado</label>
            <select class="form-control" id="Empleados_idEmpleados" name="Empleados_idEmpleados" required>
                <option value="">Seleccione un empleado</option>
                @foreach ($empleados as $empleado)
                    <option value="{{ $empleado->id }}">{{ $empleado->nombre_empleado }} {{ $empleado->apellido_empleado }}</option>
                @endforeach
            </select>
        </div>
        <!-- Fecha de Devolución -->
        <div class="col-lg-4 mb-3 mb-lg-0">
            <label for="fecha_devolucion" class="form-label">Fecha de Devolución</label>
            <input type="date" class="form-control" id="fecha_devolucion" name="fecha_devolucion" required>
        </div>
    </div>

    <!-- Tabla de comparación -->
    <table id="productTable" class="table table-bordered">
        <thead>
            <tr>
                <th>Suministro</th>
                <th>Cantidad Por Devolver</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <tr class="product-row">
                <td>
                    <select class="form-control" name="detalles[][suministro_id]" required>
                        <option value="">Seleccionar suministro...</option>
                        @foreach($suministros as $suministro)
                            <option value="{{ $suministro->idSuministro }}">{{ $suministro->nombre_suministro }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control" name="detalles[][cantidad_devuelta]" required>
                </td>
                <td>
                <select class="form-select" name="detalles[][estado_devolucion]" required>
                <option value="">Seleccionar...</option>
                <option value="1">Sobrante</option>
                <option value="2">Faltante</option>
                <option value="3">Dañado</option>
                <option value="0">Otro</option>
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
        <button type="submit" class="btn btn-success me-2 mt-2" title="Enviar">Enviar 
        <i class="fa-solid fa-location-arrow"></i></button>
    </div>
</form>
</div>

<div class="container mt-5">
<div class="container">
    <h3>Lista de Devoluciones</h3>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha de Devolución</th>
                <th>Empleado</th>
                <th>Recepción</th>
                <th>Detalles</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($devoluciones as $devolucion)
                <tr>
                    <td>{{ $devolucion->idDevoluciones }}</td>
                    <td>{{ $devolucion->fecha_devolucion->format('d/m/Y') }}</td>
                    <td>{{ $devolucion->empleado->nombre_empleado ?? 'Desconocido' }}</td>
                    <td>{{ $devolucion->recepciones_mercancia->idRecepcion_mercancia ?? 'Desconocido' }}</td>
                    <td>
                        <ul>
                            @foreach ($devolucion->detalles_devoluciones as $detalle)
                                <li>
                                    {{ $detalle->suministro->nombre_suministro ?? 'Desconocido' }} - 
                                    Cantidad Devuelta: {{ $detalle->cantidad_devuelta }}, 
                                    Motivo: {{ $detalle->motivo }}, 
                                    Estado: {{ $detalle->status_devolucion }}
                                </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#Recepciones_mercancias_idRecepcion_mercancia').change(function() {
        var recepcionId = $(this).val();
        if (recepcionId) {
            $.ajax({
                url: '/recepcion-details/' + recepcionId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Mostrar la sección de detalles
                    $('#recepcion-details').show();
                    
                    // Mostrar la información combinada
                    $('#recepcion-id').text(data.idRecepcion_mercancia);
                    $('#recepcion-fecha').text(data.fecha_recepcion);
                    $('#recepcion-proveedor').text(data.proveedor);

                    // Mostrar los detalles de recepción
                    var detallesHtml = '';
                    data.detalles_recepciones_mercancias.forEach(function(detalle) {
                        detallesHtml += `
                            <tr>
                                <td>${detalle.suministro}</td>
                                <td>${detalle.cantidad_recibida}</td>
                                <td>${detalle.estado}</td>
                            </tr>
                        `;
                    });
                    $('#recepcion-detalles-tbody').html(detallesHtml);
                    
                    // Mostrar los detalles de orden de compra
                    var ordenHtml = '';
                    data.detalles_ordenes_compras.forEach(function(detalle) {
                        ordenHtml += `
                            <tr>
                                <td>${detalle.suministro}</td>
                                <td>${detalle.cantidad_pedida}</td>
                            </tr>
                        `;
                    });
                    $('#orden-detalles-tbody').html(ordenHtml);
                },
                error: function() {
                    $('#recepcion-details').html('<p>No se pudo cargar la información de la recepción.</p>').show();
                }
            });
        } else {
            // Ocultar la sección de detalles si no se selecciona nada
            $('#recepcion-details').hide();
        }
    });
});


    // Add new row to the table
    $('#addRow').click(function() {
        var newRow = $('#productTable tbody tr:first').clone();
        newRow.find('input').val('');
        newRow.find('select').val('');
        $('#productTable tbody').append(newRow);
    });
</script>
@endsection
