$(document).ready(function() {
    $('#Recepciones_mercancias_idRecepcion_mercancia').change(function() {
        var recepcionId = $(this).val();
        if (recepcionId) {
            $.ajax({
                url: '/recepcion-details/' + recepcionId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Mostrar la sección de detalles
                    $('#recepcion-details').show();
                    
                    // Mostrar la información combinada
                    $('#recepcion-id').text(data.idRecepcion_mercancia);
                    $('#recepcion-fecha').text(data.fecha_recepcion);
                    $('#recepcion-proveedor').text(data.proveedor);

                    // Mostrar los detalles de recepción
                    var detallesHtml = '';
                    data.detalles_recepciones_mercancias.forEach(function(detalle) {
                        detallesHtml += `
                            <tr>
                                <td>${detalle.suministro}</td>
                                <td>${detalle.cantidad_recibida}</td>
                                <td>${detalle.estado}</td>
                            </tr>
                        `;
                    });
                    $('#recepcion-detalles-tbody').html(detallesHtml);
                    
                    // Mostrar los detalles de orden de compra
                    var ordenHtml = '';
                    data.detalles_ordenes_compras.forEach(function(detalle) {
                        ordenHtml += `
                            <tr>
                                <td>${detalle.suministro}</td>
                                <td>${detalle.cantidad_pedida}</td>
                            </tr>
                        `;
                    });
                    $('#orden-detalles-tbody').html(ordenHtml);
                },
                error: function() {
                    $('#recepcion-details').html('<p>No se pudo cargar la información de la recepción.</p>').show();
                }
            });
        } else {
            // Ocultar la sección de detalles si no se selecciona nada
            $('#recepcion-details').hide();
        }
    });

    $('#addRow').click(function() {
        var newRow = `
            <tr class="product-row">
                <td>
                    <select class="form-control" name="Suministros_idSuministro[]" required>
                        <option value="">Selecciona un suministro</option>
                        @foreach($suministros as $suministro)
                            <option value="{{ $suministro->idSuministro }}">{{ $suministro->nombre_suministro }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control" name="cantidad_devuelta[]" required>
                </td>
                <td>
                    <select class="form-control" name="status_devolucion[]" required>
                        <option value="">Seleccionar...</option>
                        <option value="Sobrante">Sobrante</option>
                        <option value="Faltante">Faltante</option>
                        <option value="Dañado">Dañado</option>
                        <option value="Otro">Otro</option>
                    </select>
                </td>
            </tr>
        `;
        $('#productTable tbody').append(newRow);
    });

    $('.view-devolucion').click(function(e) {
    e.preventDefault();
    var devolucionId = $(this).data('id');
    $.ajax({
        url: '/devolucion/' + devolucionId,
        type: 'GET',
        success: function(data) {
            if (data.error) {
                alert(data.error);
                return;
            }

            var modalContent = `
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <h6 style="color: #8B4513;"><i class="fas fa-info-circle me-2"></i>Información General</h6>
                <p><strong>ID:</strong> ${data.idDevolucion}</p>
                <p><strong>Fecha de Devolución:</strong> ${data.fechaDevolucion}</p>
                <p><strong>Empleado:</strong> ${data.empleado}</p>
                <p><strong>Recepción:</strong> ${data.recepcion ? data.recepcion.idRecepcion : 'No especificado'}</p>
            </div>
        </div>
        <hr>
        <h6 style="color: #8B4513;"><i class="fas fa-list me-2"></i>Detalles de la Devolución</h6>
        <div class="table-responsive">
            <table class="table table-striped table-hover" style="background-color: #FFEFD5;">
                <thead style="background-color: #D2691E; color: white;">
                    <tr>
                        <th>Suministro</th>
                        <th>Cantidad Devuelta</th>
                        <th>Status</th>
                        <th>Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.detallesDevolucion.length ? data.detallesDevolucion.map(detalle => `
                        <tr>
                            <td>${detalle.suministro || 'No especificado'}</td>
                            <td>${detalle.cantidadDevuelta || 'No especificada'}</td>
                            <td>${detalle.statusDevolucion || 'Desconocido'}</td>
                            <td>${detalle.motivo || 'No especificado'}</td>
                        </tr>
                    `).join('') : '<tr><td colspan="4" class="text-center">No hay detalles disponibles</td></tr>'}
                </tbody>
            </table>
        </div>
    </div>
`;

            $('#devolucionDetailContent').html(modalContent);
            $('#devolucionDetailModal').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
            alert('Error al cargar los detalles de la devolución');
        }
    });
});

});