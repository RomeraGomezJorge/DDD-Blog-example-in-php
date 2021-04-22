$(document).ready(function () {

    const inputSelector = $('input[name="email"]');

    /* disablingEnteKeyForForm() FIX: prevent an exception, because if is enabled can submit the data without validate is a description is  already in use */
    changeTheDefaultBehaviorOfTheEnterKey();

    inputSelector.on('focusout', function () {
        addUniqueEmailRule(inputSelector);
    });
});

function addUniqueEmailRule(inputSelector) {

    const email_from_database = inputSelector.data('email_from_database');

    const url_action = inputSelector.data('available_email_url');

    /* if email write by the user is the same on database there's no need to check is in use */
    if (email_from_database === inputSelector.val().toString().trim()) {
        inputSelector.rules("remove", "remote");
        return false;
    }

    inputSelector.rules("add", {
        messages: {remote: "El correo que ha ingresado ya está registrado."},
        remote: {
            url: url_action,
            type: "GET",
            data: {
                'email': function () {
                    return inputSelector.val().trim();
                }
            },
            dataType: 'json',
            complete: function (data) {
                const isAvailable = data.responseText;

                if (isAvailable === 'true') {
                    inputSelector.closest('.form-group').removeClass('has-error').addClass('has-success');
                } else {
                    inputSelector.closest('.form-group').removeClass('has-success').addClass('has-error');

                }
            }, error: function () {
                alert('erro');
            }
        }

    });
}



