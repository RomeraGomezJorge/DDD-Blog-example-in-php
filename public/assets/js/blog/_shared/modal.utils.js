/* prevent user close modal when data is being processed */
function hideElementsCanCloseModal(topCrossSelector, cancelButtonSelector) {

    $(cancelButtonSelector).hide();

    $(topCrossSelector).hide();
}

/* prevent user close modal when data is being processed */
function disableCloseModalWhenClickOutside(modalSelector) {
    $(modalSelector).data('bs.modal')._config.keyboard = false;
    $(modalSelector).data('bs.modal')._config.backdrop = 'static';
}

/* notify user with spinner on submit button that data is being processed */
function replaceSubmitButtonWithLoadingSpinner(submitButtonSelector) {
    $(submitButtonSelector).addClass('is-loading');
    $(submitButtonSelector).attr('is-loading');
}

/*Replace innerHTML of a class '.modal-content' in a modal to notify user that something was right */
function replaceModalContentBySuccessMessage(modalSelector, successMessage) {

    $(modalSelector).html('' +
        '<div class="modal-dialog modal-confirm">' +
        '   <div class="modal-content">' +
        '      <div class="modal-header flex-column">' +
        '         <div class="icon-box text-success" >' +
        '           <i class="fas fa-check" ></i>' +
        '         </div>' +
        '         <h4 class="modal-title w-100">' + successMessage + '</h4>' +
        '         <button type="button" class="close d-none d-sm-block" data-dismiss="modal" aria-hidden="true">×</button>' +
        '      </div>' +
        '      <div class="modal-footer justify-content-center">' +
        '         <button type="button" class="btn btn-success text-white btn-block" data-dismiss="modal" autofocus>' +
        '             <span class="btn-label"> ' +
        '                 <i class="fas fa-check-circle"></i> ' +
        '             </span> ' +
        '              Continuar' +
        '         </button>' +
        '      </div>' +
        '   </div>' +
        '</div>');

    hideModalAfterFewSeconds(modalSelector);
}

/*Replace innerHTML of a class '.modal-content' in a modal to notify user that something was wrong */
function replaceModalContentByFailMessage(modalSelector, errorDetails, millisecondsToShowErrorMessage = 5000) {
    $(modalSelector).html('' +
        '   <div class="modal-dialog modal-confirm">' +
        '           <div class="modal-content">' +
        '              <div class="modal-header flex-column">' +
        '                 <div class="icon-box text-danger">' +
        '                   <i class="fas fa-times" ></i>' +
        '                 </div>' +
        '                 <h4 class="modal-title w-100">¡Ha ocurrido un error!</h4>' +
        '                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
        '              </div>' +
        '              <div class="modal-body modal-message-to-delete-confirmation">' + errorDetails + '</div>' +
        '              <div class="modal-footer justify-content-center">' +
        '                 <button type="button" class="btn btn-focus btn-block" data-dismiss="modal" >' +
        '                     <span class="btn-label"> ' +
        '                          <i class="fas fa-times-circle"></i> ' +
        '                      </span> ' +
        '                     Cerrar' +
        '                  </button>' +
        '           </div>' +
        '       </div>' +
        '   </div>');

    hideModalAfterFewSeconds(modalSelector, millisecondsToShowErrorMessage);
}

/*Replace innerHTML of a class '.modal-content' in a modal to notify user that something is processing */
function replaceModalContentByLoadingMessage(modalSelector) {
    $(modalSelector).html('' +
        '<div class="modal-dialog modal-confirm">' +
        '   <div class="modal-content">' +
        '       <div class="modal-header flex-column">' +
        '          <div class="icon-box text-primary fa-3x" >' +
        '            <i class="fas fa-sync fa-spin" ></i>' +
        '          </div>' +
        '          <h4 class="modal-title w-100">Cargando...</h4>' +
        '       </div>' +
        '   </div>' +
        '</div>');


}

function renderFormToHandleDataInModal(event, modalSelector, urlToGetFormHtml, data = '') {
    const errorDetails = 'Ha sucedido algo inesperado al intentar eliminar, por favor intente nuevamente.Si el error persiste póngase en contacto con el administrador del sistema.';

    $.ajax({
        url: urlToGetFormHtml,
        type: "GET",
        data: data,
        cache: false,
        success: function (response) {
            if (response.status != 'success') {
                replaceModalContentByFailMessage(modalSelector, errorDetails);
                return;
            }

            $(modalSelector).html(response.html);

        },
        error: function () {
            replaceModalContentByFailMessage(modalSelector, errorDetails);
        }
    });
}

/* allow user close modal because data has been processed */
function enableCloseModalWhenClickOutside(modalSelector) {

    $(modalSelector).data('bs.modal')._config.keyboard = true;
    $(modalSelector).data('bs.modal')._config.backdrop = true;
}

function hideModalAfterFewSeconds(modalSelector, closeModalAfter = 2000) {
    const modal = modalSelector;

    setTimeout(function () {
        $(modal).modal('hide');
    }, closeModalAfter)

}
