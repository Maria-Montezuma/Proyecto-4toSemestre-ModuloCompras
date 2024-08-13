@extends('layouts.layout')

@section('content')
<div class="container formulario-container mt-5">
    <h2 class="mb-4 text-center">Solicitar Devolución</h2>
    <form>
        <div class="row mb-3">
            <!-- Detalles Combinados -->
            <div id="recepcion-details" class="mb-4">
                <p><strong>ID Recepción:</strong> <span id="recepcion-id"></span></p>
                <p><strong>Fecha Recepción:</strong> <span id="recepcion-fecha"></span></p>
                <p><strong>Proveedor:</strong> <span id="recepcion-proveedor"></span></p>
                <div id="recepcion-detalles" class="row">
                    <div class="col-md-6">
                        <h5>Detalles de Orden de Compra</h5>
                        <table class="table table-bordered">
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
                        <h5>Detalles de Recepción</h5>
                        <table class="table table-bordered">
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

            <!-- Recepción de Mercancía -->
            <div class="col-12 col-lg-4 mb-3 mb-lg-0">
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
            <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                <label for="empleado" class="form-label">Empleado</label>
                <select class="form-control" id="Empleados_idEmpleados" name="Empleados_idEmpleados" required>
                    <option value="">Seleccione un empleado</option>
                    @foreach ($empleados as $empleado)
                        <option value="{{ $empleado->id }}">{{ $empleado->nombre_empleado }} {{ $empleado->apellido_empleado }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Fecha de Recepción de Mercancía -->
            <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                <label for="fecha_recepcion" class="form-label">Fecha de Devolución</label>
                <input type="date" class="form-control" id="fecha_devolucion" name="fecha_recepcion" required>
            </div>
        </div>

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

<div class="container mt-5">
    <h3>Registro de Devoluciones</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Devolución</th>
                <th>ID Recepción</th>
                <th>Proveedor</th>
                <th>Estado</th>
                <th>Suministro</th>
                <th>Cantidad</th>
                <th>Acción</th>
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
            <!-- más registros -->
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
                    // Mostrar la información combinada
                    $('#recepcion-id').text(data.idRecepcion_mercancia);
                    $('#recepcion-fecha').text(data.fecha_recepcion);
                    $('#recepcion-proveedor').text(data.proveedor); // Mostrar el proveedor
                    
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
                    $('#recepcion-details').html('<p>No se pudo cargar la información de la recepción.</p>');
                }
            });
        } else {
            $('#recepcion-details').find('span, tbody').empty();
        }
    });
});

</script>
@endsection