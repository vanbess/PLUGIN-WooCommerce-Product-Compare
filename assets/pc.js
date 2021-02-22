jQuery(document).ready(function ($) {

    // auto adjust product comparison legend container heigts
    var tit_height = $('.sbwc-pc-data-act.sbwc-pc-title').innerHeight();
    var price_height = $('.sbwc-pc-data-act.sbwc-pc-price').innerHeight();
    var rating_height = $('.sbwc-pc-data-act.sbwc-pc-rating').innerHeight();
    var img_height = $('.sbwc-pc-data-act.sbwc-pc-img').innerHeight();

    // set legend header height
    $('#sbwc-pc-legend-header').height(img_height);

    // set legend spacer height
    var total_height = parseFloat(tit_height) + parseFloat(price_height) + parseFloat(rating_height);
    $('#sbwc-pc-legend-spacer').height(total_height);

    // conditions to show/hide scrollers
    var display_width = $('div#sbwc-pc-review-outer-cont').outerWidth();
    var content_width = 0;

    $('.sbwc-pc-data').each(function () {
        content_width += $(this).outerWidth() + 10;
    });

    if (content_width > display_width) {
        $('span.sbwc-pc-scroll-left, span.sbwc-pc-scroll-right').show();
    }

    var clicked = 1

    // scroll left
    $('span.sbwc-pc-scroll-left').click(function (e) {
        e.preventDefault();
        $(this).addClass('dimmed');
        var width = $('.sbwc-pc-data').outerWidth() + 10;
        $('div#sbwc-pc-review-outer-cont').scrollLeft(-width);
        clicked = 1;
    });

    // scroll right
    $('span.sbwc-pc-scroll-right').click(function (e) {
        e.preventDefault();
        $('span.sbwc-pc-scroll-left').removeClass('dimmed');
        var width = $('.sbwc-pc-data').outerWidth() + 10;
        $('div#sbwc-pc-review-outer-cont').scrollLeft(width * clicked);
        clicked++
    });

    

});