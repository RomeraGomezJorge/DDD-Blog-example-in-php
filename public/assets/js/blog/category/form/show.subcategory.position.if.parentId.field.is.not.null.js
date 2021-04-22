$(document).ready(function () {
    const parentIdSelector = $('select[name="parent_id"]');

    const positionContainer = $('#description-container, #position-container');

    if (parentIdSelector.val() !== null) {

        positionContainer.removeClass('d-none');
        $('input[name="description"]').focus();
    }

    parentIdSelector.on('change', function () {
        if ($(this).val() === null) {
            return false
        }

        positionContainer.removeClass('d-none');

    });


});