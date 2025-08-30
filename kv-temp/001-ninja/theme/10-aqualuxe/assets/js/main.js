/**
 * AquaLuxe Theme Main JavaScript
 *
 * This file contains all the main JavaScript functionality for the AquaLuxe theme.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe Theme Functions
     */
    var AquaLuxe = {
        /**
         * Initialize all functions
         */
        init: function() {
            this.setupPreloader();
            this.setupMobileMenu();
            this.setupStickyHeader();
            this.setupBackToTop();
            this.setupDropdownToggle();
            this.setupAccessibility();
            this.setupMiniCart();
            this.setupSearchModal();
            this.setupModalClose();
            this.setupQuantityButtons();
            this.setupProductGallery();
            this.setupProductTabs();
            this.setupProductFAQ();
            this.setupCountdownTimer();
            this.setupProductVideo();
            this.setupProduct360View();
            this.setupSmoothScroll();
            this.setupAnimations();
        },

        /**
         * Setup preloader
         */
        setupPreloader: function() {
            if ($('.preloader').length) {
                $(window).on('load', function() {
                    $('.preloader').fadeOut(500, function() {
                        $(this).remove();
                    });
                });
            }
        },

        /**
         * Setup mobile menu
         */
        setupMobileMenu: function() {
            $('.menu-toggle').on('click', function() {
                $(this).toggleClass('active');
                $('.main-navigation').toggleClass('toggled');
            });

            // Close menu when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.main-navigation').length && !$(e.target).closest('.menu-toggle').length) {
                    $('.main-navigation').removeClass('toggled');
                    $('.menu-toggle').removeClass('active');
                }
            });

            // Handle dropdown toggle
            $('.dropdown-toggle').on('click', function(e) {
                e.preventDefault();
                $(this).toggleClass('active');
                $(this).parent().toggleClass('focus');
                $(this).parent().next('ul').slideToggle(200);
            });

            // Add dropdown toggle buttons to menu items with children
            if (window.matchMedia('(max-width: 991.98px)').matches) {
                $('.main-navigation .menu-item-has-children > a').each(function() {
                    if (!$(this).next('.dropdown-toggle').length) {
                        $(this).after('<span class="dropdown-toggle" aria-expanded="false"><i class="fas fa-chevron-down"></i></span>');
                    }
                });
            }

            // Handle window resize
            $(window).on('resize', function() {
                if (window.matchMedia('(min-width: 992px)').matches) {
                    $('.main-navigation').removeClass('toggled');
                    $('.menu-toggle').removeClass('active');
                    $('.main-navigation .sub-menu').removeAttr('style');
                    $('.dropdown-toggle').removeClass('active');
                    $('.menu-item-has-children').removeClass('focus');
                } else {
                    $('.main-navigation .menu-item-has-children > a').each(function() {
                        if (!$(this).next('.dropdown-toggle').length) {
                            $(this).after('<span class="dropdown-toggle" aria-expanded="false"><i class="fas fa-chevron-down"></i></span>');
                        }
                    });
                }
            });
        },

        /**
         * Setup sticky header
         */
        setupStickyHeader: function() {
            var $header = $('.site-header');
            var headerHeight = $header.outerHeight();
            var $headerPlaceholder = $('<div class="header-placeholder"></div>').height(headerHeight);
            var scrollPosition = $(window).scrollTop();
            var isSticky = false;

            // Check if sticky header is enabled
            if (!$header.hasClass('sticky-header')) {
                return;
            }

            // Add header placeholder
            $header.after($headerPlaceholder);
            $headerPlaceholder.hide();

            // Handle scroll
            $(window).on('scroll', function() {
                var currentScrollPosition = $(window).scrollTop();

                if (currentScrollPosition > headerHeight) {
                    if (!isSticky) {
                        $header.addClass('is-sticky');
                        $headerPlaceholder.show();
                        isSticky = true;
                    }
                } else {
                    if (isSticky) {
                        $header.removeClass('is-sticky');
                        $headerPlaceholder.hide();
                        isSticky = false;
                    }
                }

                // Add scroll direction class
                if (currentScrollPosition > scrollPosition) {
                    $header.addClass('scroll-down').removeClass('scroll-up');
                } else {
                    $header.addClass('scroll-up').removeClass('scroll-down');
                }

                scrollPosition = currentScrollPosition;
            });

            // Handle window resize
            $(window).on('resize', function() {
                headerHeight = $header.outerHeight();
                $headerPlaceholder.height(headerHeight);
            });
        },

        /**
         * Setup back to top button
         */
        setupBackToTop: function() {
            var $backToTop = $('#back-to-top');

            if (!$backToTop.length) {
                return;
            }

            $(window).on('scroll', function() {
                if ($(this).scrollTop() > 300) {
                    $backToTop.addClass('show');
                } else {
                    $backToTop.removeClass('show');
                }
            });

            $backToTop.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 800);
            });
        },

        /**
         * Setup dropdown toggle
         */
        setupDropdownToggle: function() {
            $('.main-navigation .menu-item-has-children > a').append('<span class="dropdown-toggle" aria-expanded="false"><i class="fas fa-chevron-down"></i></span>');

            $('.main-navigation .menu-item-has-children').hover(function() {
                if (window.matchMedia('(min-width: 992px)').matches) {
                    $(this).find('> .sub-menu').stop(true, true).fadeIn(200);
                    $(this).find('> a .dropdown-toggle').attr('aria-expanded', 'true');
                }
            }, function() {
                if (window.matchMedia('(min-width: 992px)').matches) {
                    $(this).find('> .sub-menu').stop(true, true).fadeOut(200);
                    $(this).find('> a .dropdown-toggle').attr('aria-expanded', 'false');
                }
            });
        },

        /**
         * Setup accessibility
         */
        setupAccessibility: function() {
            // Add aria attributes to menu items
            $('.main-navigation .menu-item-has-children > a').attr('aria-haspopup', 'true');

            // Skip link focus fix
            var isIe = /(trident|msie)/i.test(navigator.userAgent);

            if (isIe && document.getElementById && window.addEventListener) {
                window.addEventListener('hashchange', function() {
                    var id = location.hash.substring(1);

                    if (!(/^[A-z0-9_-]+$/.test(id))) {
                        return;
                    }

                    var element = document.getElementById(id);

                    if (element) {
                        if (!(/^(?:a|select|input|button|textarea)$/i.test(element.tagName))) {
                            element.tabIndex = -1;
                        }

                        element.focus();
                    }
                }, false);
            }
        },

        /**
         * Setup mini cart
         */
        setupMiniCart: function() {
            // Show mini cart on hover
            $('.header-cart').hover(function() {
                $(this).find('.mini-cart-dropdown').stop(true, true).fadeIn(200);
            }, function() {
                $(this).find('.mini-cart-dropdown').stop(true, true).fadeOut(200);
            });

            // Update mini cart on AJAX add to cart
            $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
                $('.header-cart').addClass('pulse');

                setTimeout(function() {
                    $('.header-cart').removeClass('pulse');
                }, 1000);
            });
        },

        /**
         * Setup search modal
         */
        setupSearchModal: function() {
            $('.search-toggle').on('click', function(e) {
                e.preventDefault();
                $('.search-modal').addClass('show');
                setTimeout(function() {
                    $('.search-modal .search-field').focus();
                }, 100);
            });

            $('.search-modal-close').on('click', function(e) {
                e.preventDefault();
                $('.search-modal').removeClass('show');
            });

            // Close search modal when clicking outside
            $(document).on('click', function(e) {
                if ($(e.target).closest('.search-modal-content').length === 0 && $(e.target).closest('.search-toggle').length === 0) {
                    $('.search-modal').removeClass('show');
                }
            });

            // Close search modal on escape key
            $(document).on('keyup', function(e) {
                if (e.key === 'Escape') {
                    $('.search-modal').removeClass('show');
                }
            });
        },

        /**
         * Setup modal close
         */
        setupModalClose: function() {
            $('.aqualuxe-modal-close').on('click', function() {
                $(this).closest('.aqualuxe-modal').removeClass('show');
            });

            // Close modal when clicking outside
            $(document).on('click', function(e) {
                if ($(e.target).hasClass('aqualuxe-modal')) {
                    $(e.target).removeClass('show');
                }
            });

            // Close modal on escape key
            $(document).on('keyup', function(e) {
                if (e.key === 'Escape') {
                    $('.aqualuxe-modal').removeClass('show');
                }
            });
        },

        /**
         * Setup quantity buttons
         */
        setupQuantityButtons: function() {
            // Add quantity buttons
            $('.quantity').each(function() {
                var $quantity = $(this);
                var $input = $quantity.find('input[type="number"]');

                if (!$quantity.find('.quantity-button').length) {
                    $quantity.prepend('<span class="quantity-button quantity-down">-</span>');
                    $quantity.append('<span class="quantity-button quantity-up">+</span>');
                }

                // Handle quantity buttons click
                $quantity.on('click', '.quantity-button', function() {
                    var $button = $(this);
                    var oldValue = parseFloat($input.val());
                    var newVal;

                    if ($button.hasClass('quantity-up')) {
                        newVal = oldValue + 1;
                    } else {
                        if (oldValue > 1) {
                            newVal = oldValue - 1;
                        } else {
                            newVal = 1;
                        }
                    }

                    $input.val(newVal);
                    $input.trigger('change');
                });
            });

            // Re-run on AJAX complete
            $(document.body).on('updated_cart_totals', function() {
                AquaLuxe.setupQuantityButtons();
            });
        },

        /**
         * Setup product gallery
         */
        setupProductGallery: function() {
            // Check if product gallery exists
            if (!$('.woocommerce-product-gallery').length) {
                return;
            }

            // Add gallery zoom
            if (typeof $.fn.zoom !== 'undefined') {
                $('.woocommerce-product-gallery__image').each(function() {
                    $(this).zoom({
                        url: $(this).find('img').attr('data-large_image'),
                        touch: false
                    });
                });
            }
        },

        /**
         * Setup product tabs
         */
        setupProductTabs: function() {
            $('.woocommerce-tabs').each(function() {
                var $tabs = $(this);
                var $tabLinks = $tabs.find('.wc-tabs li a');
                var $tabPanels = $tabs.find('.woocommerce-Tabs-panel');

                // Hide all panels except the active one
                $tabPanels.hide();
                $tabPanels.eq(0).show();

                // Set active class on first tab
                $tabLinks.eq(0).parent().addClass('active');

                // Handle tab click
                $tabLinks.on('click', function(e) {
                    e.preventDefault();

                    var $link = $(this);
                    var tabId = $link.attr('href');

                    // Remove active class from all tabs
                    $tabLinks.parent().removeClass('active');

                    // Add active class to current tab
                    $link.parent().addClass('active');

                    // Hide all panels
                    $tabPanels.hide();

                    // Show current panel
                    $(tabId).show();
                });
            });
        },

        /**
         * Setup product FAQ
         */
        setupProductFAQ: function() {
            $('.product-faq-question').on('click', function() {
                var $question = $(this);
                var $item = $question.parent();
                var $answer = $item.find('.product-faq-answer');

                if ($item.hasClass('active')) {
                    $item.removeClass('active');
                    $answer.slideUp(200);
                } else {
                    $('.product-faq-item').removeClass('active');
                    $('.product-faq-answer').slideUp(200);
                    $item.addClass('active');
                    $answer.slideDown(200);
                }
            });
        },

        /**
         * Setup countdown timer
         */
        setupCountdownTimer: function() {
            $('.product-countdown').each(function() {
                var $countdown = $(this);
                var endDate = new Date($countdown.data('end-date')).getTime();

                // Update countdown every second
                var countdownInterval = setInterval(function() {
                    var now = new Date().getTime();
                    var distance = endDate - now;

                    // If countdown is finished
                    if (distance < 0) {
                        clearInterval(countdownInterval);
                        $countdown.remove();
                        return;
                    }

                    // Calculate days, hours, minutes and seconds
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Update countdown
                    $countdown.find('.days').text(days < 10 ? '0' + days : days);
                    $countdown.find('.hours').text(hours < 10 ? '0' + hours : hours);
                    $countdown.find('.minutes').text(minutes < 10 ? '0' + minutes : minutes);
                    $countdown.find('.seconds').text(seconds < 10 ? '0' + seconds : seconds);
                }, 1000);
            });
        },

        /**
         * Setup product video
         */
        setupProductVideo: function() {
            $('.product-video-wrapper').each(function() {
                var $videoWrapper = $(this);
                var $video = $videoWrapper.find('.product-video');

                // Add play button if not exists
                if (!$video.find('.play-button').length) {
                    $video.append('<div class="play-button"><i class="fas fa-play"></i></div>');
                }

                // Handle play button click
                $video.on('click', '.play-button', function() {
                    var $playButton = $(this);
                    var $iframe = $video.find('iframe');
                    var $videoElement = $video.find('video');

                    if ($iframe.length) {
                        // YouTube or Vimeo video
                        var src = $iframe.attr('src');

                        // Add autoplay parameter
                        if (src.indexOf('?') !== -1) {
                            src += '&autoplay=1';
                        } else {
                            src += '?autoplay=1';
                        }

                        $iframe.attr('src', src);
                        $playButton.fadeOut(200);
                    } else if ($videoElement.length) {
                        // HTML5 video
                        $videoElement[0].play();
                        $playButton.fadeOut(200);
                    }
                });
            });
        },

        /**
         * Setup product 360 view
         */
        setupProduct360View: function() {
            $('.product-360-view-button').on('click', function(e) {
                e.preventDefault();
                $('#product-360-modal').addClass('show');
            });

            $('.product-360-view').each(function() {
                var $view = $(this);
                var $container = $view.find('.product-360-view-container');
                var $spinner = $view.find('.spinner');
                var $image = $view.find('.product-360-image');
                var $prev = $view.find('.product-360-prev');
                var $next = $view.find('.product-360-next');
                var images = $view.data('images').split(',');
                var totalImages = images.length;
                var currentImage = 0;
                var loaded = 0;
                var dragging = false;
                var startX = 0;
                var currentX = 0;

                // Preload images
                function preloadImages() {
                    $spinner.show();

                    for (var i = 0; i < totalImages; i++) {
                        var img = new Image();
                        img.onload = function() {
                            loaded++;

                            if (loaded === totalImages) {
                                $spinner.hide();
                            }
                        };
                        img.src = images[i];
                    }
                }

                // Show image
                function showImage(index) {
                    if (index < 0) {
                        index = totalImages - 1;
                    } else if (index >= totalImages) {
                        index = 0;
                    }

                    currentImage = index;
                    $image.attr('src', images[currentImage]);
                }

                // Handle previous button click
                $prev.on('click', function(e) {
                    e.preventDefault();
                    showImage(currentImage - 1);
                });

                // Handle next button click
                $next.on('click', function(e) {
                    e.preventDefault();
                    showImage(currentImage + 1);
                });

                // Handle mouse/touch events
                $container.on('mousedown touchstart', function(e) {
                    e.preventDefault();
                    dragging = true;
                    startX = e.pageX || e.originalEvent.touches[0].pageX;
                    currentX = startX;
                });

                $(document).on('mousemove touchmove', function(e) {
                    if (!dragging) {
                        return;
                    }

                    e.preventDefault();
                    currentX = e.pageX || e.originalEvent.touches[0].pageX;

                    // Calculate drag distance
                    var distance = currentX - startX;

                    // Change image if drag distance is enough
                    if (Math.abs(distance) > 30) {
                        if (distance > 0) {
                            showImage(currentImage - 1);
                        } else {
                            showImage(currentImage + 1);
                        }

                        startX = currentX;
                    }
                });

                $(document).on('mouseup touchend', function() {
                    dragging = false;
                });

                // Initialize
                preloadImages();
                showImage(0);
            });
        },

        /**
         * Setup smooth scroll
         */
        setupSmoothScroll: function() {
            // Smooth scroll for anchor links
            $('a[href*="#"]:not([href="#"])').on('click', function() {
                if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
                    var target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');

                    if (target.length) {
                        $('html, body').animate({
                            scrollTop: target.offset().top - 100
                        }, 800);
                        return false;
                    }
                }
            });
        },

        /**
         * Setup animations
         */
        setupAnimations: function() {
            // Add fade-in animation to elements
            $('.fade-in').each(function() {
                var $element = $(this);

                if ($element.offset().top < $(window).height()) {
                    $element.addClass('animated');
                }
            });

            // Add fade-in-up animation to elements
            $('.fade-in-up').each(function() {
                var $element = $(this);

                if ($element.offset().top < $(window).height()) {
                    $element.addClass('animated');
                }
            });

            // Add fade-in-down animation to elements
            $('.fade-in-down').each(function() {
                var $element = $(this);

                if ($element.offset().top < $(window).height()) {
                    $element.addClass('animated');
                }
            });

            // Handle scroll
            $(window).on('scroll', function() {
                var scrollTop = $(window).scrollTop();
                var windowHeight = $(window).height();

                // Fade in elements
                $('.fade-in:not(.animated)').each(function() {
                    var $element = $(this);
                    var elementTop = $element.offset().top;

                    if (elementTop < scrollTop + windowHeight - 100) {
                        $element.addClass('animated');
                    }
                });

                // Fade in up elements
                $('.fade-in-up:not(.animated)').each(function() {
                    var $element = $(this);
                    var elementTop = $element.offset().top;

                    if (elementTop < scrollTop + windowHeight - 100) {
                        $element.addClass('animated');
                    }
                });

                // Fade in down elements
                $('.fade-in-down:not(.animated)').each(function() {
                    var $element = $(this);
                    var elementTop = $element.offset().top;

                    if (elementTop < scrollTop + windowHeight - 100) {
                        $element.addClass('animated');
                    }
                });
            });
        }
    };

    // Initialize AquaLuxe theme functions
    $(document).ready(function() {
        AquaLuxe.init();
    });

})(jQuery);