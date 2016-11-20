$(document).ready(function(){
    function editTaskButtonPressed(){
        var url = Routing.generate('edukodas_tasks_edit', {taskId : 1});
        // $.post(url, null, function (data) {
        $('#edit-task-modal > .modal-content').html(url);
        // });
    }

    $('#edit-task-modal').modal({
            ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
                editTaskButtonPressed();
            },
            complete: function() { } // Callback for Modal close
        }
    );
});
