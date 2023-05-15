$ = jQuery;
$(document).ready(function () {
    "use strict";

    $('body:not( .woocommerce ) select').selbel();

    $("#menuOpen").click(function (e) {
        $(this).toggleClass("opened");
    });

    // ===IMG TO SVG===
    $('img.img_svg').each(function () {
        var $img = jQuery(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');

        $.get(imgURL, function (data) {
            var $svg = jQuery(data).find('svg');

            if (typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }


            if (typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', imgClass + ' replaced-svg');
            }

            $svg = $svg.removeAttr('xmlns:a');

            if (!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
            }

            $svg = $svg.attr('preserveAspectRatio', 'xMinYMin meet');

            $img.replaceWith($svg);

        }, 'xml');

    });
    // ===END IMG TO SVG===

});
// ===END READY===

$(window).on('load', function () {
    // swiper_example
    // var swiper_example = new Swiper('.swiper_example', {
    //     loop: true,
    //     speed: 1000,
    //     autoplay: {
    //         delay: 4000
    //     },
    //     pagination: {
    //         el: '.swiper-pagination',
    //         dynamicBullets: true,
    //         clickable: true
    //     },
    //     navigation: {
    //         prevEl: '.swiper-prev',
    //         nextEl: '.swiper-next'
    //     },
    //     on: {
    //         init: function () {
    //             $('.swiper_example').addClass('slider_loaded');
    //         }
    //     },
    //     breakpoints: {
    //         640: {
    //             slidesPerView: 2,
    //             spaceBetween: 20
    //         }
    //     }
    // });
});
// ===END LOAD===
