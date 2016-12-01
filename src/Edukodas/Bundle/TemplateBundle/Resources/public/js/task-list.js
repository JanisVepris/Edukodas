$(document).ready(function () {
    $('#task_list_filter_course').selectize({
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
                Materialize.toast('Nepavyko atnaujinti užduočių sarašą.', 4000);
                $('#full-task-list-container').show();
                $('#full-task-list-preloader').hide();
            })
        }
    });
});
