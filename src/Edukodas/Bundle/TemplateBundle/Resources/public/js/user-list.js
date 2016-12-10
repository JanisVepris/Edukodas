$(document).ready(function() {
    $('#user_list_filter_team').selectize();
    $('#user_list_filter_class').selectize();

    var selectTeam = $("#user_list_filter_team");
    var selectClass = $("#user_list_filter_class");

    selectTeam.on("change", function () {
        $('[name=user_list_filter]').submit();
    });
    selectClass.on("change", function () {
        $('[name=user_list_filter]').submit();
    });
});
