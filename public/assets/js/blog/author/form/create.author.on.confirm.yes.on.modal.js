$(document).ready(function () {

    const createAuthorForm = $("form#create-author-form");

    createAuthorForm.validate({
        onfocusout: false,
        onkeyup: false,
        highlight: function (element) {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function (element) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },
        submitHandler: function (formA, event) {

            event.preventDefault();

            submitsAuthorFormViaAjaxWhenIsValid(createAuthorForm);
        }
    });

});

function submitsAuthorFormViaAjaxWhenIsValid(createAuthorForm) {

    const errorDetails = 'La autor no se creó, si el problema persiste ponte en contacto con soporte tecnico';

    const createAuthorModalSelector = '#create-author-modal';

    if (createAuthorForm.data('isRequestRunning')) {
        return;
    }

    hideElementsCanCloseModal(
        '#close-create-author-modal-on-click-button-cancel',
        '#close-create-author-modal-on-click-top-cross'
    );

    createAuthorForm.data('isRequestRunning', true);

    disableCloseModalWhenClickOutside(createAuthorModalSelector);

    replaceSubmitButtonWithLoadingSpinner('#create-author-submit');

    const id = $(createAuthorModalSelector + ' input[name="id"]').val();

    const fullname = $(createAuthorModalSelector + ' input[name="fullname"]').val();

    const isSelectThisAuthorChecked = $('input[name="select_this_author_on_save"]').is(':checked');

    $.ajax({
        url: createAuthorForm.attr('action'),
        type: "POST",
        data: createAuthorForm.serialize(),
        cache: false,
        success: function (response) {

            const successMessage = '¡La autor ha sido creado!';

            createAuthorForm.data('isRequestRunning', false);

            if (response.status !== 'success') {

                $(createAuthorModalSelector).html(response.html);

                return false;
            }

            replaceModalContentBySuccessMessage(createAuthorModalSelector, successMessage);

            enableCloseModalWhenClickOutside(createAuthorModalSelector);

            const checked = isSelectThisAuthorChecked ? 'checked' : '';

            $('<br>' +
                '<label class="form-radio-label">' +
                '<input class="form-radio-input" type="radio" name="author_id" value="' + id + '" ' + checked + ' >' +
                '<span class="form-radio-sign">' + fullname + '</span>' +
                '</label>').insertAfter('#authors label:first');
        },
        error: function () {
            replaceModalContentByFailMessage(createAuthorModalSelector, errorDetails);
            createAuthorForm.data('isRequestRunning', false);
        },
        complete: function () {
            createAuthorForm.data('isRequestRunning', false);
        }
    });
}
