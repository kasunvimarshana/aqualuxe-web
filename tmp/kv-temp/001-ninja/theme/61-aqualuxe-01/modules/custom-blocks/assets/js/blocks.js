/**
 * AquaLuxe Custom Blocks - Frontend Script
 *
 * @package AquaLuxe
 * @subpackage Modules/CustomBlocks
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Initialize Portfolio Block
     */
    function initPortfolioBlock() {
        $('.aqualuxe-portfolio').each(function() {
            const $portfolio = $(this);
            const $filters = $portfolio.find('.aqualuxe-portfolio-filter');
            const $items = $portfolio.find('.aqualuxe-portfolio-item');
            const $grid = $portfolio.find('.aqualuxe-portfolio-grid');
            
            // Initialize lightbox if available
            if ($.fn.lightbox) {
                $portfolio.find('.aqualuxe-portfolio-item-zoom').lightbox();
            }
            
            // Initialize isotope if available
            if ($.fn.isotope) {
                const $isotope = $grid.isotope({
                    itemSelector: '.aqualuxe-portfolio-item',
                    layoutMode: 'fitRows',
                    percentPosition: true
                });
                
                // Filter items on click
                $filters.on('click', function() {
                    const filterValue = $(this).attr('data-filter');
                    
                    $filters.removeClass('active');
                    $(this).addClass('active');
                    
                    if (filterValue === '*') {
                        $isotope.isotope({ filter: '*' });
                    } else {
                        $isotope.isotope({ filter: '.' + filterValue });
                    }
                    
                    return false;
                });
            } else {
                // Fallback for filtering without isotope
                $filters.on('click', function() {
                    const filterValue = $(this).attr('data-filter');
                    
                    $filters.removeClass('active');
                    $(this).addClass('active');
                    
                    if (filterValue === '*') {
                        $items.show();
                    } else {
                        $items.hide();
                        $items.filter('.' + filterValue).show();
                    }
                    
                    return false;
                });
            }
        });
    }

    /**
     * Initialize Stats Block
     */
    function initStatsBlock() {
        $('.aqualuxe-stats').each(function() {
            const $stats = $(this);
            const $values = $stats.find('.aqualuxe-stat-value');
            
            // Initialize counter if available
            if ($.fn.countTo) {
                $values.each(function() {
                    const $value = $(this);
                    const count = $value.attr('data-count');
                    
                    $value.countTo({
                        from: 0,
                        to: count,
                        speed: 2000,
                        refreshInterval: 50
                    });
                });
            }
        });
    }

    /**
     * Initialize Accordion Block
     */
    function initAccordionBlock() {
        $('.aqualuxe-accordion').each(function() {
            const $accordion = $(this);
            const $items = $accordion.find('.aqualuxe-accordion-item');
            const $buttons = $accordion.find('.aqualuxe-accordion-button');
            const $panels = $accordion.find('.aqualuxe-accordion-panel');
            const allowMultiple = $accordion.attr('data-allow-multiple') === 'true';
            
            $buttons.on('click', function() {
                const $button = $(this);
                const $item = $button.closest('.aqualuxe-accordion-item');
                const $panel = $item.find('.aqualuxe-accordion-panel');
                const isExpanded = $button.attr('aria-expanded') === 'true';
                
                // Toggle current item
                $button.attr('aria-expanded', !isExpanded);
                $panel.attr('hidden', isExpanded);
                $item.toggleClass('aqualuxe-accordion-item-active', !isExpanded);
                
                // Close other items if not allowing multiple
                if (!allowMultiple && !isExpanded) {
                    $items.not($item).removeClass('aqualuxe-accordion-item-active');
                    $buttons.not($button).attr('aria-expanded', 'false');
                    $panels.not($panel).attr('hidden', true);
                }
            });
            
            // Handle keyboard navigation
            $buttons.on('keydown', function(e) {
                const $button = $(this);
                const $buttons = $accordion.find('.aqualuxe-accordion-button');
                const index = $buttons.index($button);
                const lastIndex = $buttons.length - 1;
                
                switch (e.keyCode) {
                    case 38: // Up arrow
                        e.preventDefault();
                        if (index > 0) {
                            $buttons.eq(index - 1).focus();
                        }
                        break;
                    case 40: // Down arrow
                        e.preventDefault();
                        if (index < lastIndex) {
                            $buttons.eq(index + 1).focus();
                        }
                        break;
                    case 36: // Home
                        e.preventDefault();
                        $buttons.first().focus();
                        break;
                    case 35: // End
                        e.preventDefault();
                        $buttons.last().focus();
                        break;
                }
            });
        });
    }

    /**
     * Initialize Timeline Block
     */
    function initTimelineBlock() {
        $('.aqualuxe-timeline').each(function() {
            const $timeline = $(this);
            const $items = $timeline.find('.aqualuxe-timeline-item');
            
            // Add animation on scroll if available
            if (typeof IntersectionObserver !== 'undefined') {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('aqualuxe-timeline-item-visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.2
                });
                
                $items.each(function() {
                    observer.observe(this);
                });
            } else {
                // Fallback for browsers without IntersectionObserver
                $items.addClass('aqualuxe-timeline-item-visible');
            }
        });
    }

    /**
     * Initialize all blocks
     */
    function initBlocks() {
        initPortfolioBlock();
        initStatsBlock();
        initAccordionBlock();
        initTimelineBlock();
    }

    // Initialize when DOM is ready
    $(document).ready(function() {
        initBlocks();
    });

})(jQuery);