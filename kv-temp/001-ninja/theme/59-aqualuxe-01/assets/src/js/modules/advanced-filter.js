/**
 * Advanced Filter Module
 * 
 * Handles advanced filtering functionality for products
 */

(function($) {
  'use strict';

  const AquaLuxeAdvancedFilter = {
    /**
     * Initialize advanced filter
     */
    init: function() {
      this.initFilterSidebar();
      this.initPriceFilter();
      this.initAttributeFilter();
      this.initCategoryFilter();
      this.initRatingFilter();
      this.initSortingFilter();
      this.initAjaxFilter();
      this.initMobileFilter();
      this.initFilterReset();
    },

    /**
     * Initialize filter sidebar
     */
    initFilterSidebar: function() {
      // Filter button click
      $(document).on('click', '.filter-button', function(e) {
        e.preventDefault();

        const $sidebar = $('.filter-sidebar');
        const $overlay = $('.filter-sidebar__overlay');

        $sidebar.addClass('active');
        $overlay.addClass('active');
        $('body').addClass('filter-sidebar-open');
      });

      // Close filter sidebar
      $(document).on('click', '.filter-sidebar__close, .filter-sidebar__overlay', function(e) {
        e.preventDefault();

        const $sidebar = $('.filter-sidebar');
        const $overlay = $('.filter-sidebar__overlay');

        $sidebar.removeClass('active');
        $overlay.removeClass('active');
        $('body').removeClass('filter-sidebar-open');
      });

      // Close filter sidebar on ESC key
      $(document).on('keyup', function(e) {
        if (e.key === 'Escape' && $('.filter-sidebar').hasClass('active')) {
          $('.filter-sidebar').removeClass('active');
          $('.filter-sidebar__overlay').removeClass('active');
          $('body').removeClass('filter-sidebar-open');
        }
      });

      // Apply filters
      $(document).on('click', '.filter-sidebar__apply', function(e) {
        e.preventDefault();

        // Close filter sidebar
        $('.filter-sidebar').removeClass('active');
        $('.filter-sidebar__overlay').removeClass('active');
        $('body').removeClass('filter-sidebar-open');

        // Apply filters
        AquaLuxeAdvancedFilter.applyFilters();
      });
    },

    /**
     * Initialize price filter
     */
    initPriceFilter: function() {
      // Price slider
      const $priceSlider = $('.price_slider');
      
      if ($priceSlider.length) {
        // Get price values
        const $minPrice = $('.price_slider_amount #min_price');
        const $maxPrice = $('.price_slider_amount #max_price');
        const minPrice = parseFloat($minPrice.data('min'));
        const maxPrice = parseFloat($maxPrice.data('max'));
        const currentMinPrice = parseFloat($minPrice.val());
        const currentMaxPrice = parseFloat($maxPrice.val());
        
        // Initialize price slider
        $priceSlider.slider({
          range: true,
          min: minPrice,
          max: maxPrice,
          values: [currentMinPrice, currentMaxPrice],
          slide: function(event, ui) {
            $minPrice.val(ui.values[0]);
            $maxPrice.val(ui.values[1]);
            
            // Update price display
            $('.price_slider_amount .price_label .from').text(
              aqualuxe_advanced_filter.currency_symbol + ui.values[0]
            );
            $('.price_slider_amount .price_label .to').text(
              aqualuxe_advanced_filter.currency_symbol + ui.values[1]
            );
          },
          change: function(event, ui) {
            if (aqualuxe_advanced_filter.ajax_filter) {
              // Trigger filter update on slider change
              AquaLuxeAdvancedFilter.applyFilters();
            }
          }
        });
        
        // Initialize price display
        $('.price_slider_amount .price_label .from').text(
          aqualuxe_advanced_filter.currency_symbol + currentMinPrice
        );
        $('.price_slider_amount .price_label .to').text(
          aqualuxe_advanced_filter.currency_symbol + currentMaxPrice
        );
      }
    },

    /**
     * Initialize attribute filter
     */
    initAttributeFilter: function() {
      // Attribute filter change
      $(document).on('change', '.widget_layered_nav input[type="checkbox"]', function() {
        if (aqualuxe_advanced_filter.ajax_filter) {
          // Trigger filter update on attribute change
          AquaLuxeAdvancedFilter.applyFilters();
        }
      });
      
      // Color and image swatches
      $(document).on('click', '.swatch-filter', function(e) {
        e.preventDefault();
        
        const $swatch = $(this);
        const $checkbox = $swatch.find('input[type="checkbox"]');
        
        // Toggle checkbox
        $checkbox.prop('checked', !$checkbox.prop('checked'));
        
        // Toggle active class
        $swatch.toggleClass('active');
        
        if (aqualuxe_advanced_filter.ajax_filter) {
          // Trigger filter update on swatch change
          AquaLuxeAdvancedFilter.applyFilters();
        }
      });
    },

    /**
     * Initialize category filter
     */
    initCategoryFilter: function() {
      // Category filter change
      $(document).on('change', '.widget_product_categories input[type="checkbox"]', function() {
        if (aqualuxe_advanced_filter.ajax_filter) {
          // Trigger filter update on category change
          AquaLuxeAdvancedFilter.applyFilters();
        }
      });
      
      // Toggle subcategories
      $(document).on('click', '.widget_product_categories .cat-parent > .toggle', function(e) {
        e.preventDefault();
        
        const $toggle = $(this);
        const $parent = $toggle.parent();
        const $children = $parent.find('> .children');
        
        // Toggle children
        $children.slideToggle(200);
        
        // Toggle icon
        $toggle.toggleClass('active');
      });
    },

    /**
     * Initialize rating filter
     */
    initRatingFilter: function() {
      // Rating filter change
      $(document).on('change', '.widget_rating_filter input[type="checkbox"]', function() {
        if (aqualuxe_advanced_filter.ajax_filter) {
          // Trigger filter update on rating change
          AquaLuxeAdvancedFilter.applyFilters();
        }
      });
    },

    /**
     * Initialize sorting filter
     */
    initSortingFilter: function() {
      // Sorting change
      $(document).on('change', '.woocommerce-ordering select.orderby', function() {
        if (aqualuxe_advanced_filter.ajax_filter) {
          // Trigger filter update on sorting change
          AquaLuxeAdvancedFilter.applyFilters();
        } else {
          // Submit form
          $(this).closest('form').submit();
        }
      });
    },

    /**
     * Initialize AJAX filter
     */
    initAjaxFilter: function() {
      // Check if AJAX filter is enabled
      if (!aqualuxe_advanced_filter.ajax_filter) {
        return;
      }
      
      // Filter form submit
      $(document).on('submit', '.widget_price_filter form, .widget_layered_nav form', function(e) {
        e.preventDefault();
        
        // Apply filters
        AquaLuxeAdvancedFilter.applyFilters();
      });
      
      // Pagination links
      $(document).on('click', '.woocommerce-pagination a.page-numbers', function(e) {
        e.preventDefault();
        
        // Get page number
        const url = new URL($(this).attr('href'));
        const page = url.searchParams.get('paged') || 1;
        
        // Apply filters with page number
        AquaLuxeAdvancedFilter.applyFilters(page);
        
        // Scroll to top of products
        $('html, body').animate({
          scrollTop: $('.products').offset().top - 100
        }, 500);
      });
    },

    /**
     * Initialize mobile filter
     */
    initMobileFilter: function() {
      // Check if on mobile
      if (window.innerWidth <= 768) {
        // Move filter widgets to filter sidebar on mobile
        const $filterSidebar = $('.filter-sidebar__content');
        const $shopSidebar = $('.shop-sidebar');
        
        if ($filterSidebar.length && $shopSidebar.length) {
          // Clone widgets to filter sidebar
          $shopSidebar.find('.widget').clone(true).appendTo($filterSidebar);
          
          // Hide shop sidebar on mobile
          $shopSidebar.addClass('hidden-mobile');
        }
      }
      
      // Update on window resize
      $(window).on('resize', function() {
        if (window.innerWidth <= 768) {
          $('.shop-sidebar').addClass('hidden-mobile');
        } else {
          $('.shop-sidebar').removeClass('hidden-mobile');
        }
      });
    },

    /**
     * Initialize filter reset
     */
    initFilterReset: function() {
      // Reset filter button
      $(document).on('click', '.reset-filters', function(e) {
        e.preventDefault();
        
        // Reset price slider
        const $priceSlider = $('.price_slider');
        if ($priceSlider.length) {
          const $minPrice = $('.price_slider_amount #min_price');
          const $maxPrice = $('.price_slider_amount #max_price');
          const minPrice = parseFloat($minPrice.data('min'));
          const maxPrice = parseFloat($maxPrice.data('max'));
          
          $minPrice.val(minPrice);
          $maxPrice.val(maxPrice);
          $priceSlider.slider('values', [minPrice, maxPrice]);
          
          $('.price_slider_amount .price_label .from').text(
            aqualuxe_advanced_filter.currency_symbol + minPrice
          );
          $('.price_slider_amount .price_label .to').text(
            aqualuxe_advanced_filter.currency_symbol + maxPrice
          );
        }
        
        // Reset checkboxes
        $('.widget_layered_nav input[type="checkbox"], .widget_product_categories input[type="checkbox"], .widget_rating_filter input[type="checkbox"]').prop('checked', false);
        
        // Reset swatches
        $('.swatch-filter').removeClass('active');
        
        // Reset sorting
        $('.woocommerce-ordering select.orderby').val($('.woocommerce-ordering select.orderby option:first').val());
        
        // Apply filters
        AquaLuxeAdvancedFilter.applyFilters();
      });
    },

    /**
     * Apply filters
     * 
     * @param {number} page - Page number
     */
    applyFilters: function(page = 1) {
      // Check if AJAX filter is enabled
      if (!aqualuxe_advanced_filter.ajax_filter) {
        return;
      }
      
      // Get filter values
      const filters = AquaLuxeAdvancedFilter.getFilterValues();
      filters.page = page;
      
      // Show loading
      $('.shop-content').addClass('loading');
      $('.shop-content').append('<div class="shop-loading">' + aqualuxe_advanced_filter.i18n_loading + '</div>');
      
      // Send AJAX request
      $.ajax({
        url: aqualuxe_advanced_filter.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_ajax_filter',
          nonce: aqualuxe_advanced_filter.nonce,
          ...filters
        },
        success: function(response) {
          if (response.success) {
            // Update products
            $('.shop-content').html(response.data.html);
            
            // Update URL
            AquaLuxeAdvancedFilter.updateUrl(filters);
            
            // Trigger event
            $(document.body).trigger('aqualuxe_ajax_filter_updated', [response.data]);
          }
        },
        error: function() {
          // Remove loading
          $('.shop-content').removeClass('loading');
          $('.shop-loading').remove();
        },
        complete: function() {
          // Remove loading
          $('.shop-content').removeClass('loading');
          $('.shop-loading').remove();
        }
      });
    },

    /**
     * Get filter values
     * 
     * @return {Object} Filter values
     */
    getFilterValues: function() {
      const filters = {};
      
      // Price filter
      const $minPrice = $('.price_slider_amount #min_price');
      const $maxPrice = $('.price_slider_amount #max_price');
      if ($minPrice.length && $maxPrice.length) {
        filters.min_price = $minPrice.val();
        filters.max_price = $maxPrice.val();
      }
      
      // Attribute filter
      const attributes = {};
      $('.widget_layered_nav').each(function() {
        const $widget = $(this);
        const attribute = $widget.data('attribute');
        
        if (attribute) {
          const values = [];
          $widget.find('input[type="checkbox"]:checked').each(function() {
            values.push($(this).val());
          });
          
          if (values.length) {
            attributes[attribute] = values;
          }
        }
      });
      
      if (Object.keys(attributes).length) {
        filters.attributes = attributes;
      }
      
      // Category filter
      const categories = [];
      $('.widget_product_categories input[type="checkbox"]:checked').each(function() {
        categories.push($(this).val());
      });
      
      if (categories.length) {
        filters.categories = categories;
      }
      
      // Rating filter
      const ratings = [];
      $('.widget_rating_filter input[type="checkbox"]:checked').each(function() {
        ratings.push($(this).val());
      });
      
      if (ratings.length) {
        filters.rating = ratings;
      }
      
      // Sorting
      const orderby = $('.woocommerce-ordering select.orderby').val();
      if (orderby) {
        filters.orderby = orderby;
      }
      
      // Search
      const search = $('.woocommerce-products-header').data('search');
      if (search) {
        filters.s = search;
      }
      
      return filters;
    },

    /**
     * Update URL
     * 
     * @param {Object} filters - Filter values
     */
    updateUrl: function(filters) {
      // Check if browser supports history API
      if (!window.history || !window.history.pushState) {
        return;
      }
      
      // Get current URL
      const url = new URL(window.location.href);
      
      // Clear existing parameters
      url.searchParams.delete('min_price');
      url.searchParams.delete('max_price');
      url.searchParams.delete('paged');
      url.searchParams.delete('orderby');
      
      // Remove attribute parameters
      for (const key of url.searchParams.keys()) {
        if (key.startsWith('filter_')) {
          url.searchParams.delete(key);
        }
      }
      
      // Add price parameters
      if (filters.min_price) {
        url.searchParams.set('min_price', filters.min_price);
      }
      
      if (filters.max_price) {
        url.searchParams.set('max_price', filters.max_price);
      }
      
      // Add attribute parameters
      if (filters.attributes) {
        for (const [attribute, values] of Object.entries(filters.attributes)) {
          url.searchParams.set('filter_' + attribute, values.join(','));
        }
      }
      
      // Add category parameters
      if (filters.categories) {
        url.searchParams.set('product_cat', filters.categories.join(','));
      }
      
      // Add rating parameters
      if (filters.rating) {
        url.searchParams.set('rating_filter', filters.rating.join(','));
      }
      
      // Add sorting parameter
      if (filters.orderby) {
        url.searchParams.set('orderby', filters.orderby);
      }
      
      // Add page parameter
      if (filters.page && filters.page > 1) {
        url.searchParams.set('paged', filters.page);
      }
      
      // Update URL
      window.history.pushState({}, '', url.toString());
    }
  };

  // Initialize on document ready
  $(document).ready(function() {
    AquaLuxeAdvancedFilter.init();
  });

  // Export to global scope
  window.AquaLuxeAdvancedFilter = AquaLuxeAdvancedFilter;

})(jQuery);