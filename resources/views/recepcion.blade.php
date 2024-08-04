@extends('layouts.layout')

@section('content')
 <div class="container formulario-container mt-5">
    <h2 class="mb-4 text-center">Recepción de Suministros</h2>
    <form>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="nombre" class="form-label">Numero de orden</label>
                <input type="text" class="form-control" id="nombreOrden" required>
            </div>
            <div class="col-md-4">
                <label for="nombre" class="form-label">Numero de Recepcion</label>
                <input type="text" class="form-control" id="nombreOrden" required>
            </div>
            <div class="col-md-4 ">
                <label for="Fecha">Fecha de Recepcion</label>
                <input type="date" class="form-control" id="fecha" required placeholder="Seleccionar">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="proveedor" class="form-label">Proveedor</label>
                <input type="text" class="form-control" id="proveedor" required placeholder="Seleccionar...">
            </div>
        </div>
        


        <table id="productTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th><i class="fas fa-box me-1"></i>Suministro</th>
                        <th><i class="fas fa-sort-amount-up me-1"></i>Cantidad</th>
                        <th><i class="fas fa-info-circle me-1"></i>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" class="form-control" required></td>
                        <td><input type="number" class="form-control" required></td>
                        <td>
                            <select class="form-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="bueno">Bueno</option>
                                <option value="dañado">Dañado</option>
                                <option value="incompleto">Incompleto</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div>
            <button type="submit" class="btn btn-primary me-2" title="Limpiar"> Limpiar 
                <i class="fa-solid fa-broom"></i></button>
            <button type="button" class="btn btn-secondary mt-2" id="addProduct"><i class="fas fa-plus me-1"></i> Agregar Suministro</button>

            <button  class="btn btn-success me-2 mt-2" title="Borrar"> Guardar
            <i class="fa-solid fa-box-archive"></i></button>
        </div>
    </form>
</div> 

<div class="container mt-5 ">
    <h3>Lista de Recepciones</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Orden de Compra</th>
                <th>ID Suministro</th>
                <th>Suministro</th>
                <th>Cantidad</th>
                <th>Proveedor</th>
                <th>Estatus</th>
                <th>Fecha de Recepcion</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>0001</td>
                <td>000123</td>
                <td>Pepinillo</td>
                <td>12</td>
                <td>Dani</td>
                <td>Bueno</td>
                <td>05-05-2024</td>
                <td>
                    <button class="btn btn-sm btn-warning me-1"> Editar <i class="fas fa-edit"></i></button>
                </td>
            </tr>
           <!-- mas proveedores -->
        </tbody>
    </table>
</div>
@endsection