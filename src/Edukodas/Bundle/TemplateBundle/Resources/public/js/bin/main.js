$(document).ready(function () {
    $(".button-collapse").sideNav({
        closeOnClick: true
    });

    $('.collapsible').collapsible();

    var width = $(window).width();

    $(window).resize(function() {
        if ($(window).width()!=width) {
            width = $(window).width();
            $('.button-collapse').sideNav('hide');
        }
    });
});
