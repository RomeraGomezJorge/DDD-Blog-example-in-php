$(document).ready(function () {

    const nameSelector = $('input[name="name"]');

    /* disablingEnteKeyForForm() FIX: prevent an exception, because if is enabled can submit the data without validate is a name is  already in use */
    changeTheDefaultBehaviorOfTheEnterKey();

    nameSelector.on('focusout', function () {
        addUniqueCategoryNameRule(nameSelector);
    });
});


function addUniqueCategoryNameRule(inputSelector) {

    const name_from_database = inputSelector.data('name_from_database');

    if (name_from_database == inputSelector.val()) {
        inputSelector.rules("remove", "remote");
        return false;
    }

    const url_action = inputSelector.data('available_name_url');

    inputSelector.rules("add", {
        messages: {remote: "El nombre que ha ingresado ya está registrado."},
        remote: {
            async: false,
            url: url_action,
            type: "GET",
            data: {
                'name': function () {
                    return inputSelector.val();
                }
            },
            dataType: 'json',
            complete: function (data) {
                const isAvailable = data.responseText;

                if (isAvailable !== 'true') {
                    inputSelector.closest('.form-group').removeClass('has-success').addClass('has-error');
                    return;
                }

                inputSelector.closest('.form-group').removeClass('has-error').addClass('has-success');

            }, error: function () {
                alert('erro');
            }
        }

    });
}



