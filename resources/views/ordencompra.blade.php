@extends('layouts.layout')

@section('content')
    <!-- error de validacion -->
    <div class="container formulario-container mt-5">
        <!-- Manejo de errores -->
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Mensaje de éxito -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
  
        <h2 class="mb-4 text-center">Orden de Compra</h2>
<form action="{{ route('ordencompra.store') }}" method="POST">
    @csrf
    <div class="row mb-1">
        <div class="col-12 col-md-6">
            <label for="fecha_emision">Fecha de Emisión</label>
            <p class="form-control mt-2" id="fecha_emision" name="fecha_emision">{{ \Carbon\Carbon::now()->format('Y-m-d') }}</p>
        </div>
        <div class="col-12 col-md-6">
            <label for="fecha">Fecha de Entrega</label>
            <input type="date" class="form-control mt-2" id="fecha" name="fecha_entraga" value="{{ old('fecha_entraga') }}" required placeholder="Seleccionar">
        </div>
    </div>
    <div class="row mb-1">
        <div class="col-12 col-md-6">
            <label for="Empleados_idEmpleados">Empleado</label>
            <select class="form-control mt-2" id="Empleados_idEmpleados" name="Empleados_idEmpleados" required>
                <option value="">Seleccionar Empleado</option>
                @foreach($empleados as $empleado)
                <option value="{{ $empleado->idEmpleados }}" {{ old('Empleados_idEmpleados') == $empleado->idEmpleados ? 'selected' : '' }}>{{ $empleado->nombre_empleado }} {{ $empleado->apellido_empleado }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-6">
            <label for="Proveedores_idProveedores">Proveedor</label>
            <select class="form-control mt-2" id="Proveedores_idProveedores" name="Proveedores_idProveedores" required>
                <option value="">Seleccionar Proveedor</option>
                @foreach($proveedores as $proveedor)
                <option value="{{ $proveedor->idProveedores }}" {{ old('Proveedores_idProveedores') == $proveedor->idProveedores ? 'selected' : '' }}>{{ $proveedor->nombre_empresa }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="table-responsive mt-3"> <!-- Añadido mt-3 para margen superior -->
        <table id="productTable" class="table table-bordered">
            <thead>
                <tr>
                    <th><i class="fas fa-box me-1"></i>Suministro</th>
                    <th><i class="fas fa-sort-amount-up me-1"></i>Cantidad</th>
                    <th><i class="fas fa-solid fa-dollar-sign me-1"></i>Precio Unitario</th>
                    <th><i class="fa-solid fa-check me-1"></i>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select class="form-control suministro-select" name="suministros[]" value="{{ old('suministro') }}" required>
                            <option>Seleccionar Suministro</option>
                        </select>
                    </td>
                    <td><input type="number" class="form-control cantidad" name="cantidades[]" required min="1"></td>
                    <td><input type="number" step="0.01" class="form-control precio" name="precios[]" readonly></td>
                    <td><input type="number" step="0.01" class="form-control subtotal" name="subtotales[]" readonly></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td class="text-right">
                        <label for="total">Total:</label>
                        <input type="number" step="0.01" class="form-control" id="total" name="total" readonly>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div>
        <button type="reset" class="btn btn-primary me-2" title="Limpiar"> Limpiar 
            <i class="fa-solid fa-broom"></i>
        </button>
        <button type="button" id="addRow" class="btn btn-dark me-2">Agregar Fila</button>
        <button type="submit" class="btn btn-success me-2" title="Enviar">Enviar 
            <i class="fa-solid fa-location-arrow"></i>
        </button>
    </div>
</form>
        </div>

        <!-- lista -->
        <div class="container mt-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="mb-0">Lista de Órdenes de Compra</h3>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha de Emisión</th>
                    <th>Fecha de Entrega</th>
                    <th>Proveedor</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @if(isset($ordenesCompra) && $ordenesCompra->count() > 0)
                @foreach($ordenesCompra as $orden)
                    <tr id="orden-{{ $orden->idOrden_compra }}">
                        <td>{{ $orden->idOrden_compra }}</td>
                        <td>{{ $orden->fecha_emision->format('d/m/Y') }}</td>
                        <td>{{ $orden->fecha_entraga->format('d/m/Y') }}</td>
                        <td>{{ $orden->proveedore->nombre_empresa }}</td>
                        <td>${{ number_format($orden->total_pagar, 2) }}</td>
                        <td>
                            @if($orden->status == 1)
                                <span class="badge bg-success p-2">Enviado</span>
                            @elseif($orden->status == 0)
                                <span class="badge bg-danger p-2">Cancelada</span>
                            @else
                                <span class="badge bg-secondary p-2">Recibida</span>
                            @endif
                        </td>
                        <td>
                        <a href="#" class="btn btn-sm btn-warning view-order" data-id="{{ $orden->idOrden_compra }}" title="Ver">
                    Ver <i class="fas fa-eye"></i>
                </a>
                    @if($orden->esCancelable())
                    <form action="{{ route('ordenescompra.cancel', $orden->idOrden_compra) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-danger" title="Cancelar" onclick="return confirm('¿Estás seguro de que quieres cancelar esta orden de compra?')">
                            Cancelar <i class="fas fa-ban"></i>
                        </button> 
                    </form>
                @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7">No se encontraron órdenes de compra.</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #8B4513; color: white;">
        <h5 class="modal-title" id="orderDetailModalLabel">
          <i class="fas fa-file-invoice me-2"></i>Detalles de la Orden de Compra
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="background-color: #FFF8DC;">
        <div id="orderDetailContent" class="p-3">
          <!-- El contenido se insertará aquí dinámicamente -->
        </div>
      </div>
      <div class="modal-footer" style="background-color: #DEB887;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#Proveedores_idProveedores').change(function() {
        var idProveedor = $(this).val();
        if(idProveedor) {
            $.ajax({
                url: '/get-suministros-por-proveedor/' + idProveedor,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var options = '<option value="">Seleccionar Suministro</option>';
                    $.each(data, function(key, suministro) {
                        options += '<option value="' + suministro.idSuministro + '" data-precio="' + suministro.precio_unitario + '">' + suministro.nombre_suministro + '</option>';
                    });
                    $('.suministro-select').html(options);
                }
            });
        } else {
            $('.suministro-select').html('<option value="">Seleccionar Suministro</option>');
        }
    });

    $(document).on('change', '.suministro-select', function() {
        var precio = $(this).find(':selected').data('precio');
        $(this).closest('tr').find('.precio').val(precio);
        calculateSubtotal($(this).closest('tr'));
    });

    $(document).on('input', '.cantidad', function() {
        calculateSubtotal($(this).closest('tr'));
    });

    function calculateSubtotal(row) {
        var cantidad = parseFloat(row.find('.cantidad').val()) || 0;
        var precio = parseFloat(row.find('.precio').val()) || 0;
        var subtotal = cantidad * precio;
        row.find('.subtotal').val(subtotal.toFixed(2));
        calculateTotal();
    }

    function calculateTotal() {
        var total = 0;
        $('.subtotal').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#total').val(total.toFixed(2));
    }

    $('#addRow').click(function() {
        var newRow = $('#productTable tbody tr:first').clone();
        newRow.find('input').val('');
        $('#productTable tbody').append(newRow);
    });
});


$('.view-order').click(function(e) {
    e.preventDefault();
    var orderId = $(this).data('id');
    $.ajax({
        url: '/ordenescompra/' + orderId,
        type: 'GET',
        success: function(data) {
            var statusBadge = '';
            if (data.status == 1) {
                statusBadge = '<span class="badge" style="background-color: #228B22;">Enviado</span>';
            } else if (data.status == 0) {
                statusBadge = '<span class="badge" style="background-color: #B22222;">Cancelada</span>';
            } else {
                statusBadge = '<span class="badge" style="background-color: #4682B4;">Recibida</span>';
            }

            var modalContent = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 style="color: #8B4513;"><i class="fas fa-info-circle me-2"></i>Información General</h6>
                        <p><strong>ID:</strong> ${data.id}</p>
                        <p><strong>Fecha de Emisión:</strong> ${new Date(data.fecha_emision).toLocaleDateString()}</p>
                        <p><strong>Fecha de Entrega:</strong> ${new Date(data.fecha_entraga).toLocaleDateString()}</p>
                        <p><strong>Estado:</strong> ${statusBadge}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 style="color: #8B4513;"><i class="fas fa-user me-2"></i>Detalles de Contacto</h6>
                        <p><strong>Proveedor:</strong> ${data.proveedor ? data.proveedor.nombre : 'No especificado'}</p>
                        <p><strong>Empleado:</strong> ${data.empleado ? data.empleado.nombre : 'No especificado'}</p>
                    </div>
                </div>
                <hr style="border-color: #8B4513;">
                <h6 style="color: #8B4513;"><i class="fas fa-list me-2"></i>Detalles de la Orden</h6>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" style="background-color: #FFEFD5;">
                        <thead style="background-color: #D2691E; color: white;">
                            <tr>
                                <th>Suministro</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.detalles ? data.detalles.map(detalle => `
                                <tr>
                                    <td>${detalle.suministro ? detalle.suministro.nombre : 'No especificado'}</td>
                                    <td>${detalle.cantidad || 'No especificada'}</td>
                                    <td>$${detalle.precio_unitario ? detalle.precio_unitario.toFixed(2) : 'No especificado'}</td>
                                    <td>$${detalle.subtotal ? detalle.subtotal.toFixed(2) : 'No especificado'}</td>
                                </tr>
                            `).join('') : '<tr><td colspan="4" class="text-center">No hay detalles disponibles</td></tr>'}
                        </tbody>
                        <tfoot style="background-color: #DEB887;">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>$${data.total_pagar.toFixed(2)}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;
            $('#orderDetailContent').html(modalContent);
            $('#orderDetailModal').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
            alert('Error al cargar los detalles de la orden');
        }
    });
});

</script>
@endsection