$(document).ready(function(){
    $('#manage-task-modal').modal({
            ready: function(modal, trigger) {
                manageTaskButton(trigger);
            },
            complete: function() { $('#manage-task-modal > .modal-content').html(''); }
        }
    );

    function manageTaskButton(trigger){
        var taskAction = trigger.data('task-action');
        var taskId = trigger.data('task-id');
        var url = Routing.generate(taskAction, {taskId : taskId});

        $.ajax({
            url:   url,
            type: 'POST',
            beforeSend: function(){
                $('#manage-task-modal > .modal-content').html('<div class="center-align">' +
                    '<div class="preloader-wrapper big active">' +
                        '<div class="spinner-layer spinner-blue-only">' +
                            '<div class="circle-clipper left">' +
                                '<div class="circle"></div>' +
                            '</div><div class="gap-patch">' +
                                '<div class="circle"></div>' +
                            '</div><div class="circle-clipper right">' +
                                '<div class="circle"></div>' +
                            '</div>' +
                        '</div>' +
                    '</div></div>');
            },
            success: function(data){
                if (data) {
                    $('#manage-task-modal > .modal-content').html(data);
                    $('select').material_select();
                    $('#manage-task-form').ajaxForm({
                        url: url,
                        type: 'POST',
                        beforeSubmit: function(){
                            $('#manage-task-form > button').replaceWith('<div class="preloader-wrapper small active">' +
                                '<div class="spinner-layer spinner-green-only">' +
                                '<div class="circle-clipper left">' +
                                '<div class="circle"></div>' +
                                '</div><div class="gap-patch">' +
                                '<div class="circle"></div>' +
                                '</div><div class="circle-clipper right">' +
                                '<div class="circle"></div>' +
                                '</div>' +
                                '</div>' +
                                '</div>');
                        },
                        success: function(){
                            refreshTaskList();
                        }
                    });
                }
            }
        });
    }

    function deleteTaskButton() {
        var taskId = $(this).data('task-id');
        var url = Routing.generate('edukodas_tasks_delete', {taskId : taskId});

        $.ajax({
            url: url,
            type: 'POST',
            beforeSend: function () {
                $('.delete-task*[data-task-id="' + taskId + '"]')
                    .replaceWith('<div class="preloader-wrapper small active">' +
                        '<div class="spinner-layer spinner-red-only">' +
                        '<div class="circle-clipper left">' +
                        '<div class="circle"></div>' +
                        '</div><div class="gap-patch">' +
                        '<div class="circle"></div>' +
                        '</div><div class="circle-clipper right">' +
                        '<div class="circle"></div>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
            },
            success: function () {
                refreshTaskList();
            }
        });
    }

    $('.delete-task').on('click', deleteTaskButton);

    function refreshTaskList(){
        var url = Routing.generate('edukodas_tasks_list');

        $.ajax({
            url: url,
            type: 'POST',
            success: function(data) {
                $('#tasks-list').html(data);
                $('.delete-task').on('click', deleteTaskButton);
                $('#manage-task-modal').modal('close');
            }
        });
    }
});
