$(document).ready(function () {

    $('select[name="filters[0][field]"]').change(function () {

        if ($(this).val() === 'parent_id') {
            $('select[name="filters[0][operator]"]  option').each(function () {
                if ($(this).val() == 'CONTAINS' || $(this).val() == '<>') {
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
