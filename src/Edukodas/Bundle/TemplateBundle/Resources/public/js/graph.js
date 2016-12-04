$(document).ready(function () {
    $('select').material_select();

    var submitForm = function () {
        $('[name=graph]').submit();
    };

    $('#graph_timespan').on('change', submitForm);
});

function drawTeamPieChart(data, options) {
    var ctx = $('#team-pie-chart');

    var myPieChart = new Chart(ctx,{
        type: 'pie',
        data: data,
        options: options
    });
}

