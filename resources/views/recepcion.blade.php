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
                <th>Cantidad</th>
                <th>Cantidad Recibida</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select class="form-control" id="suministro" name="suministro" required>
                        <option value="">Seleccionar Suministro</option>
                        @foreach($suministros as $suministro)
                        <option value="{{ $suministro->id }}" {{ old('suministro') == $suministro->id ? 'selected' : '' }}>
                            {{ $suministro->nombre_suministro }}
                        </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control" id="cantidad_pedida" name="cantidad_pedida" required>
                </td>
                <td>
                    <input type="number" class="form-control" id="cantidad_recibida" name="cantidad_recibida" required>
                </td>
                <td>
                    <select class="form-select" name="status" required>
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


<!-- Modal -->
<div class="modal fade" id="recepcionDetailModal" tabindex="-1" aria-labelledby="recepcionDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #8B4513; color: white;">
                <h5 class="modal-title" id="recepcionDetailModalLabel"><i class="fas fa-box-open me-2"></i>Detalles de la Recepción de Mercancía</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background-color: #FFEFD5;">
                <div id="recepcionDetailContent">
                    <!-- Aquí se cargará el contenido del modal -->
                </div>
            </div>
            <div class="modal-footer" style="background-color: #DEB887;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- cierre de modal -->



<!-- detalles de la orden de compra -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ordenCompraSelect = document.getElementById('Ordenes_compras_idOrden_compra');
    
    ordenCompraSelect.addEventListener('change', function() {
        const ordenCompraId = this.value;
        if (ordenCompraId) {
            fetch(`/api/orden-compra/${ordenCompraId}`)
                .then(response => response.json())
                .then(data => {
                    actualizarInfoOrdenCompra(data.orden_compra);
                    actualizarTablaProductos(data.detalles);
                })
                .catch(error => console.error('Error:', error));
        } else {
            // Limpiar la información si no se selecciona ninguna orden
            document.getElementById('infoOrdenCompra').innerHTML = '';
            document.getElementById('productTable').getElementsByTagName('tbody')[0].innerHTML = '';
        }
    });
});

function actualizarInfoOrdenCompra(ordenCompra) {
    const infoOrdenCompra = document.getElementById('infoOrdenCompra');
    infoOrdenCompra.innerHTML = `
        <h4>Detalles de la Orden de Compra</h4>
        <p><strong>Número de Orden:</strong> ${ordenCompra.id}</p>
        <p><strong>Fecha de Emisión:</strong> ${ordenCompra.fecha_emision}</p>
        <p><strong>Fecha de Entrega:</strong> ${ordenCompra.fecha_entrega}</p>
        <p><strong>Proveedor:</strong> ${ordenCompra.proveedor}</p>
        <p><strong>Subtotal:</strong> ${ordenCompra.subtotal}</p>
        <p><strong>Total:</strong> ${ordenCompra.total}</p>
    `;
}

function actualizarTablaProductos(detalles) {
    const tabla = document.getElementById('productTable').getElementsByTagName('tbody')[0];
    tabla.innerHTML = ''; // Limpiar tabla existente
    
    detalles.forEach(detalle => {
        let row = tabla.insertRow();
        let cell1 = row.insertCell(0);
        let cell2 = row.insertCell(1);
        let cell3 = row.insertCell(2);
        let cell4 = row.insertCell(3);
        
        cell1.innerHTML = `<select class="form-control" name="suministro[]">
                            <option value="${detalle.suministro_id}" selected>${detalle.nombre_suministro}</option>
                           </select>`;
        cell2.innerHTML = `<input type="number" class="form-control" name="cantidadPedida[]" value="${detalle.cantidad_pedida}" readonly>`;
        cell3.innerHTML = `<input type="number" class="form-control" name="cantidadRecibida[]" value="${detalle.cantidad_pedida}" required>`;
        cell4.innerHTML = `<select class="form-select" name="estado[]" required>
                            <option value="">Seleccionar...</option>
                            <option value="aceptar">Aceptar</option>
                            <option value="rechazar">Rechazar</option>
                           </select>`;
    });
}
</script>


<!-- Modal funcion  -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<!-- Tu script personalizado -->

<script>
$(document).ready(function() {
    $('.view-order').click(function(e) {
        e.preventDefault();
        var recepcionId = $(this).data('id');
        $.ajax({
            url: '/recepcion/' + recepcionId,
            type: 'GET',
            success: function(data) {
                var statusBadge = '';
                if (data.status == 1) {
                    statusBadge = '<span class="badge" style="background-color: #228B22;">Aceptado</span>';
                } else if (data.status == 0) {
                    statusBadge = '<span class="badge" style="background-color: #B22222;">Rechazado</span>';
                } else {
                    statusBadge = '<span class="badge" style="background-color: #4682B4;">Parcial</span>';
                }

                var modalContent = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 style="color: #8B4513;"><i class="fas fa-info-circle me-2"></i>Información General</h6>
                            <p><strong>ID Recepción:</strong> ${data.idRecepcion_mercancia}</p>
                            <p><strong>ID Orden de Compra:</strong> ${data.ordenes_compra.idOrden_compra}</p>
                            <p><strong>Empleado:</strong> ${data.empleado.nombre_empleado} ${data.empleado.apellido_empleado}</p>
                            <p><strong>Fecha de Recepción:</strong> ${new Date(data.fecha_recepcion).toLocaleDateString()}</p>
                            <p><strong>Estado:</strong> ${statusBadge}</p>
                            <p><strong>Detalles del Estado:</strong> ${data.status_details}</p>
                        </div>
                    </div>
                    <hr style="border-color: #8B4513;">
                    <h6 style="color: #8B4513;"><i class="fas fa-list me-2"></i>Detalles de la Recepción</h6>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" style="background-color: #FFEFD5;">
                            <thead style="background-color: #D2691E; color: white;">
                                <tr>
                                    <th>Suministro</th>
                                    <th>Cantidad Pedida</th>
                                    <th>Cantidad Recibida</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.detalles ? data.detalles.map(detalle => `
                                    <tr>
                                        <td>${detalle.suministro ? detalle.suministro.nombre_suministro : 'No especificado'}</td>
                                        <td>${detalle.cantidad_pedida || 'No especificada'}</td>
                                        <td>${detalle.cantidad_recibida || 'No especificada'}</td>
                                        <td>${detalle.estado || 'No especificado'}</td>
                                    </tr>
                                `).join('') : '<tr><td colspan="4" class="text-center">No hay detalles disponibles</td></tr>'}
                            </tbody>
                        </table>
                    </div>
                `;
                $('#recepcionDetailContent').html(modalContent);
                $('#recepcionDetailModal').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
                alert('Error al cargar los detalles de la recepción');
            }
        });
    });
});
</script>



@endsection
