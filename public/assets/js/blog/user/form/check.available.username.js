$(document).ready(function () {

    const inputSelector = $('input[name="username"]');

    /* disablingEnteKeyForForm() FIX: prevent an exception, because if is enabled can submit the data without validate is a description is  already in use */
    changeTheDefaultBehaviorOfTheEnterKey();

    inputSelector.on('focusout', function () {
        addUniqueUsernameRule(inputSelector);
    });
});

function addUniqueUsernameRule(inputSelector) {

    const user_name_from_database = inputSelector.data('user_name_from_database');

    const url_action = inputSelector.data('available_user_name_url');

    /* if user name write by the user is the same on database there's no need to check is in use */
    if (user_name_from_database === inputSelector.val().trim()) {
        inputSelector.rules("remove", "remote");
        return false;
    }

    inputSelector.rules("add", {
        messages: {remote: "El nombre de usuario que ha ingresado ya está registrado."},
        remote: {
            async: false,
            url: url_action,
            type: "GET",
            data: {
                'username': function () {
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



