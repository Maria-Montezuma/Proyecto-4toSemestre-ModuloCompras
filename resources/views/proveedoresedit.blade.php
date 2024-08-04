@extends('layouts.layout')

@section('content')
<div class="container formulario-container mt-5">
    <h2 class="mb-4 text-center">Editar Proveedor</h2>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('proveedores.update', $proveedor->idProveedores) }}" method="POST">
    @csrf
    @method('PUT')
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="nombre_empresa" class="form-label">Nombre de la empresa</label>
                <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" value="{{ $proveedor->nombre_empresa }}" required>
            </div>
            <div class="col-md-4">
                <label for="telefono_proveedor" class="form-label">Teléfono del proveedor</label>
                <input type="text" class="form-control" id="telefono_proveedor" name="telefono_proveedor" value="{{ $proveedor->telefono_proveedor }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="direccion_empresa" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion_empresa" name="direccion_empresa" value="{{ $proveedor->direccion_empresa }}" required>
            </div>
            <div class="col-md-4">
                <label for="correo_proveedor" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo_proveedor" name="correo_proveedor" value="{{ $proveedor->correo_proveedor }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="rif" class="form-label">RIF</label>
                <input type="text" class="form-control" id="rif" name="rif" value="{{ $proveedor->rif }}" required>
            </div>
            <div class="col-md-4">
                <label for="categorias" class="form-label">Categorías</label>
                <div id="categorias">
                    @foreach($categorias as $categoria)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="categorias[]" 
                                   value="{{ $categoria->idcategorias }}" id="categoria{{ $categoria->idcategorias }}" 
                                   {{ $proveedor->categorias->contains($categoria->idcategorias) ? 'checked' : '' }}>
                            <label class="form-check-label" for="categoria{{ $categoria->idcategorias }}">
                                {{ $categoria->nombre_categoria }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div>
            <button type="submit" class="btn btn-success me-2 mt-2" title="Actualizar"> Actualizar
            <i class="fa-solid fa-box-archive"></i></button>
        </div>
    </form>
</div>
@endsection