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
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
                <label for="nombre_empresa" class="form-label">Nombre de la empresa</label>
                <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" value="{{ $proveedor->nombre_empresa }}" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="telefono_proveedor" class="form-label">Teléfono del proveedor</label>
                <input type="text" class="form-control" id="telefono_proveedor" name="telefono_proveedor" value="{{ $proveedor->telefono_proveedor }}" required>
            </div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
                <label for="direccion_empresa" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion_empresa" name="direccion_empresa" value="{{ $proveedor->direccion_empresa }}" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="correo_proveedor" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo_proveedor" name="correo_proveedor" value="{{ $proveedor->correo_proveedor }}" required>
            </div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
                <label for="rif" class="form-label">RIF</label>
                <input type="text" class="form-control" id="rif" name="rif" value="{{ $proveedor->rif }}" required>
            </div>
            <div class="col-12 col-md-6">
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
        <div class="text-center mt-3">
            <button type="submit" class="btn btn-success" title="Actualizar">Actualizar
            <i class="fa-solid fa-box-archive"></i></button>
            <a href="{{ route('proveedores.create') }}" class="btn btn-secondary" title="Cancelar">Cancelar <i class="fa-solid fa-times"></i></a>
        </div>
    </form>
</div>
@endsection
