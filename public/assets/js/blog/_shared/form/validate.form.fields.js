$(document).ready(function () {

    $("#form").validate({

        onfocusout: function (element) {
            if (element.tagName === "TEXTAREA" || (element.tagName === "INPUT" && element.type !== "password")) {
                element.value = $.trim(element.value).toString();
            }

            /* validate a field on focus out*/
            $(element).valid();

        },
        onkeyup: false,
        submitHandler: function () {
            /*when an valid form is submitted. Disabled submit button and set the loading icon*/
            $("#submitBtn i").removeClass('fas fa-save');
            $("#submitBtn i").addClass('fas fa-sync-alt fa-spin fa-3x fa-fw');
            $("#submitBtn ").attr("disabled", true);
            form.submit();
        },
        invalidHandler: function () {
            /*when an invalid form is submitted. Enabled submit button and set the default icon*/
            $("#submitBtn i").removeClass('fas fa-sync-alt fa-spin fa-3x fa-fw');
            $("#submitBtn i").addClass(' fas fa-save');
            $("#submitBtn ").attr("disabled", false);

            $('html, body').animate({
                scrollTop: ($('.error').offset().top - 300)
            }, 500);

        },
        highlight: function (element) {
            $(element).closest('.form-group, .form-check').removeClass('has-success').addClass('has-error');
        },
        success: function (element) {
            $(element).closest('.form-group, .form-check').removeClass('has-error').addClass('has-success');
        },
        errorPlacement: function (error, element) {

            error.insertAfter(element.siblings(":last"));

            error.insertAfter(element.parent('.form-radio-label').siblings(":last"));
        }
    });


});