/*global ajaxurl*/
;(function( $ ) {
    $(document).ready(function() {
        // Switch to default dashboard.
        $('.switch-dashboard').on('click', function(e) {
            e.preventDefault();

            var adminUrl = $(this).data('admin-url');

            $.ajax(
                $(this).attr('href')
            )
                .success(function () {
                    window.location.href = adminUrl;
                })

        })

        // Switch to default dashboard.
        $('.sg-restart-wizard').on('click', function(e) {
            e.preventDefault();

            var adminUrl = $(this).data('admin-url');

            $.ajax(
                $(this).attr('href')
            )
                .success(function () {
                    window.location.href = adminUrl;
                })
        })
    })
})( jQuery )
