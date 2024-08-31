$(document).ready(function() {
    $('#Proveedores_idProveedores').change(function() {
        var idProveedor = $(this).val();
        if(idProveedor) {
            $.ajax({
                url: '/get-suministros-por-proveedor/' + idProveedor,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var options = '<option value="">Seleccionar Suministro</option>';
                    $.each(data, function(key, suministro) {
                        options += '<option value="' + suministro.idSuministro + '" data-precio="' + suministro.precio_unitario + '">' + suministro.nombre_suministro + '</option>';
                    });
                    $('.suministro-select').html(options);
                }
            });
        } else {
            $('.suministro-select').html('<option value="">Seleccionar Suministro</option>');
        }
    });

    $(document).on('change', '.suministro-select', function() {
        var precio = $(this).find(':selected').data('precio');
        $(this).closest('tr').find('.precio').val(precio);
        calculateSubtotal($(this).closest('tr'));
    });

    $(document).on('input', '.cantidad', function() {
        calculateSubtotal($(this).closest('tr'));
    });

    function calculateSubtotal(row) {
        var cantidad = parseFloat(row.find('.cantidad').val()) || 0;
        var precio = parseFloat(row.find('.precio').val()) || 0;
        var subtotal = cantidad * precio;
        row.find('.subtotal').val(subtotal.toFixed(2));
        calculateTotal();
    }

    function calculateTotal() {
        var total = 0;
        $('.subtotal').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#total').val(total.toFixed(2));
    }

    $('#addRow').click(function() {
        var newRow = $('#productTable tbody tr:first').clone();
        newRow.find('input').val('');
        $('#productTable tbody').append(newRow);
    });
});


$('.view-order').click(function(e) {
    e.preventDefault();
    var orderId = $(this).data('id');
    $.ajax({
        url: '/ordenescompra/' + orderId,
        type: 'GET',
        success: function(data) {
            var statusBadge = '';
            if (data.status == 1) {
                statusBadge = '<span class="badge" style="background-color: #228B22;">Enviado</span>';
            } else if (data.status == 0) {
                statusBadge = '<span class="badge" style="background-color: #B22222;">Cancelada</span>';
            } else {
                statusBadge = '<span class="badge" style="background-color: #4682B4;">Recibida</span>';
            }

            var modalContent = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 style="color: #8B4513;"><i class="fas fa-info-circle me-2"></i>Información General</h6>
                        <p><strong>ID:</strong> ${data.id}</p>
                        <p><strong>Fecha de Emisión:</strong> ${new Date(data.fecha_emision).toLocaleDateString()}</p>
                        <p><strong>Fecha de Entrega:</strong> ${new Date(data.fecha_entraga).toLocaleDateString()}</p>
                        <p><strong>Estado:</strong> ${statusBadge}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 style="color: #8B4513;"><i class="fas fa-user me-2"></i>Detalles de Contacto</h6>
                        <p><strong>Proveedor:</strong> ${data.proveedor ? data.proveedor.nombre : 'No especificado'}</p>
                        <p><strong>Empleado:</strong> ${data.empleado ? data.empleado.nombre : 'No especificado'}</p>
                    </div>
                </div>
                <hr style="border-color: #8B4513;">
                <h6 style="color: #8B4513;"><i class="fas fa-list me-2"></i>Detalles de la Orden</h6>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" style="background-color: #FFEFD5;">
                        <thead style="background-color: #D2691E; color: white;">
                            <tr>
                                <th>Suministro</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.detalles ? data.detalles.map(detalle => `
                                <tr>
                                    <td>${detalle.suministro ? detalle.suministro.nombre : 'No especificado'}</td>
                                    <td>${detalle.cantidad || 'No especificada'}</td>
                                    <td>$${detalle.precio_unitario ? detalle.precio_unitario.toFixed(2) : 'No especificado'}</td>
                                    <td>$${detalle.subtotal ? detalle.subtotal.toFixed(2) : 'No especificado'}</td>
                                </tr>
                            `).join('') : '<tr><td colspan="4" class="text-center">No hay detalles disponibles</td></tr>'}
                        </tbody>
                        <tfoot style="background-color: #DEB887;">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>$${data.total_pagar.toFixed(2)}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;
            $('#orderDetailContent').html(modalContent);
            $('#orderDetailModal').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
            alert('Error al cargar los detalles de la orden');
        }
    });
});