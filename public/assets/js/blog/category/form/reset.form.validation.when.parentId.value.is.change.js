$(document).ready(function () {
    $('select[name="parent_id"]').on('change', function () {

        $("#form").validate().resetForm();

        $('input[name="position"],input[name="description"]').closest('.form-group').removeClass('has-error').removeClass('has-success');

    });

});