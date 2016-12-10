$(document).ready(function () {
    $('#graph_timespan').material_select();
    $('#graph_team').selectize();
    $('#graph_class').selectize();

    $('[name=graph] select').on('change', function () {
        $('[name=graph]').submit();
    });
});

function drawChart(position, type, data, options) {
    position = $(position);

    new Chart(position, {
        type: type,
        data: data,
        options: options
    });
}
