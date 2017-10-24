jQuery(document).ready(function () {

    if(jQuery(window).width() > 991) {
        heightTitle('.qs-control-tab .qs-title-tab-control');
    } else {
        heightTitle('.control-tabs .qs-title-tab-control');
    }

    if(jQuery(window).width() > 1599) {
        if(jQuery('.qs-desgin-wrapper-custom .product-name').length) {
            var wpname = jQuery('.qs-desgin-wrapper-custom .product-name').width();
            var wswiper = jQuery('.qs-desgin-wrapper-custom .swiper-pagination').width();

            jQuery('.qs-desgin-wrapper-custom .product-name').css('left', -((wpname+wswiper)/2) + 'px');
        }
    }

    jQuery(document).on('click', '.qs-list-content1 ul li a', function () {
        jQuery(this).next().slideToggle("fast");
        jQuery('.qs-list-content1 ul li a').not(this).removeClass('active-color');
        jQuery(this).toggleClass('active-color');

        jQuery('.qs-list-content1 ul li a').not(this).next().slideUp("fast");

        jQuery('.qs-list-content1 ul li a').not(this).find('i').addClass('fa-angle-right').removeClass('fa-angle-down');
        jQuery(this).find('i').toggleClass('fa-angle-right').toggleClass('fa-angle-down');
        return false;
    });

    jQuery(document).on('click', '.qs-list-content2 ul li a', function () {
        jQuery(this).next().slideToggle("fast");
        jQuery('.qs-list-content2 ul li a').not(this).removeClass('active-color');
        jQuery(this).toggleClass('active-color');
        jQuery('.qs-list-content2 ul li a').not(this).next().slideUp("fast");
        jQuery('.qs-list-content2 ul li a').not(this).find('i').addClass('fa-angle-right').removeClass('fa-angle-down');
        jQuery(this).find('i').toggleClass('fa-angle-right').toggleClass('fa-angle-down');
        return false;
    });

    /*if (jQuery('.qs-design-custom').length && jQuery('.qs-control-custom').length) {
        if (jQuery(window).width() > 1199 ) {
            jQuery('.qs-control-custom').css('height', jQuery('.qs-design-custom').height() + 'px');
        } else if(jQuery(window).width() < 992) {
            jQuery('.qs-control-custom').css('height', jQuery('.qs-design-custom').height() - 30 + 'px');
        } else {
            jQuery('.qs-control-custom').removeAttr('style');
        }
    }*/

    jQuery(document).on('click', '.qs-button-inspire, .qs-button-inspire-back', function() {
        jQuery('.qs-inspire-wrapper').toggleClass('inspire-show');
    });

    if (jQuery('.qs-content-see-detail').length) {
        if (jQuery(window).width() > 1199) {
            jQuery('.qs-content-see-detail').css('height', (jQuery('.qs-control-custom').height() - jQuery('.qs-control-bottom').height() - jQuery('.qs-header-see-detail').height() - 1) + 'px');
        } else {
            jQuery('.qs-content-see-detail').css('height', (jQuery('.qs-control-custom').height() - jQuery('.qs-header-see-detail').height() - 1) + 'px');
        }
    }

    if (jQuery('#qs-also-like-slider').length) {
        jQuery('#qs-also-like-slider').owlCarousel({
            nav: true,
            items: 4,
            loop: true,
            margin: 30,
            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                992: {
                    items: 4
                }
            }
        });
    }

    jQuery(document).on('click', 'input[name="choose-size"]', function () {
        if (jQuery(this).val() == 'measurement' || jQuery(this).val() == 'bodyscan') {
            var noteShow = jQuery(this).parent().next();

            jQuery('.qs-notice-size').hide();
            jQuery(noteShow).fadeIn();
        } else {
            jQuery('.qs-notice-size').hide();
        }
    });

    //Inspire Me
    if (jQuery('#owl-inspire').length) {
        jQuery('#owl-inspire').owlCarousel({
            nav: true,
            loop: true,
            margin: 30,
            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                992: {
                    items: 4
                }
            }
        });
    }

    if (jQuery('#owl-inspire-current').length) {
        jQuery('#owl-inspire-current').owlCarousel({
            nav: true,
            loop: true,
            margin: 30,
            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                992: {
                    items: 4
                }
            }
        });
    }

    //Tab owl slider
    // var tabproduct = jQuery('#owl-product-tab');
    //
    // if(jQuery('#owl-product-tab').length) {
    //     tabproduct.owlCarousel({
    //         items: 1,
    //         nav: true
    //     });
    // }
    //
    // var panelchoose = jQuery('#owl-panel-choose');
    //
    // if(jQuery('#owl-panel-choose').length) {
    //     panelchoose.owlCarousel({
    //         nav: true,
    //         responsive: {
    //             0: {
    //                 items: 3
    //             },
    //             480: {
    //                 items: 4
    //             },
    //             768: {
    //                items: 5
    //             },
    //             1200: {
    //                 items: 4
    //             },
    //             1600: {
    //                 items: 5
    //             }
    //         }
    //     });
    // }

    jQuery(window).resize(function () {
       /* if (jQuery('.qs-design-custom').length && jQuery('.qs-control-custom').length) {
            setTimeout(function () {
                if (jQuery(window).width() > 1199 ) {
                    jQuery('.qs-control-custom').css('height', jQuery('.qs-design-custom').height() + 'px');
                } else if(jQuery(window).width() < 992) {
                    jQuery('.qs-control-custom').css('height', jQuery('.qs-design-custom').height() - 1 + 'px');
                } else {
                    jQuery('.qs-control-custom').removeAttr('style');
                }
            }, 500);
        }

        if (jQuery('.qs-content-see-detail').length) {
            setTimeout(function () {
                if (jQuery(window).width() > 1199) {
                    jQuery('.qs-content-see-detail').css('height', (jQuery('.qs-control-custom').height() - jQuery('.qs-control-bottom').height() - jQuery('.qs-header-see-detail').height() - 1) + 'px');
                } else {
                    jQuery('.qs-content-see-detail').css('height', (jQuery('.qs-control-custom').height() - jQuery('.qs-header-see-detail').height() - 1) + 'px');
                }
            }, 500);
        }*/

        // if(jQuery('.qs-control-custom').length) {
        //     setTimeout(function () {
        //         if (jQuery(window).width() > 991) {
        //             jQuery('.qs-control-custom').slideDown();
        //             var carouselpanel = panelchoose.data('owlCarousel');
        //             carouselpanel._width = panelchoose.width();
        //             carouselpanel.invalidate('width');
        //             carouselpanel.refresh();
        //
        //             var carouselproduct = tabproduct.data('owlCarousel');
        //             carouselproduct._width = tabproduct.width();
        //             carouselproduct.invalidate('width');
        //             carouselproduct.refresh();
        //         }
        //     }, 500);
        // }

        setTimeout(function() {
            if(jQuery(window).width() > 991) {
                heightTitle('.qs-control-tab .qs-title-tab-control');
            } else {
                heightTitle('.control-tabs .qs-title-tab-control');
            }

            if(jQuery(window).width() > 1599) {
                if(jQuery('.qs-desgin-wrapper-custom .product-name').length) {
                    var wpname = jQuery('.qs-desgin-wrapper-custom .product-name').width();
                    var wswiper = jQuery('.qs-desgin-wrapper-custom .swiper-pagination').width();

                    jQuery('.qs-desgin-wrapper-custom .product-name').css('left', -((wpname+wswiper)/2) + 'px');
                }
            } else {
                jQuery('.qs-desgin-wrapper-custom .product-name').removeAttr('style');
            }
        }, 500);
    });

    if(jQuery('.qs-nav-tabs').length) {
        jQuery(document).on('click', '.qs-nav-tabs a', function(e) {
            if(!jQuery('#' + jQuery(this).attr('aria-controls')).hasClass('active')){
                e.preventDefault();

                if(jQuery(this).parent().parent().parent().hasClass('nav-tabs-mobile')) {
                    /*jQuery('.qs-control-custom').slideDown();*/
                    jQuery('.button-control-tabs').slideUp();
                    jQuery('html,body').animate({
                            scrollTop: jQuery('.qs-design-custom').offset().top},
                        'slow');

                    jQuery('.nav-tabs-mobile .qs-nav-tabs li').each(function() {
                        jQuery(this).removeClass('active');
                    });
                }

                if(jQuery(this).parent().parent().parent().hasClass('qs-control-tab')) {
                    jQuery('.qs-control-tab .qs-nav-tabs li').each(function() {
                        jQuery(this).removeClass('active');
                    });
                }

                jQuery(this).parent().addClass('active');

                jQuery('.qs-tab-content .qs-tab-pane').each(function() {
                    jQuery(this).removeClass('active');
                });

                jQuery('#' + jQuery(this).attr('aria-controls')).addClass('active');

                // var carouselpanel = panelchoose.data('owlCarousel');
                // carouselpanel._width = panelchoose.width();
                // carouselpanel.invalidate('width');
                // carouselpanel.refresh();
                //
                // var carouselproduct = tabproduct.data('owlCarousel');
                // carouselproduct._width = tabproduct.width();
                // carouselproduct.invalidate('width');
                // carouselproduct.refresh();
            } else {
                jQuery(this).parent().removeClass('active');
                jQuery('#' + jQuery(this).attr('aria-controls')).removeClass('active')
            }

        });
    }

    if(jQuery('.back-tablet').length) {
        jQuery(document).on('click', '.back-tablet', function() {
            /*jQuery('.qs-control-custom').slideUp();*/
            jQuery('.button-control-tabs').slideDown();
            jQuery('.control-tabs ul li').each(function() {
                jQuery(this).removeClass('active');
            });
        });
    }

    jQuery(document).on('click', '.qs-row-choose-size', function() {
        jQuery('.qs-row-choose-size').each(function() {
           jQuery(this).find('label').removeAttr('class');
        });

        jQuery(this).find('label').addClass('size-active');
    });

    /*if(jQuery('.qs-choose-size-wrapper').length) {
        setTimeout(function() {
            if(jQuery(window).width() > 991) {
                jQuery('.qs-choose-size-wrapper').css('height', (jQuery('.qs-control-custom').height() - jQuery('.qs-control-bottom').height() - 150) + 'px');
            } else {
                jQuery('.qs-choose-size-wrapper').css('height', (jQuery('.qs-control-custom').height() - 75) + 'px');
            }
        }, 500);

        jQuery(window).resize(function() {
            setTimeout(function() {
                if(jQuery(window).width() > 991) {
                    jQuery('.qs-choose-size-wrapper').css('height', (jQuery('.qs-control-custom').height() - jQuery('.qs-control-bottom').height() - 150) + 'px');
                } else {
                    jQuery('.qs-choose-size-wrapper').css('height', (jQuery('.qs-control-custom').height() - 75) + 'px');
                }
            }, 500);
        });
    }*/
});

function showDetail() {
    jQuery('.qs-see-detail-wrapper').toggleClass('detail-show');
    return false;
}

function tabControl(elmnt) {
    var panelShow = jQuery(elmnt).attr('data-panel');

    jQuery('.qs-owl-name-tab').each(function () {
        jQuery(this).removeClass('active');
    });

    jQuery('.qs-panel-tab-content').each(function () {
        jQuery(this).removeClass('active').hide();
    });

    jQuery(elmnt).addClass('active');

    jQuery('.qs-tab-pane.active .qs-name-tab-control').html(jQuery(elmnt).find('span').html());

    jQuery('#' + panelShow).fadeIn();
}

function heightTitle(child) {
    var maxHeight = 0;

    jQuery(child).each(function () {
        jQuery(this).removeAttr('style');
        var height = jQuery(this).height();
        maxHeight = (height > maxHeight) ? height : maxHeight;
    });

    jQuery(child).removeAttr('style').css('height', maxHeight + 'px');
}

/*
function showDetailMobile() {
    var html;

    if(jQuery('.qs-see-detail-wrapper').length) {
        html = jQuery('.qs-see-detail-wrapper').clone().find('*').removeAttr('id onclick').end();
    } else {
        html = '';
    }

    jQuery('.mobile-details-content').html(html.html());

    if(jQuery('.mobile-details-content').hasClass('mobile-shows')) {
        jQuery('.mobile-details-content').removeClass('mobile-shows');
    } else {
        jQuery('.mobile-details-content').addClass('mobile-shows');
    }

    return false;
}*/
