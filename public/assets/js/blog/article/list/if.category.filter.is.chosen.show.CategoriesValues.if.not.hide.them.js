$(document).ready(function () {

    $('select[name="filters[0][field]"]').change(function () {

        if ($(this).val() === 'category') {
            enableInput('#selectValueFilter');
            disableInput('#inputValueFilter');
            return false;
        }

        enableInput('#inputValueFilter');
        disableInput('#selectValueFilter');
    });

    function enableInput(selector) {
        const inputValueName = 'filters[0][value]';
        $(selector).removeClass('d-none').attr('name', inputValueName);
    }

    function disableInput(selector) {
        $(selector).removeAttr('name').addClass('d-none');
    }

});

