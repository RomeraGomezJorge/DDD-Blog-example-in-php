$(document).ready(function () {

    const filter_field = $('select[name="filters[0][field]"]');
    const roles = $('#valuesOfRoles');
    const valuesNotEqualToRole = $('#valuesNotEqualToRole');
    filter_field.change();

    filter_field.on('change', function () {

        if (filter_field.val() === 'role') {

            roles.attr('name', 'filters[0][value]');
            roles.removeClass('d-none');

            valuesNotEqualToRole.addClass('d-none');
            valuesNotEqualToRole.removeAttr('name');
            return false;
        }

        valuesNotEqualToRole.removeClass('d-none');
        valuesNotEqualToRole.attr('name', 'filters[0][value]');

        roles.addClass('d-none');
        roles.removeAttr('name');

        return false;
    })
});