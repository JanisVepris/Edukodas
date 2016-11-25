$(document).ready(function() {
    function manageTaskButton(trigger) {
        var taskAction = trigger.data('task-action');
        var taskId = trigger.data('task-id');
        var url = Routing.generate(taskAction, {taskId : taskId});

        $.ajax({
            url:   url,
            type: 'POST',
            beforeSend: function() {
                $('#manage-task-modal > .modal-content > .form-content').html('');
                $('#task-form-preload').removeClass('hide');
            },
            success: function(data) {
                if (data) {
                    $('#task-form-preload').addClass('hide');
                    $('#manage-task-modal > .modal-content > .form-content').html(data);
                    manageTaskForm(url);
                }
            },
            error: function() {
                Materialize.toast('Nepavyko užkrauti formos', 4000);
                $('#manage-task-modal').modal('close');
                $('#task-form-preload').addClass('hide');
            }
        });
    }

    function deleteTaskButton() {
        var taskId = $(this).data('task-id');
        var url = Routing.generate('edukodas_tasks_delete', {taskId : taskId});

        $.ajax({
            url: url,
            type: 'POST',
            beforeSend: function() {
                $('.delete-task*[data-task-id="' + taskId + '"]').prop('disabled',true).hide();
                $('#delete-task-preload-' + taskId).removeClass('hide');
            },
            success: function(data) {
                if (data) {
                    updateTasksList(data);
                }
            },
            error: function() {
                Materialize.toast('Nepavyko ištrinti užduoties', 4000);
                $('#delete-task-preload-' + taskId).addClass('hide');
                $('.delete-task*[data-task-id="' + taskId + '"]').prop('disabled',false).show();
            }
        });
    }

    function manageTaskForm(url) {
        $('select').material_select();

        $('#manage-task-form').ajaxForm({
            url: url,
            type: 'POST',
            beforeSubmit: function() {
                $('#manage-task-form > button').prop('disabled', true).hide();
                $('#submit-preloader').removeClass('hide');
            },
            success: function(data) {
                if (data) {
                    updateTasksList(data);
                    $('#manage-task-modal').modal('close');
                    $('#submit-preloader').addClass('hide');
                }
            },
            error: function(data) {
                $('#submit-preloader').addClass('hide');
                if (data['status'] == 400) {
                    $('#manage-task-modal > .modal-content > .form-content').html(data['responseText']);
                    manageTaskForm(url);
                } else {
                    Materialize.toast('Klaida išsaugant užduotį', 4000);
                    $('#manage-task-form > button').prop('disabled', false).show();
                }
            }
        });
    }

    function updateTasksList(data) {
        $('#tasks-list').html(data);
        $('.delete-task').on('click', deleteTaskButton);
    }

    $('#manage-task-modal').modal({
            ready: function(modal, trigger) {
                manageTaskButton(trigger);
            },
            complete: function() { $('#manage-task-modal > .modal-content > .form-content').html(''); }
        }
    );

    $('.delete-task').on('click', deleteTaskButton);
});
