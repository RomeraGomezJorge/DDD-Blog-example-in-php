$(document).ready(function () {
    const maxFile = 20; //Input fields increment limitation
    const addButton = $('.add_video_button'); //Add button selector
    let wrapper = $('#new_video_container'); //Input field wrapper
    var videoCounter = $('input[name*="[url]"]').length; //Initial field counter is 1


    //Once add button is clicked
    $(addButton).on('click', function (e) {

        e.preventDefault();

        //Check maximum number of input fields
        if (videoCounter == maxFile) {
            return;
        }

        const newVideo =
            '<div class="row video_wrapper">' +
            '    <div class="col-12 col-sm-5">\n' +
            '        <div class="form-group ">\n' +
            '            <label>Enlace Youtube</label>\n' +
            '            <input type="text" placeholder=" - Opcional -" name="youtube_video[' + videoCounter + '][url]"  maxlength="100" class="form-control" >\n' +
            '            <small class="errorLabelContainer form-text text-muted text-danger"></small>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '    <div class="col-12 col-sm-6">\n' +
            '        <div class="form-group ">\n' +
            '            <label>Descripción</label>\n' +
            '            <input type="text" placeholder=" - Opcional -" name="youtube_video[' + videoCounter + '][title]" value="" maxlength="100"  class="form-control">\n' +
            '            <small class="errorLabelContainer form-text text-muted text-danger"></small>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '    <div class="col-12 col-sm-1">\n' +
            '        <div class="form-group ">\n' +
            '            <label class="text-white d-none d-sm-block">Eliminar</label>\n' +
            '            <button type="button" class="remove_video_button btn-sm btn-danger btn-border">' +
            '               <i class="fas fa-times-circle fa-2x d-none d-sm-block"></i> ' +
            '               <span class="d-inline d-sm-none" style="font-size:0.9rem"><i class="fas fa-times-circle "></i> Eliminar</span> ' +
            '            </button>' +
            '        </div>' +
            '    </div>' +
            '</div>';

        $(wrapper).append(newVideo); //Add field html
        videoCounter++; //Increment field counter

    });

    //Once remove button is clicked
    $('body').on('click', '.remove_video_button', function (e) {
        e.preventDefault();
        $(this).closest('.video_wrapper').remove();//Remove field html
        videoCounter--; //Decrement field counter
    });

});
