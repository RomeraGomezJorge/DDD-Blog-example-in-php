$(document).ready(function () {

    $('#mobile-actions-modal').on('show.bs.modal', function (event) {
        const modal_title = $(event.relatedTarget).data("modal_title");
        const edit_path = $(event.relatedTarget).data("edit_path");
        const delete_path = $(event.relatedTarget).data("delete_path");
        const url_delete_confirmation_modal = $(event.relatedTarget).data("url_delete_confirmation_modal");
        const css_selector_to_handle_tr_style_that_contains_items_to_delete = $(event.relatedTarget).data("css_selector_to_handle_tr_style_that_contains_items_to_delete");
        const id_to_delete = $(event.relatedTarget).data("id_to_delete");
        const message_to_delete_confirmation = $(event.relatedTarget).data("message_to_delete_confirmation");

        /* Establece el titulo del modal*/
        $('#modal-title').html(modal_title);

        /* Establece la url que permite actualizar un registro*/
        $('#edit-mobile').attr('href', edit_path);

        $('#delete-mobile').data('delete_path', delete_path);
        $('#delete-mobile').data('url_delete_confirmation_modal', url_delete_confirmation_modal);
        $('#delete-mobile').data('css_selector_to_handle_tr_style_that_contains_items_to_delete', css_selector_to_handle_tr_style_that_contains_items_to_delete);
        $('#delete-mobile').data('id_to_delete', id_to_delete);
        $('#delete-mobile').data('message_to_delete_confirmation', message_to_delete_confirmation);

    });

    $('#delete-confirmation-modal').on('show.bs.modal', function (event) {
        $('#mobile-actions-modal').modal('hide');
    });
});


