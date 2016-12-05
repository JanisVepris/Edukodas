$(document).ready(function () {
    $('select').material_select();

    var submitForm = function () {
        $('[name=graph]').submit();
    };

    $('#graph_timespan').on('change', submitForm);
});

function drawTeamPieChart(data, options) {
    var ctx = $('#team-pie-chart');

    new Chart(ctx,{
        type: 'pie',
        data: data,
        options: options
    });
}

function drawTeamLineChart(data, options) {
    var ctx = $('#team-line-chart');

    // data =  {
    //     labels: ["January", "February", "March", "April", "May", "June", "July"],
    //     datasets: [
    //         {
    //             label: "My First dataseta",
    //             fill: false,
    //             lineTension: 0.1,
    //             borderColor: "rgba(75,100,192,0.4)",
    //             data: [65, 59, 80, 81, 56, 55, 40],
    //         },
    //         {
    //             label: "My First dataseta",
    //             fill: false,
    //             lineTension: 0.1,
    //             borderColor: "rgba(75,100,192,0.4)",
    //             data: [20, 40, 60, 60, 30, 10, 80],
    //         }
    //     ]
    // };

    new Chart(ctx,{
        type: 'line',
        data: data,
        options: options
    });
}
