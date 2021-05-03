$(document).ready(function () {
    setTimeout(function () {

        $('.filer-button').fadeIn();

        setTimeout(
            function () {
                $('.filer-button').removeClass('show-button-name');
            }, 4000
        );

    }, 1000);
});