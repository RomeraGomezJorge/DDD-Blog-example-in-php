$(document).ready(function () {

    $('#delete-confirmation-modal').on('show.bs.modal', function (event) {
        const data = getDataFromButtonWasClickedToRenderForm(event);

        const urlToGetFormHtml = $(event.relatedTarget).data("url_delete_confirmation_modal");

        const modalSelector = '#delete-confirmation-modal';

        replaceModalContentByLoadingMessage(modalSelector);

        renderFormToHandleDataInModal(event, modalSelector, urlToGetFormHtml, data)
    });

});

function getDataFromButtonWasClickedToRenderForm(event) {
    return {
        id: $(event.relatedTarget).data('id_to_delete'),
        delete_path: $(event.relatedTarget).data('delete_path'),
        css_selector_to_handle_tr_style_that_contains_items_to_delete: $(event.relatedTarget).data('css_selector_to_handle_tr_style_that_contains_items_to_delete'),
        data_about_item: $(event.relatedTarget).data("message_to_delete_confirmation")
    }
}
