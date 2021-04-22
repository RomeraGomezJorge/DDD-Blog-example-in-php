$(document).ready(function () {

    $(document).on('submit', 'form#delete-confirmation-form', function (e) {

        e.preventDefault();

        const modalSelector = '#delete-confirmation-modal';

        hideElementsCanCloseModal(
            '#close-delete-confirmation-modal-on-click-button-cancel',
            '#close-delete-confirmation-modal-on-click-top-cross'
        );

        disableCloseModalWhenClickOutside(modalSelector);

        replaceSubmitButtonWithLoadingSpinner('#confirm-delete-button');

        $.ajax({
            url: $('form#delete-confirmation-form').attr('action'),
            type: "POST",
            data: $('form#delete-confirmation-form').serialize(),
            cache: false,
            success: function (response) {

                if (response.status === 'fail_invalid_csfr_token') {
                    replaceModalContentByFailMessage(modalSelector, response.message);
                    return;
                }

                if (response.status === 'fail') {
                    replaceModalContentByFailMessage(modalSelector, response.message);
                    return;
                }

                changeStyleOfElementThatContainsDeleteItem();

                replaceModalContentBySuccessMessage(modalSelector, '¡El registro ha sido eliminado!');

                enableCloseModalWhenClickOutside(modalSelector);

            },
            error: function (responseText) {
                replaceModalContentByFailMessage(modalSelector, "Error al eliminar archivo ");
            }
        });
    });

    return false;
});


function changeStyleOfElementThatContainsDeleteItem() {

    const css_selector_to_handle_tr = $('input[name="css_selector_to_handle_tr_style_that_contains_items_to_delete"]').val();

    const numberOfTDInsideATr = $(css_selector_to_handle_tr).children('td').length;

    $(css_selector_to_handle_tr).html('' +
        '<td colspan="' + numberOfTDInsideATr + '">' +
        '<div class="text-center"  role="alert">' +
        '    <span class="text-info">' +
        '        <i class="fas fa-info-circle"></i>' +
        '   </span>' +
        '   Este registro ha sido eliminado.' +
        '</div>' +
        '</td>'
    );
}
