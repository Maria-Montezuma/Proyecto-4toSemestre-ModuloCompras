    @extends('layouts.layout')

    @section('content')
    <div class="container formulario-container mt-5">
        <h2 class="mb-4 text-center">Registrar de Proveedores</h2>

        <!-- Manejo de errores -->
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Mensaje de éxito -->
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <!-- Mensaje de error -->
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulario para registro de proveedores -->
    <form action="{{ route('proveedores.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="nombre_empresa" class="form-label">Nombre de la empresa</label>
                <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" value="{{ old('nombre_empresa') }}" required>
            </div>
            <div class="col-md-4">
                <label for="telefono_proveedor" class="form-label">Teléfono del proveedor</label>
                <input type="text" class="form-control" id="telefono_proveedor" name="telefono_proveedor" value="{{ old('telefono_proveedor') }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="direccion_empresa" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion_empresa" name="direccion_empresa" value="{{ old('direccion_empresa') }}" required>
            </div>
            <div class="col-md-4">
                <label for="correo_proveedor" class="form-label">Correo Electrónico</label>
                <input type="text" class="form-control" id="correo_proveedor" name="correo_proveedor" value="{{ old('correo_proveedor') }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="rif" class="form-label">RIF</label>
                <input type="text" class="form-control" id="rif" name="rif" value="{{ old('rif') }}" required>
            </div>
            <div class="col-md-4">
                <label for="categorias" class="form-label">Categorías</label>
                <div id="categorias">
                    @foreach($categorias as $categoria)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="categorias[]" value="{{ $categoria->idcategorias }}" id="categoria{{ $categoria->idcategorias }}" {{ in_array($categoria->idcategorias, old('categorias', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="categoria{{ $categoria->idcategorias }}">
                                {{ $categoria->nombre_categoria }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
            <div>
                <button type="reset" class="btn btn-primary me-2 mt-2" title="Limpiar"> Limpiar 
                    <i class="fa-solid fa-broom"></i></button>

                <button type="submit" class="btn btn-success me-2 mt-2" title="Guardar"> Guardar
                    <i class="fa-solid fa-box-archive"></i></button>
         </div>
        </form>
    </div>

    <!-- prueba -->
    <div class="container mt-5">
        <!-- Encabezado y búsqueda -->
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h3 class="mb-0">Lista de Proveedores</h3>
            </div>
            <div class="col-md-6">
                <form action="{{ route('proveedores.search') }}" method="GET" class="d-flex justify-content-end">
                    <div class="input-group">
                        <select name="categoria" class="form-control">
                            <option value="">Todas las categorías</option>
                                @foreach($categorias as $categoria)
                                <option value="{{ $categoria->idcategorias }}" {{ request('categoria') == $categoria->idcategorias ? 'selected' : '' }}>
                                    {{ $categoria->nombre_categoria }}
                                </option>
                                @endforeach
                        </select>
                        <button type="submit" class="btn btn-dark">Buscar</button>
                    </div>
                </form>
            </div>
        </div>

    <!-- Botón de acción -->
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('solicitud') }}" class="btn btn-success" title="Solicitud">
            Solicitud <i class="fas fa-solid fa-location-arrow"></i>
        </a>
    </div>

    <!-- Tabla de proveedores -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>RIF</th>
                    <th>Categoría</th>
                    <th>Ubicación</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
                <tbody>
                    @foreach($proveedores as $proveedor)
                    <tr id="proveedor-{{ $proveedor->idProveedores }}">
                        <td>{{ $proveedor->nombre_empresa }}</td>
                        <td>{{ $proveedor->telefono_proveedor }}</td>
                        <td>{{ $proveedor->rif }}</td>
                        <td>
                            @foreach($proveedor->categorias as $categoria)
                                {{ $categoria->nombre_categoria }} <br>
                            @endforeach
                        </td>
                        <td>{{ $proveedor->direccion_empresa }}</td>
                        <td>{{ $proveedor->correo_proveedor }}</td>
                        <td>
                            <a href="{{ route('proveedores.edit', $proveedor->idProveedores) }}" class="btn btn-sm btn-warning" title="Editar">
                                Editar <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('proveedores.delete', $proveedor->idProveedores) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar este proveedor?')">
                                    Eliminar <i class="fas fa-ban"></i>
                                </button> 
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @endsection