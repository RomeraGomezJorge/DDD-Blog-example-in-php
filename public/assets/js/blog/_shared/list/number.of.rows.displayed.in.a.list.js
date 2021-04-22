$(document).ready(function () {

    $('select[name="limit"]').change(function (e) {

        e.preventDefault();

        const actionUrl = $('#number-of-rows-displayed-in-a-list').attr('action');

        const limit = $('select[name="limit"]').val();

        const filters = $('#number-of-rows-displayed-in-a-list input[name="filters"]').val();

        window.location.href = actionUrl + '/rows_per_page-' + limit + '/' + filters;
    });
});
