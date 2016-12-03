$(document).ready(function () {
    var currentSelectizeValue;

    var taskListSelectize = $('#task_list_filter_course').selectize({
        plugins: {
            'no_results': { message: 'Nepavyko nieko rasti' }
        },
        onChange: function (value) {
            if (!value.length || value == currentSelectizeValue) {
                return;
            }

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
        onBlur: function () {
            if (this.getValue() === '') {
                this.setValue(currentSelectizeValue);
            }
        },
        onFocus: function () {
            currentSelectizeValue = this.getValue();
            this.clear(true);
        }
    });
});
