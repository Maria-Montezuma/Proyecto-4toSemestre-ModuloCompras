@extends('layouts.layout')

@section('content')
<div class="container formulario-container mt-5">
    <h2 class="mb-4 text-center">Recepción de Mercancía</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Detalles de la Orden de Compra -->
   <div id="infoOrdenCompra" class="mb-4"></div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('recepcion.store') }}" method="POST">
    @csrf
    <div class="row mb-3">
        <!-- los detalles de como aparecen los datos en recepcion de orden de compra -->
        <div id="detallesOrdenCompra" style="display: none;">
            <h4>Detalles de la Orden de Compra</h4>
            <p><strong>Fecha de emisión:</strong> <span id="fechaEmision"></span></p>
            <p><strong>Fecha de entrega:</strong> <span id="fechaEntrega"></span></p>
            <p><strong>Proveedor:</strong> <span id="proveedor"></span></p>
            <p><strong>Total:</strong> <span id="total"></span></p>
            <div id="listaProductos"></div>
        </div>

        <!-- Orden de compra -->
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <label for="ordenCompra" class="form-label">N° Orden de compra</label>
            <select class="form-control" id="Ordenes_compras_idOrden_compra" name="Ordenes_compras_idOrden_compra" required>
                <option value="">Seleccionar Orden de Compra</option>
                @foreach($ordenesCompra as $ordenCompra)
                <option value="{{ $ordenCompra->idOrden_compra }}">
                    {{ $ordenCompra->idOrden_compra }} - {{ $ordenCompra->proveedore->nombre_empresa }}
                </option>
                @endforeach
            </select>
        </div>
        <!-- Empleado -->
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <label for="empleado" class="form-label">Empleado</label>
            <select class="form-control" id="Empleados_idEmpleados" name="Empleados_idEmpleados" required>
                <option value="">Seleccionar Empleado</option>
                @foreach($empleados as $empleado)
                <option value="{{ $empleado->idEmpleados }}" {{ old('Empleados_idEmpleados') == $empleado->idEmpleados ? 'selected' : '' }}>
                    {{ $empleado->nombre_empleado }} {{ $empleado->apellido_empleado }}
                </option>
                @endforeach
            </select>
        </div>
        <!-- Fecha de Recepcion de Mercancia  -->
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <label for="fecha_recepcion" class="form-label">Fecha de Recepcion</label>
            <input type="date" class="form-control" id="fecha_recepcion" name="fecha_recepcion" required>
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
                        <option value="">Seleccionar Suministro</option>
                        @foreach($suministros as $suministro)
                        <option value="{{ $suministro->id }}" {{ old('suministro') == $suministro->id ? 'selected' : '' }}>
                            {{ $suministro->nombre_suministro }}
                        </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control" name="cantidad_recibida[]" required min=1>
                </td>
                <td>
                    <select class="form-select" name="status[]" required>
                        <option value="">Seleccionar...</option>
                        <option value="aceptar" {{ old('estado') == 'aceptar' ? 'selected' : '' }}>Aceptar</option>
                        <option value="rechazar" {{ old('estado') == 'rechazar' ? 'selected' : '' }}>Rechazar</option>
                    </select>
                </td>
            </tr>   
        </tbody>
    </table>
    <div>
        <button type="reset" class="btn btn-primary mt-2" title="Limpiar"> Limpiar <i class="fa-solid fa-broom"></i></button>
        <button type="button" id="addRow" class="btn btn-dark mt-2" title="Agregar Fila">Agregar Fila </button>
        <button type="submit" class="btn btn-success me-2 mt-2" title="Guardar"> Guardar <i class="fa-solid fa-box-archive"></i></button>
    </div>
</form>

</div>

<div class="container mt-5">
    <h3>Lista de Recepciones</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Recepción</th>
                    <th>ID Orden de Compra</th>
                    <th>Empleado</th>
                    <th>Fecha Recepción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recepcionesMercancia as $recepcion)
                <tr>
                    <td>{{ $recepcion->idRecepcion_mercancia }}</td>
                    <td>{{ $recepcion->ordenes_compra->idOrden_compra }}</td>
                    <td>{{ $recepcion->empleado->nombre_empleado }} {{ $recepcion->empleado->apellido_empleado }}</td>
                    <td>{{ $recepcion->fecha_recepcion->format('d-m-Y') }}</td>
                    <td>
                    @if($recepcion->status == 1)
                        <span class="badge bg-success p-2">Aceptado</span>
                    @elseif($recepcion->status == 0)
                        <span class="badge bg-danger p-2">Rechazado</span>
                    @else
                        <span class="badge bg-dark p-2">Parcial</span>
                    @endif
                    <br>
                    <small>{{ $recepcion->status_details }}</small>
                </td>
                    <td>
                        <a href="{{ route('recepcion.edit', $recepcion->idRecepcion_mercancia) }}" class="btn btn-sm btn-secondary me-1">
                            Editar <i class="fas fa-edit"></i>
                        </a>

                        <a href="#" class="btn btn-sm btn-warning view-order" data-id="{{ $recepcion->idRecepcion_mercancia }}" data-bs-toggle="modal" data-bs-target="#viewModal" title="Ver">
                        Ver <i class="fas fa-eye"></i>
                        </a>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- para llamar la orden de compra a recepcion -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#Ordenes_compras_idOrden_compra').change(function() {
        var idOrdenCompra = $(this).val();
        if(idOrdenCompra) {
            $.ajax({
                url: '/get-orden-compra-details/' + idOrdenCompra,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#fechaEmision').text(data.fecha_emision);
                    $('#fechaEntrega').text(data.fecha_entraga);
                    $('#proveedor').text(data.proveedor);
                    $('#total').text(data.total_pagar);
                    
                    var productosHtml = '<table class="table"><thead><tr><th>Suministro</th><th>Cantidad Pedida</th><th>Precio Unitario</th><th>Subtotal</th></tr></thead><tbody>';
                    data.productos.forEach(function(producto) {
                        productosHtml += '<tr><td>' + producto.nombre_suministro + 
                                         '</td><td>' + producto.cantidad + 
                                         '</td><td>$' + producto.precio_unitario + 
                                         '</td><td>$' + producto.subtotal + '</td></tr>';
                    });
                    productosHtml += '</tbody></table>';
                    $('#listaProductos').html(productosHtml);
                    
                    $('#detallesOrdenCompra').show();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        } else {
            $('#detallesOrdenCompra').hide();
        }
    });

    // Add new row to the table
    $('#addRow').click(function() {
        var newRow = $('#productTable tbody tr:first').clone();
        newRow.find('input').val('');
        newRow.find('select').val('');
        $('#productTable tbody').append(newRow);
    });

});
</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection
