$(document).ready(function(){
    function editTaskButtonPressed(trigger){
        var taskId = trigger.attr('id').split('-')[2];
        var url = Routing.generate('edukodas_tasks_edit', {taskId : taskId});

        $.ajax({
            url:   url,
            type: 'POST',
            beforeSend: function(){
                $('#edit-task-modal > .modal-content').html('loading...');
            },
            success: function(data){
                if (data) {
                    $('#edit-task-modal > .modal-content').html(data);
                }
            }
        });

    }

    $('#edit-task-modal').modal({
            ready: function(modal, trigger) {
                editTaskButtonPressed(trigger);
            },
            complete: function() { $('#edit-task-modal > .modal-content').html(''); }
        }
    );
});
