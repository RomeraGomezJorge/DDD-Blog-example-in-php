$(document).ready(function () {

    $('input[name="password"]').on('keyup', function () {

        const password = $(this).val();

        constraintNumberOfCharacters(password);

        constraintsAtLeastOneUppercaseCharacter(password);

        constraintsAtLeastOneNumber(password);

    });
});

function constraintNumberOfCharacters(password) {

    const numberOfCharacters = password.length;

    const numberOfCharactersStyle = (numberOfCharacters >= 8) ? 'fas fa-check-circle text-success' : 'far fa-circle';

    $("#atLeastEightCharacters").attr('class', numberOfCharactersStyle);
}


function constraintsAtLeastOneUppercaseCharacter(password) {
    const atLeastOneUppercasePattern = /[A-Z]/;

    const atLeastOneUppercaseStyle = atLeastOneUppercasePattern.test(password) ? 'fas fa-check-circle text-success' : 'far fa-circle';

    $("#atLeastOneCapital").attr('class', atLeastOneUppercaseStyle);
}

function constraintsAtLeastOneNumber(password) {

    const atLeastOneNumberPattern = /[0-9]/;

    const atLeastOneNumberPatternStyle = atLeastOneNumberPattern.test(password) ? 'fas fa-check-circle text-success' : 'far fa-circle';

    $("#atLeastOneNumber").attr('class', atLeastOneNumberPatternStyle);
}
