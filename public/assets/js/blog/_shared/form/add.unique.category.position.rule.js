function addUniqueCategoryPositionRule(inputSelector) {
    const position_from_database = inputSelector.data('position_from_database').toString();

    if (inputSelector.val().trim() !== '' && position_from_database == inputSelector.val().toString()) {
        /* if user has insert some data and this data is not equal to the database value */
        inputSelector.rules("remove", "remote");
        return false;
    }

    const url_action = inputSelector.data('available_position_url');

    inputSelector.rules("add", {
        messages: {remote: "La posición que ha ingresado ya está registrada."},
        remote: {
            url: url_action,
            type: "GET",
            data: {
                'position': function () {
                    return inputSelector.val().trim();
                }
            },
            dataType: 'json',
            complete: function (data) {
                const isAvailable = data.responseText.toString();

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



