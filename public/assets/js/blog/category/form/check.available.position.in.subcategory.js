$(document).ready(function () {

    changeTheDefaultBehaviorOfTheEnterKey();
    const positionSelector = $('input[name="position"]');


    $('select[name="parent_id"]').on('change', function () {
        /* validate again just in case combination of new value parentId and position is in use*/
        addUniquePositionRule(positionSelector);
    });


    positionSelector.on('focusout', function () {
        addUniquePositionRule(positionSelector);
    });

});


function addUniquePositionRule(positionSelector) {
    const parentIdValue = $('select[name="parent_id"]').val();

    /* if not value selected for parentId no rule is add because this value is need to validate subcategory position */
    if (parentIdValue === null) {
        return false;
    }

    const position_from_database = positionSelector.data('position_from_database');

    if (position_from_database === positionSelector.val().toString().trim()) {
        positionSelector.rules("remove", "remote");
        return false;
    }

    const parentLabelSelected = $("select[name='parent_id'] option[value='" + parentIdValue + "']").text();

    const url_action = positionSelector.data('available_position_url');

    positionSelector.rules("add", {
        messages: {remote: "La posición que ha ingresado ya está registrada en la categoria  \"\ " + parentLabelSelected + " \"\. "},
        remote: {
            url: url_action,
            type: "GET",
            data: {
                'position': function () {
                    return positionSelector.val().trim();
                },
                'parent_id': function () {
                    return parentIdValue.trim();
                }
            },
            dataType: 'json',
            complete: function (data) {
                const isAvailable = data.responseText.toString();

                if (isAvailable !== 'true') {
                    positionSelector.closest('.form-group').removeClass('has-success').addClass('has-error');
                    return;
                }

                positionSelector.closest('.form-group').removeClass('has-error').addClass('has-success');

            }, error: function () {
                alert('erro');
            }
        }

    });
}



