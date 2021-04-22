$(document).ready(function () {

    /*find closest selector of field Description to the clicked submit button*/
    const positionSelector = $('input[name="position"]');

    /* disablingEnteKeyForForm() FIX: prevent an exception, because if is enabled can submit the data without validate is a description is  already in use */
    changeTheDefaultBehaviorOfTheEnterKey();

    positionSelector.on('focusout', function () {
        addUniqueCategoryPositionRule(positionSelector);
    });

});
