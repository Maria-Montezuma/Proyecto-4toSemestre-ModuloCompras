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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/ordencompra.js') }}"></script>
@endsection