$ = jQuery;
$(document).ready(function () {
    "use strict";

    $('body:not( .woocommerce ) select').selbel();

    $("#menuOpen").click(function (e) {
        $(this).toggleClass("opened");
    });

    if ( $(window).width() < 1025 ) {
        $('#mainMenu a').click(function (e) {
            e.stopPropagation();
        });
        $('#mainMenu .menu-item-has-children').click(function () {
            $(this).toggleClass('active').children('.sub-menu').slideToggle();
        });
    }

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
    headerSize();
    switchHeader();

});
// ===END READY===

$(window).on('load', function () {
    // swiper_example
    // const swiper = new Swiper('.swiper', {
    //     // Optional parameters
    //     loop: true,
    //
    //     // If we need pagination
    //     pagination: {
    //         el: '.swiper-pagination',
    //     },
    //
    //     // Navigation arrows
    //     navigation: {
    //         nextEl: '.swiper-button-next',
    //         prevEl: '.swiper-button-prev',
    //     },
    // });

});
// ===END LOAD===

$(window).scroll(function () {
    switchHeader();
});

$(window).resize(function () {
    headerSize();
    switchHeader();
});

function headerSize() {
    const $header = $('.site-header');

    let offset = 0;
    if ( $header.length ) {
        offset = $header.outerHeight(true);
        document.body.style.setProperty('--headerOffset', offset+'px');
    }
}

function switchHeader() {
    const $header = $('.site-header');
    if ( $header.length ) {
        const offset = $(window).scrollTop();
        if ( offset > 5 ) {
            $header.addClass('active');
        } else {
            $header.removeClass('active');
        }
    }
}
