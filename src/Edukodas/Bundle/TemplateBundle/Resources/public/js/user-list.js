$(document).ready(function() {
    $('#user_list_filter_team').selectize({
        plugins: {
            'restore_on_backspace': {},
            'no_results': { message: 'Nepavyko nieko rasti' }
        }
    });
    $('#user_list_filter_class').selectize({
        plugins: {
            'restore_on_backspace': {},
            'no_results': { message: 'Nepavyko nieko rasti' }
        }
    });

    var selectTeam = $("#user_list_filter_team");
    var selectClass = $("#user_list_filter_class");

    selectClass.on("change", function () {
        $('[name=user_list_filter]').submit();
    });
});
