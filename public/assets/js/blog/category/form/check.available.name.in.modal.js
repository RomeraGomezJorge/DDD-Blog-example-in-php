$(document).ready(function () {

    const nameSelector = $('#create-category-form input[name="name"]');

    /* disablingEnteKeyForForm() FIX: prevent an exception, because if is enabled can submit the data without validate is a description is  already in use */
    changeTheDefaultBehaviorOfTheEnterKey();

    nameSelector.on('focusout', function () {
        addUniqueCategoryNameRule(nameSelector);
    });
});
