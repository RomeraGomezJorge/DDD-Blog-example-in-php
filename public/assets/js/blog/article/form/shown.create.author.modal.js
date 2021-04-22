$(document).ready(function () {

    $('#create-author-modal').on('show.bs.modal', function (event) {

        const urlToGetFormHtml = $(event.relatedTarget).data("author_create_modal_url");

        const modalSelector = '#create-author-modal';

        renderFormToHandleDataInModal(event, modalSelector, urlToGetFormHtml);
    });

});
