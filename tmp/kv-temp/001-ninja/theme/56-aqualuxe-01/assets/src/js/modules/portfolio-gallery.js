/**
 * Portfolio Gallery Module
 * 
 * Handles filtering, animations, and lightbox functionality for the portfolio gallery.
 */

(function($) {
    'use strict';

    const PortfolioGallery = {
        /**
         * Initialize the portfolio gallery functionality
         */
        init: function() {
            // Initialize variables
            this.portfolioGallery = $('.aqualuxe-portfolio-gallery');
            
            // Exit if no portfolio gallery found
            if (!this.portfolioGallery.length) {
                return;
            }
            
            // Initialize components
            this.initFilters();
            this.initLightbox();
            this.initMasonry();
            this.initAnimations();
            
            // Trigger window resize to fix layout issues
            $(window).trigger('resize');
        },
        
        /**
         * Initialize portfolio filtering
         */
        initFilters: function() {
            const self = this;
            const filterButtons = $('.portfolio-filter-btn');
            
            if (!filterButtons.length) {
                return;
            }
            
            filterButtons.on('click', function(e) {
                e.preventDefault();
                
                const $this = $(this);
                const filterValue = $this.data('filter');
                
                // Update active class
                filterButtons.removeClass('active');
                $this.addClass('active');
                
                // Filter items
                self.filterItems(filterValue);
            });
        },
        
        /**
         * Filter portfolio items based on category
         * 
         * @param {string} category - The category to filter by
         */
        filterItems: function(category) {
            const portfolioItems = $('.portfolio-item');
            const isAll = category === 'all';
            
            portfolioItems.each(function() {
                const $item = $(this);
                const itemCategory = $item.data('category');
                
                // Show/hide items based on category
                if (isAll || itemCategory === category) {
                    $item.removeClass('hidden');
                    self.animateItem($item);
                } else {
                    $item.addClass('hidden');
                }
            });
            
            // Re-layout masonry if active
            if (this.portfolioGallery.hasClass('layout-masonry') && typeof $.fn.masonry !== 'undefined') {
                $('.portfolio-grid').masonry('layout');
            }
        },
        
        /**
         * Animate portfolio item entrance
         * 
         * @param {jQuery} $item - The item to animate
         */
        animateItem: function($item) {
            const animationType = this.portfolioGallery.data('animation') || 'fade';
            
            // Reset animation
            $item.css('animation', 'none');
            
            // Trigger reflow
            $item[0].offsetHeight;
            
            // Apply animation with delay based on index
            const delay = $item.index() * 0.1;
            $item.css({
                'animation': `portfolio${animationType.charAt(0).toUpperCase() + animationType.slice(1)} 0.5s ease-in-out ${delay}s forwards`
            });
        },
        
        /**
         * Initialize lightbox for portfolio images
         */
        initLightbox: function() {
            if (typeof $.fn.magnificPopup !== 'undefined') {
                $('.portfolio-zoom-btn').magnificPopup({
                    type: 'image',
                    gallery: {
                        enabled: true,
                        navigateByImgClick: true,
                        preload: [0, 1]
                    },
                    mainClass: 'mfp-fade',
                    removalDelay: 300,
                    callbacks: {
                        beforeOpen: function() {
                            this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
                        }
                    }
                });
            }
        },
        
        /**
         * Initialize masonry layout if available
         */
        initMasonry: function() {
            if (this.portfolioGallery.hasClass('layout-masonry') && typeof $.fn.masonry !== 'undefined') {
                $('.portfolio-grid').masonry({
                    itemSelector: '.portfolio-item',
                    percentPosition: true
                });
                
                // Re-layout masonry after images load
                $('.portfolio-item__image img').on('load', function() {
                    $('.portfolio-grid').masonry('layout');
                });
            }
        },
        
        /**
         * Initialize entrance animations for portfolio items
         */
        initAnimations: function() {
            const self = this;
            const portfolioItems = $('.portfolio-item');
            
            // Animate each item with a delay
            portfolioItems.each(function(index) {
                const $item = $(this);
                self.animateItem($item);
            });
        }
    };
    
    // Initialize on document ready
    $(document).ready(function() {
        PortfolioGallery.init();
    });
    
})(jQuery);