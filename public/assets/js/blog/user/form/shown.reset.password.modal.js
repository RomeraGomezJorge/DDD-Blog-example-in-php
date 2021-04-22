$(document).ready(function () {

    $('#reset-password-modal').on('show.bs.modal', function (event) {
        const id = $('#reset-password-button').data('id');

        const data = {'id': id};

        const urlToGetFormHtml = $(event.relatedTarget).data("reset_password_modal_url");

        const modalSelector = '#reset-password-modal';


        renderFormToHandleDataInModal(event, modalSelector, urlToGetFormHtml, data)
    });

});
