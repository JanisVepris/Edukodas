$(document).ready(function () {
    $('[name=graph] select').material_select();

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
