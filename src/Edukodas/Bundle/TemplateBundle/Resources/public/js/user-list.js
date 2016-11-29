$(document).ready(function() {
    $('select').material_select();

    var selectTeam = $("#select-team");
    var selectClass = $("#select-class");
    var userList = $("#user-list");

    var updateUserList = function() {
        var url = Routing.generate('edukodas_user_list_update');

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                teamId: selectTeam.val(),
                classId: selectClass.val()
            },
            beforeSend: function() {
                //
            },
            success: function(data) {
                if (data) {
                    userList.html(data);
                }
            },
            error: function() {
                Materialize.toast('Nepavyko atnaujinti sąrašo', 4000);
            }
        });
    };

    selectTeam.on("change", updateUserList);
    selectClass.on("change", updateUserList);
});
