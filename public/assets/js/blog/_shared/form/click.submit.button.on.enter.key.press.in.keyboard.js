function changeTheDefaultBehaviorOfTheEnterKey() {
    $(document).keypress(
        function (event) {

            if (event.which != '13') {
                return
            }

            var elements = document.getElementById("form").elements;

            for (var i = 0, element; element = elements[i++];) {
                element.focus();
            }


            $('button[type="submit"]').focus();
            event.preventDefault();
            $('button[type="submit"]').click();

        });
}