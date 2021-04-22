$(document).ready(function () {

    changeTheDefaultBehaviorOfTheEnterKey();

    const descriptionSelector = $('input[name="description"]');

    $('select[name="parent_id"]').on('change', function () {
        /* validate again just in case combination of new value parentId and description is in use*/
        addUniquePositionRule(descriptionSelector);
    });

    descriptionSelector.on('focusout', function () {
        addUniqueDescriptionRule(descriptionSelector);
    });

});


function addUniqueDescriptionRule(descriptionSelector) {

    const parentIdValue = $('select[name="parent_id"]').val();

    /* if not value selected for parentId no rule is add because this value is need to validate subcategory description */
    if (parentIdValue === null) {
        return false;
    }

    const description_from_database = descriptionSelector.data('description_from_database').toString();

    if (description_from_database === descriptionSelector.val().toString()) {
        descriptionSelector.rules("remove", "remote");
        return false;
    }

    const parentLabelSelected = $("select[name='parent_id'] option[value='" + parentIdValue + "']").text();

    const url_action = descriptionSelector.data('available_description_url');

    descriptionSelector.rules("add", {
        messages: {remote: "La descripción que ha ingresado ya está registrada en la categoria  \"\ " + parentLabelSelected + " \"\. "},
        remote: {
            url: url_action,
            type: "GET",
            data: {
                'description': function () {
                    return descriptionSelector.val();
                },
                'parent_id': function () {
                    return parentIdValue;
                }
            },
            dataType: 'json',
            complete: function (data) {
                const isAvailable = data.responseText.toString();

                if (isAvailable !== 'true') {
                    descriptionSelector.closest('.form-group').removeClass('has-success').addClass('has-error');
                    return;
                }

                descriptionSelector.closest('.form-group').removeClass('has-error').addClass('has-success');

            }, error: function () {
                alert('erro');
            }
        }

    });
}



