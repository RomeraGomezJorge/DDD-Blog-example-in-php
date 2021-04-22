$(document).ready(function () {

    const inputSelector = $('input[name="title"]');

    /* disablingEnteKeyForForm() FIX: prevent an exception, because if is enabled can submit the data without validate is a title is  already in use */
    changeTheDefaultBehaviorOfTheEnterKey();

    inputSelector.on('focusout', function () {
        addUniqueTitleRule(inputSelector);
    });

});


function addUniqueTitleRule(inputSelector) {

    const url_action = inputSelector.data('available_title_url');

    const title_from_database = inputSelector.data('title_from_database');

    if (title_from_database === inputSelector.val().trim()) {
        inputSelector.rules("remove", "remote");
        return;
    }

    inputSelector.rules("add", {
        messages: {remote: "El titulo que ha ingresado ya está registrado."},
        remote: {
            async: false,
            url: url_action,
            type: "GET",
            data: {
                'title': function () {
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



