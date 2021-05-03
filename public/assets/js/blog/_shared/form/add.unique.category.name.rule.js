function addUniqueCategoryNameRule(inputSelector) {

    const name_from_database = inputSelector.data('name_from_database');

    if (name_from_database == inputSelector.val().trim()) {
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



