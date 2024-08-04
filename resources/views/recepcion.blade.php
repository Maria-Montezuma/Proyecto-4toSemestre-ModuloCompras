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
   <!-- Añade este div para la información de la orden de compra -->
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
                <th>Fecha Recepcion</th>
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
                <td>{{ $recepcion->status == 1 ? 'Aceptado' : 'Rechazado' }}</td>
                <td>
                <a href="{{ route('recepcion.edit', $recepcion->idRecepcion_mercancia) }}" class="btn btn-sm btn-warning me-1">
        Editar <i class="fas fa-edit"></i>
    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>

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
@endsection
