$(document).ready(function () {
    var currentSelectizeValue;

    var taskListSelectize = $('#task_list_filter_course').selectize({
        onChange: function (value) {
            if (!value.length) return;

            $.ajax({
                url: Routing.generate('edukodas_task_list_get', {id: value}),
                dataType: 'html',
                method: 'GET',
                beforeSend: function () {
                    $('#full-task-list-container').hide();
                    $('#full-task-list-preloader').show();
                }
            }).done(function (data) {
                $('#full-task-list-container').html(data).show();
                $('#full-task-list-preloader').hide();
            }).fail(function () {
                Materialize.toast('Nepavyko atnaujinti užduočių sąrašo.', 4000);
                $('#full-task-list-container').show();
                $('#full-task-list-preloader').hide();
            })
        },
        onDropdownClose: function () {
            var selectize = taskListSelectize[0].selectize;
            if (selectize.getValue() === '') {
                selectize.setValue(currentSelectizeValue);
            }
        }
    });

    $(".selectize-control").on('click', function () {
        var selectize = taskListSelectize[0].selectize;
        currentSelectizeValue = selectize.getValue();
        selectize.clear(true);
    });
});
