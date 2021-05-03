$(document).ready(function () {

    $('select[name="filters[0][field]"]').change(function () {

        if ($(this).val() !== 'createAt') {

            $('input[name="filters[0][value]"]').inputmask("remove");
            return false;
        }


        $('input[name="filters[0][value]"]').inputmask("datetime", {
            "placeholder": "dd-mm-yyyy hh:mm:ss",
            "inputFormat": "dd-mm-yyyy HH:MM:ss",
            "hourFormat": "24",
        });

    });


});

