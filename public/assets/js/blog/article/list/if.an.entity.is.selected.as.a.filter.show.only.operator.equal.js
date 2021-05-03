$(document).ready(function () {

    $('select[name="filters[0][field]"]').change(function () {

        if ($(this).val() === 'category') {
            $('select[name="filters[0][operator]"]  option').each(function () {
                if ($(this).val() != '=' ) {
                    $(this).hide();
                }
            });
            return;
        }

        $('select[name="filters[0][operator]"]  option').each(function () {
            $(this).show();
        });

    });


});
