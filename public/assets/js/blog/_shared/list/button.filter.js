$(document).ready(function () {
    setTimeout(function () {

        $('.filer-button').fadeIn();

        setTimeout(
            function () {
                $('.filer-button').removeClass('show-button-description');
            }, 4000
        );

    }, 1000);
});