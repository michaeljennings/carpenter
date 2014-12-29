(function ( $ ) {

    $.fn.carpenterJs = function()
    {
        var $container = $(this);
        var $form = $(this).find('form');
        var $table = $(this).find('table');
        var $row = $table.children('tbody').children('tr:not(a,button)');

        $container.on('click', 'a', function(e)
        {
            if ($(this).attr('confirmed')) {
                e.preventDefault();

                var url = $(this).attr('href');

                if (confirm($(this).attr('confirmed'))) {
                    document.location = url;
                }
            }
        });
    };

})( jQuery );