/**
 * File theme.js.
 *
 * Main theme JavaScript file.
 */
(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        // Initialize masonry layout if enabled
        if ($('.masonry-grid').length) {
            $('.masonry-grid').imagesLoaded(function() {
                $('.masonry-grid').masonry({
                    itemSelector: '.post',
                    columnWidth: '.post',
                    percentPosition: true
                });
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
        const backToTopButton = $('#back-to-top');
        
        if (backToTopButton.length) {
            $(window).scroll(function() {
                if ($(window).scrollTop() > 300) {
                    backToTopButton.addClass('show');
                } else {
                    backToTopButton.removeClass('show');
                }
            });

            backToTopButton.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({scrollTop: 0}, 800);
            });
        }

        // Add animation classes when elements come into view
        function animateElements() {
            $('.animate-on-scroll').each(function() {
                const elementTop = $(this).offset().top;
                const elementBottom = elementTop + $(this).outerHeight();
                const viewportTop = $(window).scrollTop();
                const viewportBottom = viewportTop + $(window).height();
                
                if (elementBottom > viewportTop && elementTop < viewportBottom) {
                    $(this).addClass('animated');
                }
            });
        }

        // Run animation on page load
        animateElements();
        
        // Run animation on scroll
        $(window).on('scroll', animateElements);

        // Initialize tooltips
        $('[data-tooltip]').each(function() {
            $(this).on('mouseenter', function() {
                const tooltip = $('<div class="tooltip"></div>');
                tooltip.text($(this).data('tooltip'));
                $('body').append(tooltip);
                
                const elementPosition = $(this).offset();
                const elementWidth = $(this).outerWidth();
                const tooltipWidth = tooltip.outerWidth();
                
                tooltip.css({
                    top: elementPosition.top - tooltip.outerHeight() - 10,
                    left: elementPosition.left + (elementWidth / 2) - (tooltipWidth / 2)
                }).addClass('show');
                
                $(this).on('mouseleave', function() {
                    tooltip.remove();
                });
            });
        });

        // Handle responsive tables
        $('table').wrap('<div class="table-responsive"></div>');

        // Handle responsive embeds
        $('iframe[src*="youtube.com"], iframe[src*="vimeo.com"]').each(function() {
            if (!$(this).parent('.responsive-embed').length) {
                $(this).wrap('<div class="responsive-embed"></div>');
            }
        });

        // Initialize custom select dropdowns
        $('.custom-select').each(function() {
            const select = $(this);
            const options = select.find('option');
            const selectedOption = select.find('option:selected');
            
            // Create custom select wrapper
            const customSelect = $('<div class="custom-select-wrapper"></div>');
            const customSelectTrigger = $('<div class="custom-select-trigger"></div>').text(selectedOption.text());
            const customSelectOptions = $('<div class="custom-select-options"></div>');
            
            // Add options to custom select
            options.each(function() {
                const option = $(this);
                const customOption = $('<div class="custom-select-option" data-value="' + option.val() + '"></div>').text(option.text());
                
                if (option.is(':selected')) {
                    customOption.addClass('selected');
                }
                
                customOption.on('click', function() {
                    select.val($(this).data('value'));
                    customSelectTrigger.text($(this).text());
                    customSelectOptions.find('.custom-select-option').removeClass('selected');
                    $(this).addClass('selected');
                    select.trigger('change');
                    customSelect.removeClass('open');
                });
                
                customSelectOptions.append(customOption);
            });
            
            // Add custom select to DOM
            customSelect.append(customSelectTrigger).append(customSelectOptions);
            select.after(customSelect);
            select.hide();
            
            // Toggle custom select on click
            customSelectTrigger.on('click', function() {
                $('html').one('click', function() {
                    customSelect.removeClass('open');
                });
                
                customSelect.toggleClass('open');
                return false;
            });
        });
    });

    // Window load
    $(window).on('load', function() {
        // Remove loading overlay
        $('.page-loader').fadeOut(500, function() {
            $(this).remove();
        });
    });

    // Window resize
    $(window).on('resize', function() {
        // Reinitialize masonry layout on window resize
        if ($('.masonry-grid').length) {
            $('.masonry-grid').masonry('layout');
        }
    });

})(jQuery);