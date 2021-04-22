$(document).ready(function () {

    $('#create-category-modal').on('show.bs.modal', function (event) {

        const urlToGetFormHtml = $(event.relatedTarget).data("category_create_modal_url");

        const modalSelector = '#create-category-modal';

        renderFormToHandleDataInModal(event, modalSelector, urlToGetFormHtml);
    });

});
