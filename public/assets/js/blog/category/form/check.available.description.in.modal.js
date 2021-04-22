$(document).ready(function () {

    const descriptionSelector = $('#create-category-form input[name="description"]');

    /* disablingEnteKeyForForm() FIX: prevent an exception, because if is enabled can submit the data without validate is a description is  already in use */
    changeTheDefaultBehaviorOfTheEnterKey();

    descriptionSelector.on('focusout', function () {
        addUniqueCategoryDescriptionRule(descriptionSelector);
    });
});
