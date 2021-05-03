$(document).ready(function () {

    changeTheDefaultBehaviorOfTheEnterKey();

    const nameSelector = $('input[name="name"]');

    $('select[name="parent_id"]').on('change', function () {
        /* validate again just in case combination of new value parentId and name is in use*/
        addUniquePositionRule(nameSelector);
    });

    nameSelector.on('focusout', function () {
        addUniqueDescriptionRule(nameSelector);
    });

});


function addUniqueDescriptionRule(nameSelector) {

    const parentIdValue = $('select[name="parent_id"]').val();

    /* if not value selected for parentId no rule is add because this value is need to validate subcategory name */
    if (parentIdValue === null) {
        return false;
    }

    const name_from_database = nameSelector.data('name_from_database').toString();

    if (name_from_database === nameSelector.val().toString()) {
        nameSelector.rules("remove", "remote");
        return false;
    }

    const parentLabelSelected = $("select[name='parent_id'] option[value='" + parentIdValue + "']").text();

    const url_action = nameSelector.data('available_name_url');

    nameSelector.rules("add", {
        messages: {remote: "El nombre que ha ingresado ya está registrado en la categoria  \"\ " + parentLabelSelected + " \"\. "},
        remote: {
            url: url_action,
            type: "GET",
            data: {
                'name': function () {
                    return nameSelector.val();
                },
                'parent_id': function () {
                    return parentIdValue;
                }
            },
            dataType: 'json',
            complete: function (data) {
                const isAvailable = data.responseText.toString();

                if (isAvailable !== 'true') {
                    nameSelector.closest('.form-group').removeClass('has-success').addClass('has-error');
                    return;
                }

                nameSelector.closest('.form-group').removeClass('has-error').addClass('has-success');

            }, error: function () {
                alert('erro');
            }
        }

    });
}



