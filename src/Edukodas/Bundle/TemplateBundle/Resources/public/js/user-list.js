$(document).ready(function() {
    $('#user_list_filter_team').material_select();
    $('#user_list_filter_class').material_select();

    var selectTeam = $("#user_list_filter_team");
    var selectClass = $("#user_list_filter_class");

    selectTeam.on("change", function () {
        $('[name=user_list_filter]').submit();
    });
    selectClass.on("change", function () {
        $('[name=user_list_filter]').submit();
    });
});
