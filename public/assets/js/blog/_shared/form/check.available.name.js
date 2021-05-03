$(document).ready(function () {

    const inputSelector = $('input[name="name"]');

    /* disablingEnteKeyForForm() FIX: prevent an exception, because if is enabled can submit the data without validate is a name is  already in use */
    changeTheDefaultBehaviorOfTheEnterKey();

    $('input[name="name"]').on('focusout', function () {
        addUniqueCategoryNameRule(inputSelector);
    });
});
