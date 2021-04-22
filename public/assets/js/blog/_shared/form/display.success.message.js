$(document).ready(function () {
    var placementFrom = $('#notify_placement_from option:selected').val();
    var placementAlign = $('#notify_placement_align option:selected').val();
    var content = {};

    content.title = getNotificationMessage();
    content.message = '';
    content.icon = 'fas fa-check';

    $.notify(content, {
        type: 'success',
        placement: {
            from: placementFrom,
            align: placementAlign
        },
        time: 1000,
        delay: 8000,
    });
});
