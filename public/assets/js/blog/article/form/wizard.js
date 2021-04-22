$(document).ready(function () {

    var currentFieldSet, previous_fs;

    $("#goToVehicleDetails").on('click', function () {

        if (!validateVPassengersInfo()) {
            return false;
        }

        $(window).scrollTop(0);

        goToNextStep("#goToVehicleDetails");
    });


    $("#goToTripDetails").on('click', function () {

        // if (!validateVehicleInfo()) {
        //     return false;
        // }

        $(window).scrollTop(0);

        goToNextStep("#goToTripDetails");
    });


    $(".prev").click(function () {
        $(window).scrollTop(0);
        currentFieldSet = $(this).parent('fieldset');
        previous_fs = $(this).parent('fieldset').prev();
        $(currentFieldSet).removeClass("show");
        $(previous_fs).addClass("show");

    });
});

function goToNextStep(selector) {

    const currentFieldSet = $(selector).parent('fieldset');

    const nextFieldSet = $(selector).parent('fieldset').next();

    $(currentFieldSet).removeClass("show");

    $(nextFieldSet).addClass("show");

    $(nextFieldSet).find('input:visible:first').focus();
}

/* comprueba que todos los campos de los pasajeros sean validos */
function validateVPassengersInfo() {

    const validator = $("#form").validate();

    let areThePassengerFieldsValid = true;

    $('input[name*="surname"]').each(function () {

        let passenger_number = $(this).data("passenger_number");


        /* en caso que el falte el nombre en algun pasajero los datos de los pasajeros se marcan como invalidos */
        if (!validator.element("input[name='vehicle_passenger[" + passenger_number + "][name]']")) {
            areThePassengerFieldsValid = false;
        }

        /* en caso que el falte el apellido en algun pasajero los datos de los pasajeros se marcan como invalidos */
        if (!validator.element("input[name='vehicle_passenger[" + passenger_number + "][surname]']")) {
            areThePassengerFieldsValid = false;
        }

        /* en caso que el falte la temperatura de algun pasajero los datos de los pasajeros se marcan como invalidos */
        if (!validator.element("input[name='vehicle_passenger[" + passenger_number + "][identityCardTypeId]']")) {
            areThePassengerFieldsValid = false;
        }

        /* en caso que el falte el documento en algun pasajero los datos de los pasajeros se marcan como invalidos */
        if (!validator.element("input[name='vehicle_passenger[" + passenger_number + "][identityCard]']")) {
            areThePassengerFieldsValid = false;
        }

        /* en caso que el falte la temperatura de algun pasajero los datos de los pasajeros se marcan como invalidos */
        if (!validator.element("input[name='vehicle_passenger[" + passenger_number + "][temperatureControl]']")) {
            areThePassengerFieldsValid = false;
        }

    });

    /*si algun campo no es valido Desplazara la pantalla hacia el  */
    if (!areThePassengerFieldsValid) {
        scrollToFirstErrorMessage();
    }

    return areThePassengerFieldsValid;
}

/* comprueba que todos los campos del vehiculo sean validos */
function validateVehicleInfo() {

    const validator = $("#form").validate();

    let areVehicleFieldsValid = true;

    if (!validator.element("#vehicle_maker_name_id")) {
        areVehicleFieldsValid = false;
    }

    if (!validator.element("#model_of_vehicle_id")) {
        areVehicleFieldsValid = false;
    }

    if (!validator.element("#license_plate")) {
        areVehicleFieldsValid = false;
    }

    /* se usa este selector "input[name='vehicleBodyTypeId']" ya que el input es de tipo "option" */
    if (!validator.element("input[name='reason_for_trip_id']")) {
        areVehicleFieldsValid = false;
    }

    /*si algun campo no es valido Desplazara la pantalla hacia el  */
    if (!areVehicleFieldsValid) {
        scrollToFirstErrorMessage()
    }

    return areVehicleFieldsValid;
}

function scrollToFirstErrorMessage() {

    $('.error').each(function () {

        const error = this;

        if ($(error).html().length <= 1) {
            return true;
        }

        $('html, body').animate({
            scrollTop: ($(error).offset().top - 300)
        }, 500);

        return false

    });

}