$(document).ready(function () {
    $('select[name="filters[0][field]"]').change(function () {
        $('input[name="filters[0][value]"]').val('');
    });
});