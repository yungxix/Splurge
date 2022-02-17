(function ($) {
    $(function () {
        var target = $('#mobile-menu');
        $('#mobile-menu-trigger').on('click', function () {
            $(this).find('svg').each(function () {
                var svgIcon = $(this);
                if (svgIcon.hasClass('block')) {
                    svgIcon.removeClass('block');
                    
                    svgIcon.addClass('hidden');
                } else {
                    svgIcon.addClass('block');
                    svgIcon.removeClass('hidden');
                }
                
            });

            if (target.hasClass('hidden')) {
                target.removeClass('hidden');
                target.addClass('block');
            } else {
                target.addClass('hidden');
                target.removeClass('block');
            }
            target.slideToggle();
           
        })
    });

})($);