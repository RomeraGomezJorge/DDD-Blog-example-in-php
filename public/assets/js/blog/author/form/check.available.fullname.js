$(document).ready(function () {

    const inputSelector = $('input[name="fullname"]');

    /* disablingEnteKeyForForm() FIX: prevent an exception, because if is enabled can submit the data without validate is a fullname is  already in use */
    changeTheDefaultBehaviorOfTheEnterKey();

    inputSelector.on('focusout', function () {
        addUniqueDescriptionRule(inputSelector);
    });

});


function addUniqueDescriptionRule(inputSelector) {

    const url_action = inputSelector.data('available_fullname_url');

    const fullname_from_database = inputSelector.data('fullname_from_database');

    if (fullname_from_database === inputSelector.val().trim()) {
        inputSelector.rules("remove", "remote");
        return;
    }

    inputSelector.rules("add", {
        messages: {remote: "El nombre completo que ha ingresado ya está registrado."},
        remote: {
            async: false,
            url: url_action,
            type: "GET",
            data: {
                'fullname': function () {
                    return inputSelector.val().trim();
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



