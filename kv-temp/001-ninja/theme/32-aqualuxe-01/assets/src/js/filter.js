/**
 * AquaLuxe Theme - Product Filter
 *
 * Handles the product filtering functionality.
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Check if we're on a shop page with filters
        if (!$('.shop-filters').length) {
            return;
        }

        // Filter toggle for mobile
        $('.filter-toggle').on('click', function(e) {
            e.preventDefault();
            
            $('.shop-filters').toggleClass('active');
            $('body').toggleClass('filters-active');
            
            if ($('.shop-filters').hasClass('active')) {
                $(this).attr('aria-expanded', 'true');
            } else {
                $(this).attr('aria-expanded', 'false');
            }
        });

        // Close filters when clicking outside on mobile
        $(document).on('click', function(e) {
            if ($(window).width() < 992) {
                if (!$(e.target).closest('.shop-filters, .filter-toggle').length && $('.shop-filters').hasClass('active')) {
                    $('.shop-filters').removeClass('active');
                    $('body').removeClass('filters-active');
                    $('.filter-toggle').attr('aria-expanded', 'false');
                }
            }
        });

        // Initialize price range slider
        if ($('.price-range-slider').length && typeof noUiSlider !== 'undefined') {
            const priceSlider = document.querySelector('.price-range-slider');
            const minPrice = parseInt($(priceSlider).data('min'));
            const maxPrice = parseInt($(priceSlider).data('max'));
            const currentMinPrice = parseInt($(priceSlider).data('current-min')) || minPrice;
            const currentMaxPrice = parseInt($(priceSlider).data('current-max')) || maxPrice;
            
            noUiSlider.create(priceSlider, {
                start: [currentMinPrice, currentMaxPrice],
                connect: true,
                step: 1,
                range: {
                    'min': minPrice,
                    'max': maxPrice
                },
                format: {
                    to: function(value) {
                        return Math.round(value);
                    },
                    from: function(value) {
                        return Math.round(value);
                    }
                }
            });
            
            // Update price display
            const minPriceInput = document.getElementById('min_price');
            const maxPriceInput = document.getElementById('max_price');
            const minPriceDisplay = document.querySelector('.min-price-display');
            const maxPriceDisplay = document.querySelector('.max-price-display');
            
            priceSlider.noUiSlider.on('update', function(values, handle) {
                if (handle === 0) {
                    minPriceInput.value = values[0];
                    if (minPriceDisplay) {
                        minPriceDisplay.textContent = formatPrice(values[0]);
                    }
                } else {
                    maxPriceInput.value = values[1];
                    if (maxPriceDisplay) {
                        maxPriceDisplay.textContent = formatPrice(values[1]);
                    }
                }
            });
        }

        // Format price with currency symbol
        function formatPrice(price) {
            const currencySymbol = $('.price-range-slider').data('currency-symbol') || '$';
            const currencyPosition = $('.price-range-slider').data('currency-position') || 'left';
            
            if (currencyPosition === 'left') {
                return currencySymbol + price;
            } else {
                return price + currencySymbol;
            }
        }

        // AJAX filtering
        const filterForm = $('#shop-filter-form');
        
        if (filterForm.length) {
            // Handle filter form submission
            filterForm.on('submit', function(e) {
                e.preventDefault();
                
                // Show loading overlay
                $('.products-wrapper').addClass('loading');
                
                // Get form data
                const formData = $(this).serialize();
                
                // AJAX call to filter products
                $.ajax({
                    url: aqualuxeSettings.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_filter_products',
                        form_data: formData,
                        nonce: aqualuxeSettings.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update products
                            $('.products-wrapper').html(response.data.products);
                            
                            // Update pagination
                            $('.woocommerce-pagination').html(response.data.pagination);
                            
                            // Update product count
                            $('.woocommerce-result-count').html(response.data.count);
                            
                            // Update URL
                            if (history.pushState) {
                                history.pushState(null, null, response.data.url);
                            }
                            
                            // Initialize lazy loading
                            if (typeof lazySizes !== 'undefined') {
                                lazySizes.init();
                            }
                            
                            // Close filters on mobile
                            if ($(window).width() < 992) {
                                $('.shop-filters').removeClass('active');
                                $('body').removeClass('filters-active');
                                $('.filter-toggle').attr('aria-expanded', 'false');
                            }
                            
                            // Scroll to top of products
                            $('html, body').animate({
                                scrollTop: $('.products-wrapper').offset().top - 100
                            }, 500);
                        }
                        
                        // Remove loading overlay
                        $('.products-wrapper').removeClass('loading');
                    },
                    error: function() {
                        // Remove loading overlay
                        $('.products-wrapper').removeClass('loading');
                        
                        // Show error message
                        alert('Failed to filter products. Please try again.');
                    }
                });
            });
            
            // Handle filter changes
            $('.shop-filters input[type="checkbox"], .shop-filters input[type="radio"], .shop-filters select').on('change', function() {
                filterForm.submit();
            });
            
            // Handle price filter
            if ($('.price-range-slider').length && typeof noUiSlider !== 'undefined') {
                const priceSlider = document.querySelector('.price-range-slider');
                
                priceSlider.noUiSlider.on('change', function() {
                    filterForm.submit();
                });
            }
            
            // Handle clear filters
            $('.clear-filters').on('click', function(e) {
                e.preventDefault();
                
                // Reset form
                filterForm[0].reset();
                
                // Reset price slider
                if ($('.price-range-slider').length && typeof noUiSlider !== 'undefined') {
                    const priceSlider = document.querySelector('.price-range-slider');
                    const minPrice = parseInt($(priceSlider).data('min'));
                    const maxPrice = parseInt($(priceSlider).data('max'));
                    
                    priceSlider.noUiSlider.set([minPrice, maxPrice]);
                }
                
                // Submit form
                filterForm.submit();
            });
        }

        // Handle sort by change
        $('.woocommerce-ordering select').on('change', function() {
            $(this).closest('form').submit();
        });

        // Initialize select2 for filter dropdowns if available
        if (typeof $.fn.select2 !== 'undefined') {
            $('.shop-filters select').select2({
                minimumResultsForSearch: 10,
                dropdownCssClass: 'filter-dropdown'
            });
        }

        // Handle filter accordion on mobile
        $('.filter-section-title').on('click', function() {
            if ($(window).width() < 992) {
                $(this).toggleClass('active');
                $(this).next('.filter-section-content').slideToggle(300);
            }
        });

        // Open active filter sections on mobile
        if ($(window).width() < 992) {
            $('.filter-section-title.active').next('.filter-section-content').show();
        }
    });

})(jQuery);