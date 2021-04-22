$(document).ready(function () {

    appendRuleAtLeastOneNumber();

    appendRuleAtLeastOneUppercase();

    appendRuleRequiredRoleId();
});

function appendRuleAtLeastOneUppercase() {
    $.validator.addMethod("atLeastOneUppercase", function (value) {
        return /[A-Z]/.test(value);
    }, "Al menos un caracter debe estar en mayúscula.");

    $('input[name="password"]').rules("add", {
        atLeastOneUppercase: true
    });
}

function appendRuleAtLeastOneNumber() {
    $.validator.addMethod("atLeastOneNumber", function (value) {
        return /[0-9]/.test(value);
    }, "Al menos un caracter debe ser numerico.");

    $('#resetPassword').rules("add", {
        atLeastOneNumber: true
    });
}

function appendRuleRequiredRoleId() {
    $('input[name="role_id"]').rules("add", {
        required: true
    });
}
