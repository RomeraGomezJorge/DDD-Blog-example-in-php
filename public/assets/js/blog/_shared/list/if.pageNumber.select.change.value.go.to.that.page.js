$(document).ready(function () {
    $('#pageNumber').change(function (e) {

        e.preventDefault();

        const page_url = $(this).children('option:selected').data('page_url');

        window.location.href = page_url;
    })
});