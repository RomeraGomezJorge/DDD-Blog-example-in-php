$(document).ready(function () {
    const maxFile = 20; //Input fields increment limitation
    const addButton = $('.add_file_button'); //Add button selector
    let wrapper = $('#new_file_container'); //Input field wrapper
    var attachmentCounter = $('input[name*="[file]"]').length; //Initial field counter is 1
    var allowed_files = $('input[name="attachment[0][file]"]').attr('accept');

    //Once add button is clicked
    $(addButton).on('click', function (e) {

        e.preventDefault();

        //Check maximum number of input fields
        if (attachmentCounter == maxFile) {
            return;
        }

        const newFile =
            '<div class="row file_wrapper">' +
            '    <div class="col-12 col-sm-5">\n' +
            '        <div class="form-group ">\n' +
            '            <label>Archivo</label>\n' +
            '            <input type="file" placeholder=" - Opcional -" name="attachment[' + attachmentCounter + '][file]"  accept="' + allowed_files + '"  class="form-control-file form-control_file valid" aria-invalid="false">\n' +
            '            <small class="errorLabelContainer form-text text-muted text-danger"></small>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '    <div class="col-12 col-sm-6">\n' +
            '        <div class="form-group ">\n' +
            '            <label>Descripción</label>\n' +
            '            <input type="text" placeholder=" - Opcional -" name="attachment[' + attachmentCounter + '][title]" value="" maxlength="100" id="entry" class="form-control">\n' +
            '            <small class="errorLabelContainer form-text text-muted text-danger"></small>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '    <div class="col-12 col-sm-1">\n' +
            '        <div class="form-group ">\n' +
            '            <label class="text-white d-none d-sm-block">Eliminar</label>\n' +
            '            <button type="button" class="remove_file_button btn-sm btn-danger btn-border">' +
            '               <i class="fas fa-times-circle fa-2x d-none d-sm-block"></i> ' +
            '               <span class="d-inline d-sm-none" style="font-size:0.9rem"><i class="fas fa-times-circle "></i> Eliminar</span> ' +
            '            </button>' +
            '        </div>' +
            '    </div>' +
            '</div>';

        $(wrapper).append(newFile); //Add field html

        addRulesForTheNewAttachment(attachmentCounter);

        attachmentCounter++; //Increment field counter

    });

    //Once remove button is clicked
    $('body').on('click', '.remove_file_button', function (e) {
        e.preventDefault();
        $(this).closest('.file_wrapper').remove();//Remove field html
        attachmentCounter--; //Decrement field counter
    });


    function addRulesForTheNewAttachment(attachmentCounter) {


        $('input[name="attachment[' + attachmentCounter + '][file]"]').rules("add", {
            messages: {remote: "El archivo seleccionado no esta permito."},
            accept: allowed_files
        });

    }
});
