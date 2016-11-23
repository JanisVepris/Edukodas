$(document).ready(function(){
    function addTaskButtonPressed(trigger){
        var url = Routing.generate('edukodas_tasks_add');

        $.ajax({
            url:   url,
            type: 'POST',
            beforeSend: function(){
                $('#add-task-modal > .modal-content').html('<div class="center-align">' +
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
                    $('#add-task-modal > .modal-content').html(data);
                    $('select').material_select();
                }
            }
        });
    }

    $('#add-task-modal').modal({
            ready: function(modal, trigger) {
                addTaskButtonPressed(trigger);
            },
            complete: function() { $('#add-task-modal > .modal-content').html(''); }
        }
    );

    function editTaskButtonPressed(trigger){
        var taskId = trigger.data('task-id');
        var url = Routing.generate('edukodas_tasks_edit', {taskId : taskId});

        $.ajax({
            url:   url,
            type: 'POST',
            beforeSend: function(){
                $('#edit-task-modal > .modal-content').html('<div class="center-align">' +
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
                    $('#edit-task-modal > .modal-content').html(data);
                    $('select').material_select();
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

    $('.delete-task').click(function () {
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
            success: function (data) {
                $('#task-' + data).remove();
            }
        });
    });
});
