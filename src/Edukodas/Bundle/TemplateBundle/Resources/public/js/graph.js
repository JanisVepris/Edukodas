$(document).ready(function () {
    $('form[name=graph] select').material_select();

    var submitForm = function () {
        $('[name=graph]').submit();
    };

    $('form[name=graph] select').on('change', submitForm);
});

function drawTeamPieChart(data, options) {
    var ctx = $('#team-pie-chart');

    new Chart(ctx,{
        type: 'pie',
        data: data,
        options: options
    });
}

function drawTeamBarChart(data, options) {
    var ctx = $('#team-bar-chart');

    new Chart(ctx,{
        type: 'bar',
        data: data,
        options: options
    });
}

function drawTeamLineChart(data, options) {
    var ctx = $('#team-line-chart');

    new Chart(ctx,{
        type: 'line',
        data: data,
        options: options
    });
}
