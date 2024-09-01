
$(document).ready(function() {
    $('#addRow').click(function() {
        var newRow = $('#productTable tbody tr:first').clone();
        newRow.find('input').val('');
        newRow.find('select').val('');
        $('#productTable tbody').append(newRow);
    });
});
