$(document).ready(function () {

    const inputSelector = $('input[name="description"]');

    /* disablingEnteKeyForForm() FIX: prevent an exception, because if is enabled can submit the data without validate is a description is  already in use */
    changeTheDefaultBehaviorOfTheEnterKey();

    $('input[name="description"]').on('focusout', function () {
        addUniqueCategoryDescriptionRule(inputSelector);
    });
});
