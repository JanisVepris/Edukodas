$(document).ready(function() {
    var timer;
    var xhr;

    function hideSearchResults() {
        $('.search-preloader-container').addClass('hide')
        $('.autocomplete-content').addClass('hide');
    }

    var searchUsers = function () {
        if (typeof xhr != 'undefined') {
            xhr.abort();
        }

        searchString = $(this).val();

        if (timer) {
            clearTimeout(timer);
        }

        if (searchString.length > 2) {
            timer = setTimeout(function () {
                xhr = $.ajax({
                    url: Routing.generate('edukodas_search_student', {searchString: searchString}),
                    type: 'GET',
                    beforeSend: function () {
                        $('.search-preloader-container').removeClass('hide');
                        $('.autocomplete-content').empty();
                        $('.autocomplete-content').addClass('hide');
                    },
                    success: function (data) {
                        $('.search-preloader-container').addClass('hide');
                        $('.autocomplete-content').html(data);
                        searchResultLinks();
                        $('.autocomplete-content').removeClass('hide');
                    },
                    error: function () {
                    }
                });
            }, 250);
        } else {
            hideSearchResults();
        }
    }

    $('#search').on('input', searchUsers);
    $('#search-mobile').on('input', searchUsers);

    function searchResultLinks() {
        $('.search-result > a').on('click', function () {
            $('#search').val($(this).text().trim());
            $('#search').trigger('change');
            hideSearchResults();
            window.location = $(this).attr('href');
        });
    }

    $('.search').on('submit', function (e) {
        e.preventDefault();
        $('.search-result > a:first').click();
    });

    $('.close-search').on('click', function () {
       hideSearchResults();
    });

    $(document).mouseup(function (e)
    {
        var container = $('.search');

        if (!container.is(e.target)
            && container.has(e.target).length === 0)
        {
            hideSearchResults();
        }
    });
});
