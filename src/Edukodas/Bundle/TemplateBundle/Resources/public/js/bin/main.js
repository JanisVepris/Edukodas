$(document).ready(function () {
    $(".button-collapse").sideNav({
        closeOnClick: true
    });

    $('.collapsible').collapsible();

    $( window ).resize(function() {
        if (window.innerWidth > 992){
            $('.button-collapse').sideNav('hide');
        }
    });
});
