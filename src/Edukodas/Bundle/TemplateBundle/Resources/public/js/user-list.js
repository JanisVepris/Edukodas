$(document).ready(function() {
    $('select').material_select();

    var selectTeam = $("#select-team");
    var selectClass = $("#select-class");

    var updateUserList = function() {
        $.ajax({
            url: Routing.generate('edukodas_user_list_update'),
            data: {
                team: selectTeam.val(),
                class: selectClass.val()
            },
            type: 'POST',
            beforeSend: function() {
                // $('.delete-task*[data-task-id="' + taskId + '"]').prop('disabled',true).hide();
                // $('#delete-task-preload-' + taskId).removeClass('hide');
            },
            success: function(data) {
                if (data) {
                    console.log(data);
                }
            },
            error: function() {
                Materialize.toast('Nepavyko atnaujinti sąrašo', 4000);
                // $('#delete-task-preload-' + taskId).addClass('hide');
                // $('.delete-task*[data-task-id="' + taskId + '"]').prop('disabled',false).show();
            }
        });
    }

    selectTeam.on("change", updateUserList);
});
