$(document).ready(function () {
    const allowed_files = $('input[name="attachment[0][file]"]').attr('accept');
    $('input[name="attachment[0][file]"]').rules("add", {
        messages: "El archivo seleccionado no esta permitido.",
        accept: allowed_files
    });
});