$(document).ready(
    function () {
        var placementFrom = $('#notify_placement_from option:selected').val();
        var placementAlign = $('#notify_placement_align option:selected').val();
        var content = {};

        content.message = 'Uno o más valores de los ingresados no son validos.';
        content.title = 'No se pudo completar la operación.';
        content.icon = 'fas fa-times';

        var notify = $.notify(content, {
            type: 'danger',
            placement: {
                from: placementFrom,
                align: placementAlign
            },
            time: 1000,
            delay: 18000,
        });
    }
);

