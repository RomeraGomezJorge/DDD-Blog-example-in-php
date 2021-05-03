$(document).ready(function () {

    $('body').on('focusout', 'input[name*="[url]"]', function () {

        let userInputBValue = $(this).val();

        alert(userInputBValue)
        if (userInputBValue == ''){
            return false;
        }


        let youtube_id = youtube_parser(userInputBValue);

        if (youtube_id === false) {
            $(this).parent().addClass('has-error');
            $(this).next('.errorLabelContainer.form-text.text-muted.text-danger').html('<span class="has-error">El enlace ingresado no es valido</span>');
            $(this).val(userInputBValue);
            return
        }

        $(this).parent().removeClass('has-error').addClass('has-success');
        $(this).next('.errorLabelContainer.form-text.text-muted.text-danger').html('');

        $(this).val(youtube_parser(userInputBValue));

        function youtube_parser(url) {
            var regExp = /^https?\:\/\/(?:www\.youtube(?:\-nocookie)?\.com\/|m\.youtube\.com\/|youtube\.com\/)?(?:ytscreeningroom\?vi?=|youtu\.be\/|vi?\/|user\/.+\/u\/\w{1,2}\/|embed\/|watch\?(?:.*\&)?vi?=|\&vi?=|\?(?:.*\&)?vi?=)([^#\&\?\n\/<>"']*)/i;
            var match = url.match(regExp);
            return (match && match[1].length == 11) ? match[1] : false;
        }
    });

})
