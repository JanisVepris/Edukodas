$(document).ready(function() {

    function editPointHistoryButton(trigger) {
        var pointHistoryId = trigger.data('points-id');
        var url = Routing.generate('edukodas_points_edit', {pointHistoryId : pointHistoryId});

        $.ajax({
            url:   url,
            type: 'POST',
            beforeSend: function() {
                $('#edit-points-modal > .modal-content > .form-content').html('');
                $('#edit-points-form-preloader').removeClass('hide');
            },
            success: function(data) {
                if (data) {
                    $('#edit-points-form-preloader').addClass('hide');
                    $('#edit-points-modal > .modal-content > .form-content').html(data);
                    editPointHistoryForm(url, pointHistoryId);
                }
            },
            error: function() {
                Materialize.toast('Nepavyko užkrauti formos', 4000);
                $('#edit-points-modal').modal('close');
                $('#edit-points-form-preloader').addClass('hide');
            }
        });
    }

    function editPointHistoryForm (url, pointHistoryId) {
        $('select').material_select();

        $('#edit-points-form').ajaxForm({
            url: url,
            type: 'POST',
            beforeSubmit: function() {
                $('#edit-points-save').prop('disabled', true).hide();
                $('#points-save-preloader').removeClass('hide');
            },
            success: function(data) {
                if (data) {
                    $('#history-points-' + pointHistoryId).replaceWith(data);
                    $('#edit-points-modal').modal('close');
                    $('#points-save-preloader').addClass('hide');
                }
            },
            error: function(data) {
                $('#points-save-preloader').addClass('hide');
                if (data['status'] == 400) {
                    $('#edit-points-modal > .modal-content > .form-content').html(data['responseText']);
                    editPointHistoryForm(url);
                } else {
                    Materialize.toast('Klaida išsaugant taškus', 4000);
                    $('#edit-points-save').prop('disabled', false).show();
                }
            }
        });
    }

    $('#edit-points-modal').modal({
            ready: function(modal, trigger) {
                editPointHistoryButton(trigger);
            },
            complete: function() {
                $('#edit-points-modal > .modal-content > .form-content').html('');
            }
        }
    );

    function toggleAddPointsForm() {
        if ($('.task-list-container').hasClass('hide')) {
            $('.task-list-container').removeClass('hide');
            $('#add-points-form-container').addClass('hide');
        } else {
            $('.task-list-container').addClass('hide');
            $('#add-points-form-container').removeClass('hide');
        }
    }

    function managePointsForm() {
        $('select').material_select();

        var url = Routing.generate('edukodas_points_add');

        $('#add-points-form').ajaxForm({
            url: url,
            type: 'POST',
            beforeSubmit: function() {
                $('#add-points-submit').prop('disabled', true).hide();
                $('#points-submit-preloader').removeClass('hide');
            },
            success: function(data) {
                $('#points-history-list').prepend(data);
                $('#manage-points-modal').modal('close');
                $('#add-points-submit').prop('disabled', false).show();
                $('#points-submit-preloader').addClass('hide');
                toggleAddPointsForm();
                $('#edukodas_bundle_statisticsbundle_pointhistory_comment').val('');
            },
            error: function(data) {
                Materialize.toast('Klaida išsaugant taškus', 4000);
                $('#add-points-submit').prop('disabled', false).show();
                $('#points-submit-preloader').addClass('hide');
            }
        });
    }

    $('#add-points-form-back').click(function (e) {
        e.preventDefault();
        toggleAddPointsForm();
    });

    $('.points-task-select-button').click(function (e) {
        e.preventDefault();

        var taskId = $(this).data('taskId');
        var amount = parseInt($(this).data('taskAmount'));
        toggleAddPointsForm();

        $('#edukodas_bundle_statisticsbundle_pointhistory_task').val(taskId);
        $('#edukodas_bundle_statisticsbundle_pointhistory_amount').val(amount);
    });

    $('#manage-points-modal').modal({
            ready: function(modal, trigger) {
                managePointsForm();
            },
            complete: function() {
                // $('#manage-task-modal > .modal-content > .form-content').html('');
            }
        }
    );


//
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
