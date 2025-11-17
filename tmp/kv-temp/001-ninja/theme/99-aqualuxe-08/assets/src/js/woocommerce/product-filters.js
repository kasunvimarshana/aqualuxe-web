// Product filters
(function($) {
    'use strict';
    
    const ProductFilters = {
        init: function() {
            this.enhanceFilters();
            this.setupAjaxFiltering();
        },
        
        enhanceFilters: function() {
            // Enhance price filter
            if ($('.price_slider').length) {
                this.enhancePriceSlider();
            }
            
            // Enhance attribute filters
            $('.widget_layered_nav').each(function() {
                const $widget = $(this);
                $widget.find('ul').addClass('filter-list');
            });
        },
        
        enhancePriceSlider: function() {
            // Add custom styling and functionality to price slider
            $('.price_slider').each(function() {
                const $slider = $(this);
                
                // Add loading state during filter
                $slider.on('slide', function() {
                    $('.shop-loading').addClass('active');
                });
            });
        },
        
        setupAjaxFiltering: function() {
            // Setup AJAX filtering for better UX
            $(document).on('change', '.widget_layered_nav input', function() {
                if ($(this).is(':checked')) {
                    console.log('Filter applied:', $(this).val());
                } else {
                    console.log('Filter removed:', $(this).val());
                }
                
                // Here you would implement AJAX filtering
                // For now, just show loading state
                $('.shop-products').addClass('loading');
            });
        }
    };
    
    $(document).ready(function() {
        ProductFilters.init();
    });
    
})(jQuery);