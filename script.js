jQuery(function() {
    'use strict';

    var duration = 3000;
    if (typeof JSINFO['plugin']['newsticker']['duration'] !== 'undefined'){
        duration = JSINFO['plugin']['newsticker']['duration'] * 1000;
    }

    function ticker() {
        jQuery('ul#tickerlist li:first').slideUp(function () {
            jQuery(this).appendTo(jQuery('ul#tickerlist')).slideDown();
        });
    }

    function unticker() {
        jQuery('ul#tickerlist li:last').slideUp(function () {
            jQuery(this).prependTo(jQuery('ul#tickerlist')).slideDown();
        });
    }

    setInterval(function () {
        if (jQuery('#plugin-newsticker').hasClass('ticking')) {
            ticker();
        }
    }, duration);

    jQuery('#plugin-newsticker').hover(function () {
        jQuery(this).removeClass('ticking');
    },function () {
        jQuery(this).addClass('ticking');
    });

    jQuery('button#plugin_newsticker_unticker').click(function () {
        unticker();
    });

    jQuery('button#plugin_newsticker_ticker').click(function () {
        ticker();
    });

});
