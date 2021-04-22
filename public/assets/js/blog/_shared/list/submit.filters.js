$(document).ready(function () {
    $('#filters-form').submit(function (e) {

        e.preventDefault();

        const actionUrl = $(this).attr('action');

        const filter = $(this).serialize();

        window.location.href = actionUrl + '/' + filter;

    })
});