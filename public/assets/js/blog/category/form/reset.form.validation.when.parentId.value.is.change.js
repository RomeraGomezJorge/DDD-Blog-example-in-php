$(document).ready(function () {
    $('select[name="parent_id"]').on('change', function () {

        $("#form").validate().resetForm();

        $('input[name="position"],input[name="name"]').closest('.form-group').removeClass('has-error').removeClass('has-success');

    });

});