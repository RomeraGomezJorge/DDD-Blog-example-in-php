$(document).ready(function () {

    const createCategoryForm = $("form#create-category-form");

    createCategoryForm.validate({
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

            submitsFormViaAjaxWhenIsValid(createCategoryForm);
        }
    });

});

function submitsFormViaAjaxWhenIsValid(createCategoryForm) {

    const errorDetails = 'La categoria no se creó, si el problema persiste ponte en contacto con soporte tecnico';

    const successMessage = '¡La Categoria ha sido creada!';

    const createCategoryModalSelector = '#create-category-modal';

    if (createCategoryForm.data('isRequestRunning')) {
        return;
    }

    hideElementsCanCloseModal(
        '#close-create-category-modal-on-click-button-cancel',
        '#close-create-category-modal-on-click-top-cross'
    );

    createCategoryForm.data('isRequestRunning', true);

    disableCloseModalWhenClickOutside(createCategoryModalSelector);

    replaceSubmitButtonWithLoadingSpinner('#create-category-submit');

    const id = $(createCategoryModalSelector + ' input[name="id"]').val();

    const name = $(createCategoryModalSelector + ' input[name="name"]').val();

    const isSelectThisCategoryChecked = $('input[name="select_this_category_on_save"]').is(':checked');

    $.ajax({
        url: createCategoryForm.attr('action'),
        type: "POST",
        data: createCategoryForm.serialize(),
        cache: false,
        success: function (response) {

            createCategoryForm.data('isRequestRunning', false);

            if (response.status !== 'success') {

                $(createCategoryModalSelector).html(response.html);

                return false;
            }

            replaceModalContentBySuccessMessage(createCategoryModalSelector, successMessage);

            enableCloseModalWhenClickOutside(createCategoryModalSelector);

            createNewSelectOptionInCategory(id, name, isSelectThisCategoryChecked);
        },
        error: function () {
            const errorDetails = 'La categoria no se creó, si el problema persiste ponte en contacto con soporte tecnico';
            replaceModalContentByFailMessage(createCategoryModalSelector, errorDetails);
            createCategoryForm.data('isRequestRunning', false);
        },
        complete: function () {
            createCategoryForm.data('isRequestRunning', false);
        }
    });
}


function createNewSelectOptionInCategory(id, name, isSelectThisCategoryChecked) {
    const selected = isSelectThisCategoryChecked ? 'selected="selected"' : '';

    $('select[name="category_id"]').append("<option " + selected + "  value=" + id + " >" + name + " </option>");

    $('select[name="parent_id"]').append("<option " + selected + "  value=" + id + " >" + name + " </option>");

    /* if we are on subcategory module throw event change() to show the other fields */
    $('select[name="parent_id"]').change();

}