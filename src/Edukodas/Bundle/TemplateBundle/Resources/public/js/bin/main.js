$(document).ready(function () {
    $(".button-collapse").sideNav();

    $('input.autocomplete').autocomplete({
        data: {
            "Apple": null,
            "Microsoft": null,
            "Google": 'http://placehold.it/250x250'
        }
    });
})
