/**
 * AquaLuxe Theme - Custom Scripts
 *
 * Custom JavaScript functionality for the AquaLuxe theme.
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        // Initialize tooltips
        if (typeof tippy !== 'undefined') {
            tippy('[data-tippy-content]', {
                arrow: true,
                animation: 'fade'
            });
        }

        // Smooth scroll for anchor links
        $('a[href*="#"]:not([href="#"])').on('click', function() {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
                let target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 800);
                    return false;
                }
            }
        });

        // Back to top button
        const backToTop = $('.back-to-top');
        
        if (backToTop.length) {
            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    backToTop.addClass('show');
                } else {
                    backToTop.removeClass('show');
                }
            });
            
            backToTop.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({scrollTop: 0}, 800);
            });
        }

        // Mobile menu toggle
        $('.menu-toggle').on('click', function() {
            $(this).toggleClass('active');
            $('.mobile-menu').toggleClass('active');
            $('body').toggleClass('mobile-menu-active');
        });

        // Accordion functionality
        $('.accordion-header').on('click', function() {
            const accordionItem = $(this).parent();
            const accordionContent = $(this).next('.accordion-content');
            
            if (accordionItem.hasClass('active')) {
                accordionItem.removeClass('active');
                accordionContent.slideUp(300);
            } else {
                $('.accordion-item').removeClass('active');
                $('.accordion-content').slideUp(300);
                accordionItem.addClass('active');
                accordionContent.slideDown(300);
            }
        });

        // Tab functionality
        $('.tab-nav li a').on('click', function(e) {
            e.preventDefault();
            
            const tabId = $(this).attr('href');
            
            $('.tab-nav li a').removeClass('active');
            $('.tab-content').removeClass('active');
            
            $(this).addClass('active');
            $(tabId).addClass('active');
        });

        // Modal functionality
        $('[data-modal]').on('click', function(e) {
            e.preventDefault();
            
            const modalId = $(this).data('modal');
            
            $(`#${modalId}`).addClass('active');
            $('body').addClass('modal-open');
        });
        
        $('.modal-close, .modal-overlay').on('click', function() {
            $('.modal').removeClass('active');
            $('body').removeClass('modal-open');
        });

        // Initialize sliders if Swiper is available
        if (typeof Swiper !== 'undefined') {
            // Hero slider
            new Swiper('.hero-slider', {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
            
            // Testimonial slider
            new Swiper('.testimonial-slider', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 6000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.testimonial-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                    },
                    992: {
                        slidesPerView: 3,
                    },
                },
            });
        }
    });

    // Window load
    $(window).on('load', function() {
        // Remove preloader
        $('.preloader').fadeOut(500);
    });

    // Window scroll
    $(window).on('scroll', function() {
        // Sticky header
        if ($(this).scrollTop() > 100) {
            $('.site-header').addClass('sticky');
        } else {
            $('.site-header').removeClass('sticky');
        }
    });

    // Window resize
    $(window).on('resize', function() {
        // Adjust mobile menu
        if ($(window).width() > 991) {
            $('.mobile-menu').removeClass('active');
            $('.menu-toggle').removeClass('active');
            $('body').removeClass('mobile-menu-active');
        }
    });

})(jQuery);