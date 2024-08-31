$(document).ready(function() {
    $('#Ordenes_compras_idOrden_compra').change(function() {
        var idOrdenCompra = $(this).val();
        if(idOrdenCompra) {
            $.ajax({
                url: '/get-orden-compra-details/' + idOrdenCompra,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#fechaEmision').text(data.fecha_emision);
                    $('#fechaEntrega').text(data.fecha_entraga);
                    $('#proveedor').text(data.proveedor);
                    $('#total').text(data.total_pagar);
                    
                    var productosHtml = '<table class="table"><thead><tr><th>Suministro</th><th>Cantidad Pedida</th><th>Precio Unitario</th><th>Subtotal</th></tr></thead><tbody>';
                    data.productos.forEach(function(producto) {
                        productosHtml += '<tr><td>' + producto.nombre_suministro + 
                                         '</td><td>' + producto.cantidad + 
                                         '</td><td>$' + producto.precio_unitario + 
                                         '</td><td>$' + producto.subtotal + '</td></tr>';
                    });
                    productosHtml += '</tbody></table>';
                    $('#listaProductos').html(productosHtml);
                    
                    $('#detallesOrdenCompra').show();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        } else {
            $('#detallesOrdenCompra').hide();
        }
    });

    $('#addRow').click(function() {
        var newRow = $('#productTable tbody tr:first').clone();
        newRow.find('input').val('');
        newRow.find('select').val('');
        $('#productTable tbody').append(newRow);
    });

});

// Manejar el clic en el botón "Ver"
$('.view-order').click(function(e) {
    e.preventDefault();
    var recepcionId = $(this).data('id');
    
    $.ajax({
        url: '/recepcion/' + recepcionId,
        type: 'GET',
        success: function(response) {
            console.log('Respuesta completa:', response);

            var html = '<div class="recepcion-info">';
            html += '<h4 class="mb-3">Recepción #' + response.idRecepcion_mercancia + '</h4>';
            
            // Formateo de la fecha
            var fechaFormateada = 'Fecha no disponible';
            if (response.fecha_recepcion) {
                var fecha = new Date(response.fecha_recepcion);
                if (!isNaN(fecha.getTime())) {
                    fechaFormateada = fecha.toLocaleDateString('es-ES', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit'
                    });
                }
            }
            
            html += '<p class="mb-2"><strong>Fecha de Recepción:</strong> ' + fechaFormateada + '</p>';
            html += '<p class="mb-2"><strong>Empleado:</strong> ' + response.empleado.nombre_empleado + ' ' + response.empleado.apellido_empleado + '</p>';
            html += '<p class="mb-2"><strong>Orden de Compra:</strong> ' + response.ordenes_compra.idOrden_compra + '</p>';
            html += '<p class="mb-0"><strong>Proveedor:</strong> ' + response.ordenes_compra.proveedore.nombre_empresa + '</p>';
            html += '</div>';
            html += '</div>';
            html += '<h5 class="mb-3">Detalles de los productos:</h5>';
            html += '<div class="table-responsive"><table class="table table-striped table-hover detalles-table">' +
                    '<thead class="table-light"><tr>' +
                    '<th>Suministro Pedido</th>' +
                    '<th>Suministro Recibido</th>' +
                    '<th>Cantidad Pedida</th>' +
                    '<th>Cantidad Recibida</th>' +
                    '<th>Precio Unitario</th>' +
                    '<th>Subtotal</th>' +
                    '<th>Estado</th>' +
                    '</tr></thead><tbody>';
            response.detalles.forEach(function(detalle) {
                html += '<tr>';
                html += '<td>' + detalle.suministro_pedido + '</td>';
                html += '<td>' + detalle.suministro_recibido + '</td>';
                html += '<td>' + detalle.cantidad_pedida + '</td>';
                html += '<td>' + detalle.cantidad_recibida + '</td>';
                html += '<td>$' + (detalle.precio_unitario !== 'N/D' ? parseFloat(detalle.precio_unitario).toFixed(2) : 'N/D') + '</td>';
                html += '<td>$' + (detalle.subtotal !== 'N/D' ? parseFloat(detalle.subtotal).toFixed(2) : 'N/D') + '</td>';
                html += '<td><span class="status-badge badge ' + (detalle.status_recepcion == 1 ? 'bg-success' : 'bg-danger') + '">' 
                     + (detalle.status_recepcion == 1 ? 'Aceptado' : 'Rechazado') + '</span></td>';
                html += '</tr>';
            });
            html += '</tbody></table></div>';

            $('#modalBody').html(html);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            $('#modalBody').html('<div class="alert alert-danger">Hubo un error al cargar los detalles. Por favor, intenta de nuevo.</div>');
        }
    });
});
