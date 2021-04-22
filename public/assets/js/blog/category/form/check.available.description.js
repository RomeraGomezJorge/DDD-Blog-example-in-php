$(document).ready(function () {

    const descriptionSelector = $('input[name="description"]');

    /* disablingEnteKeyForForm() FIX: prevent an exception, because if is enabled can submit the data without validate is a description is  already in use */
    changeTheDefaultBehaviorOfTheEnterKey();

    descriptionSelector.on('focusout', function () {
        addUniqueCategoryDescriptionRule(descriptionSelector);
    });
});


function addUniqueCategoryDescriptionRule(inputSelector) {

    const description_from_database = inputSelector.data('description_from_database');

    if (description_from_database == inputSelector.val()) {
        inputSelector.rules("remove", "remote");
        return false;
    }

    const url_action = inputSelector.data('available_description_url');

    inputSelector.rules("add", {
        messages: {remote: "La descripción que ha ingresado ya está registrada."},
        remote: {
            async: false,
            url: url_action,
            type: "GET",
            data: {
                'description': function () {
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



