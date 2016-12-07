$(document).ready(function () {
    $(".button-collapse").sideNav({
        closeOnClick: true
    });

    $('.collapsible').collapsible();

    $( window ).resize(function() {
        $('.button-collapse').sideNav('hide');
    });
});
