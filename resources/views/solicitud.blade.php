@extends('layouts.layout')

@section('content')
<div class="container formulario-container mt-5">
    <h2 class="mb-4 text-center">Solicitud de Cotización a Proveedor</h2>
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

    <!-- formulario de registro -->
    <form action="{{ route('solicitud.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <!-- fecha -->
            <div class="col-md-4 ">
                <label for="fecha_solicitud">Fecha de Emisión</label>
                <input type="date" class="form-control mt-2" id="fecha_solicitud" name="fecha_solicitud">
            </div>
            <!-- empleados -->
            <div class="col-md-4">
                <label for="idEmpleados" class="form-label">Empleado</label>
                <select class="form-control" id="Empleados_idEmpleados" name="Empleados_idEmpleados" required>
                    <option value="">Seleccione un empleado</option>
                    @foreach ($empleados as $empleado)
                        <option value="{{ $empleado->idEmpleados }}" {{ old('Empleados_idEmpleados') == $empleado->idEmpleados ? 'selected' : '' }}>
                            {{ $empleado->nombre_empleado }} {{ $empleado->apellido_empleado }}
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- proveedores -->
            <div class="col-md-4">
                <label for="proveedor" class="form-label">Proveedor</label>
                <select class="form-control" id="idProveedores" name="idProveedores" required>
                    <option value="">Seleccione un proveedor</option>
                    @foreach ($proveedores as $proveedor)
                        <option value="{{ $proveedor->idProveedores }}" {{ old('idProveedores') == $proveedor->idProveedores ? 'selected' : '' }}>
                            {{ $proveedor->nombre_empresa }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- cotizacion -->
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="cotizacion" class="form-label">Cotizacion</label>
                <textarea type="text" class="form-control" id="cotizacion" name="cotizacion" rows="3">{{ old('cotizacion') }}</textarea>
            </div>
        </div>
        <!-- condicion -->
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="condicion" class="form-label">Condición</label>
                <textarea type="text" class="form-control" id="condicion" name="condicion" rows="3">{{ old('condicion') }}</textarea>
            </div>
        </div>
        <div>
            <!-- botones de accion formulario -->
            <button type="reset" class="btn btn-primary me-2 mt-2" title="Limpiar">
                Limpiar <i class="fa-solid fa-broom"></i>
            </button>
            <button type="submit" class="btn btn-success me-2 mt-2" title="Enviar">
                Enviar Solicitud <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </form>
</div> 

<!-- lista de solicitud -->
<div class="container mt-5">
    <h2 class="mb-4">Lista de Solicitudes de Cotización</h2>

    <!-- boton de registrar suminsitro -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#registrarSuministroModal">
        Registrar suministro
    </button>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha de Emisión</th>
                    <th>Empleado</th>
                    <th>Proveedor</th>
                    <th>Cotización</th>
                    <th>Condición</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($solicitudes) && $solicitudes->count() > 0)
                    @foreach($solicitudes as $solicitud)
                        <tr id="solicitud-{{ $solicitud->idSolicitudes }}">
                            <td>{{ $solicitud->idSolicitudes }}</td>
                            <td>{{ $solicitud->fecha_solicitud->format('Y-m-d') }}</td>
                            <td>{{ $solicitud->empleado->nombre_empleado }} {{ $solicitud->empleado->apellido_empleado }}</td>
                            <td>
                                @foreach($solicitud->proveedores as $proveedor)
                                    {{ $proveedor->nombre_empresa }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </td>
                            <td>{{ Str::limit($solicitud->cotizacion, 50) }}</td>
                            <td>{{ Str::limit($solicitud->condicion, 50) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">No hay solicitudes registradas.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para Registrar Suministro -->
<div class="modal fade" id="registrarSuministroModal" tabindex="-1" aria-labelledby="registrarSuministroModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #8B4513; color: white;">
                <h5 class="modal-title" id="registrarSuministroModalLabel">Registrar Suministro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="registrarSuministroForm" action="{{ route('suministro.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="background-color: #FFF8DC;">
                    <div class="mb-3">
                        <label for="proveedor" class="form-label">Proveedor</label>
                        <select class="form-select" id="proveedor" name="Proveedores_idProveedores" required>
                            <option value="">Seleccione un proveedor</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->idProveedores }}">{{ $proveedor->nombre_empresa }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria" name="categorias_idcategorias" required>
                            <option value="">Seleccione una categoría</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nombre_suministro" class="form-label">Nombre del Suministro</label>
                        <input type="text" class="form-control" id="nombre_suministro" name="nombre_suministro" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio_unitario" class="form-label">Precio Unitario</label>
                        <input type="number" step="0.01" class="form-control" id="precio_unitario" name="precio_unitario" required>
                    </div>
                    
                </div>
                <div class="modal-footer" style="background-color: #DEB887;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar Suministro</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/solicitud.js') }}"></script>

