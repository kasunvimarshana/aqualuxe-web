/**
 * AquaLuxe Main JS
 * Main JavaScript functionality for the theme
 */

(function($) {
  'use strict';
  
  // Global variables
  const aqualuxe = {
    // Cache DOM elements
    dom: {
      window: $(window),
      document: $(document),
      body: $('body'),
      header: $('#site-header'),
      footer: $('#site-footer'),
      backToTop: $('.back-to-top'),
      searchToggle: $('.search-toggle'),
      searchForm: $('.search-form'),
      filterToggle: $('.filter-toggle'),
      filterSidebar: $('.filter-sidebar'),
      accordion: $('.accordion'),
      tabs: $('.tabs'),
      sliders: $('.slider'),
      countdowns: $('.countdown'),
      animations: $('[data-animation]')
    },
    
    // Check if WooCommerce is active
    isWooCommerceActive: function() {
      return typeof woocommerce_params !== 'undefined';
    },
    
    // Initialize all functions
    init: function() {
      // Dark mode is handled by dark-mode.js
      this.backToTop();
      
      // Only initialize WooCommerce enhancements if WooCommerce is active
      // WooCommerce specific functionality is now moved to woocommerce.js
      
      this.searchToggle();
      this.filterToggle();
      this.accordion();
      this.tabs();
      this.initSliders();
      this.countdowns();
      this.animations();
      this.ajaxFilters();
    },
    
    // Back to top button
    backToTop: function() {
      const { window, backToTop } = this.dom;
      
      if (!backToTop.length) return;
      
      // Show/hide button based on scroll position
      window.on('scroll', function() {
        if (window.scrollTop() > 300) {
          backToTop.addClass('visible');
        } else {
          backToTop.removeClass('visible');
        }
      });
      
      // Smooth scroll to top
      backToTop.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 500);
      });
    },
    
    // Search toggle functionality
    searchToggle: function() {
      const { searchToggle, searchForm, body } = this.dom;
      
      if (!searchToggle.length || !searchForm.length) return;
      
      searchToggle.on('click', function(e) {
        e.preventDefault();
        
        const isOpen = searchForm.hasClass('open');
        
        if (isOpen) {
          searchForm.removeClass('open');
          searchToggle.attr('aria-expanded', 'false');
          body.removeClass('search-open');
        } else {
          searchForm.addClass('open');
          searchToggle.attr('aria-expanded', 'true');
          body.addClass('search-open');
          setTimeout(function() {
            searchForm.find('input[type="search"]').focus();
          }, 100);
        }
      });
      
      // Close search on click outside
      $(document).on('click', function(e) {
        if (searchForm.hasClass('open') && 
            !searchForm.is(e.target) && 
            searchForm.has(e.target).length === 0 && 
            !searchToggle.is(e.target) && 
            searchToggle.has(e.target).length === 0) {
          searchForm.removeClass('open');
          searchToggle.attr('aria-expanded', 'false');
          body.removeClass('search-open');
        }
      });
      
      // Close search on ESC key
      $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && searchForm.hasClass('open')) {
          searchForm.removeClass('open');
          searchToggle.attr('aria-expanded', 'false');
          body.removeClass('search-open');
          searchToggle.focus();
        }
      });
    },
    
    // Filter sidebar toggle
    filterToggle: function() {
      const { filterToggle, filterSidebar, body } = this.dom;
      
      if (!filterToggle.length || !filterSidebar.length) return;
      
      filterToggle.on('click', function(e) {
        e.preventDefault();
        
        const isOpen = filterSidebar.hasClass('open');
        
        if (isOpen) {
          filterSidebar.removeClass('open');
          filterToggle.attr('aria-expanded', 'false');
          body.removeClass('filters-open');
        } else {
          filterSidebar.addClass('open');
          filterToggle.attr('aria-expanded', 'true');
          body.addClass('filters-open');
        }
      });
      
      // Close filters on click outside
      $(document).on('click', function(e) {
        if (filterSidebar.hasClass('open') && 
            !filterSidebar.is(e.target) && 
            filterSidebar.has(e.target).length === 0 && 
            !filterToggle.is(e.target) && 
            filterToggle.has(e.target).length === 0) {
          filterSidebar.removeClass('open');
          filterToggle.attr('aria-expanded', 'false');
          body.removeClass('filters-open');
        }
      });
      
      // Close filters on ESC key
      $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && filterSidebar.hasClass('open')) {
          filterSidebar.removeClass('open');
          filterToggle.attr('aria-expanded', 'false');
          body.removeClass('filters-open');
          filterToggle.focus();
        }
      });
    },
    
    // Accordion functionality
    accordion: function() {
      const { accordion } = this.dom;
      
      if (!accordion.length) return;
      
      accordion.each(function() {
        const $accordion = $(this);
        const $items = $accordion.find('.accordion-item');
        const $headers = $accordion.find('.accordion-header');
        const isMultiple = $accordion.data('multiple') === true;
        
        // Set initial state
        $items.each(function() {
          const $item = $(this);
          const $header = $item.find('.accordion-header');
          const $content = $item.find('.accordion-content');
          const isActive = $item.hasClass('active');
          
          $header.attr('aria-expanded', isActive);
          if (!isActive) {
            $content.hide();
          }
        });
        
        // Handle click events
        $headers.on('click', function() {
          const $header = $(this);
          const $item = $header.closest('.accordion-item');
          const $content = $item.find('.accordion-content');
          const isActive = $item.hasClass('active');
          
          // Close other items if not multiple
          if (!isMultiple && !isActive) {
            $items.not($item).removeClass('active');
            $items.not($item).find('.accordion-header').attr('aria-expanded', false);
            $items.not($item).find('.accordion-content').slideUp(300);
          }
          
          // Toggle current item
          $item.toggleClass('active');
          $header.attr('aria-expanded', !isActive);
          
          if (isActive) {
            $content.slideUp(300);
          } else {
            $content.slideDown(300);
          }
        });
      });
    },
    
    // Tabs functionality
    tabs: function() {
      const { tabs } = this.dom;
      
      if (!tabs.length) return;
      
      tabs.each(function() {
        const $tabs = $(this);
        const $tabList = $tabs.find('.tabs-nav');
        const $tabButtons = $tabList.find('.tab-button');
        const $tabPanels = $tabs.find('.tab-panel');
        
        // Set ARIA attributes
        $tabList.attr('role', 'tablist');
        $tabButtons.attr('role', 'tab');
        $tabPanels.attr('role', 'tabpanel');
        
        // Set initial state
        $tabButtons.each(function(index) {
          const $button = $(this);
          const $panel = $tabPanels.eq(index);
          const id = `tab-${$tabs.index()}-${index}`;
          const panelId = `panel-${$tabs.index()}-${index}`;
          
          $button.attr({
            'id': id,
            'aria-controls': panelId,
            'aria-selected': $button.hasClass('active'),
            'tabindex': $button.hasClass('active') ? 0 : -1
          });
          
          $panel.attr({
            'id': panelId,
            'aria-labelledby': id
          });
          
          if (!$button.hasClass('active')) {
            $panel.hide();
          }
        });
        
        // Handle click events
        $tabButtons.on('click', function() {
          const $button = $(this);
          const index = $tabButtons.index($button);
          
          // Deactivate all tabs
          $tabButtons.removeClass('active');
          $tabButtons.attr('aria-selected', false);
          $tabButtons.attr('tabindex', -1);
          $tabPanels.hide();
          
          // Activate current tab
          $button.addClass('active');
          $button.attr('aria-selected', true);
          $button.attr('tabindex', 0);
          $tabPanels.eq(index).show();
          
          // Focus the button
          $button.focus();
        });
        
        // Handle keyboard navigation
        $tabButtons.on('keydown', function(e) {
          const $button = $(this);
          const index = $tabButtons.index($button);
          let newIndex;
          
          switch (e.key) {
            case 'ArrowLeft':
            case 'ArrowUp':
              e.preventDefault();
              newIndex = index - 1;
              if (newIndex < 0) newIndex = $tabButtons.length - 1;
              $tabButtons.eq(newIndex).click();
              break;
            case 'ArrowRight':
            case 'ArrowDown':
              e.preventDefault();
              newIndex = index + 1;
              if (newIndex >= $tabButtons.length) newIndex = 0;
              $tabButtons.eq(newIndex).click();
              break;
            case 'Home':
              e.preventDefault();
              $tabButtons.first().click();
              break;
            case 'End':
              e.preventDefault();
              $tabButtons.last().click();
              break;
          }
        });
      });
    },
    
    // Initialize sliders
    initSliders: function() {
      const { sliders } = this.dom;
      
      if (!sliders.length || typeof Swiper === 'undefined') return;
      
      sliders.each(function() {
        const $slider = $(this);
        const type = $slider.data('slider-type') || 'default';
        const options = $slider.data('slider-options') || {};
        
        // Default options
        const defaultOptions = {
          loop: true,
          autoplay: {
            delay: 5000,
            disableOnInteraction: false
          },
          pagination: {
            el: $slider.find('.swiper-pagination')[0],
            clickable: true
          },
          navigation: {
            nextEl: $slider.find('.swiper-button-next')[0],
            prevEl: $slider.find('.swiper-button-prev')[0]
          }
        };
        
        // Specific slider types
        let sliderOptions = {};
        
        switch (type) {
          case 'hero':
            sliderOptions = {
              effect: 'fade',
              speed: 1000,
              autoplay: {
                delay: 7000
              }
            };
            break;
          case 'products':
            sliderOptions = {
              slidesPerView: 1,
              spaceBetween: 20,
              breakpoints: {
                640: {
                  slidesPerView: 2
                },
                768: {
                  slidesPerView: 3
                },
                1024: {
                  slidesPerView: 4
                }
              }
            };
            break;
          case 'testimonials':
            sliderOptions = {
              effect: 'coverflow',
              slidesPerView: 1,
              centeredSlides: true,
              coverflowEffect: {
                rotate: 0,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: false
              },
              breakpoints: {
                768: {
                  slidesPerView: 3
                }
              }
            };
            break;
          case 'gallery':
            sliderOptions = {
              effect: 'cube',
              grabCursor: true,
              cubeEffect: {
                shadow: true,
                slideShadows: true,
                shadowOffset: 20,
                shadowScale: 0.94
              }
            };
            break;
        }
        
        // Merge options
        const mergedOptions = $.extend({}, defaultOptions, sliderOptions, options);
        
        // Initialize Swiper
        new Swiper($slider[0], mergedOptions);
      });
    },
    
    // Countdown timers
    countdowns: function() {
      const { countdowns } = this.dom;
      
      if (!countdowns.length) return;
      
      countdowns.each(function() {
        const $countdown = $(this);
        const targetDate = new Date($countdown.data('target-date')).getTime();
        
        if (isNaN(targetDate)) return;
        
        const $days = $countdown.find('.countdown-days .countdown-value');
        const $hours = $countdown.find('.countdown-hours .countdown-value');
        const $minutes = $countdown.find('.countdown-minutes .countdown-value');
        const $seconds = $countdown.find('.countdown-seconds .countdown-value');
        
        // Update countdown every second
        const interval = setInterval(function() {
          const now = new Date().getTime();
          const distance = targetDate - now;
          
          if (distance < 0) {
            clearInterval(interval);
            $countdown.addClass('expired');
            $countdown.trigger('countdown:expired');
            return;
          }
          
          const days = Math.floor(distance / (1000 * 60 * 60 * 24));
          const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          const seconds = Math.floor((distance % (1000 * 60)) / 1000);
          
          if ($days.length) $days.text(days.toString().padStart(2, '0'));
          if ($hours.length) $hours.text(hours.toString().padStart(2, '0'));
          if ($minutes.length) $minutes.text(minutes.toString().padStart(2, '0'));
          if ($seconds.length) $seconds.text(seconds.toString().padStart(2, '0'));
          
          $countdown.trigger('countdown:update', [days, hours, minutes, seconds]);
        }, 1000);
        
        // Store interval ID for cleanup
        $countdown.data('interval-id', interval);
      });
      
      // Clean up intervals on page unload
      $(window).on('beforeunload', function() {
        countdowns.each(function() {
          const intervalId = $(this).data('interval-id');
          if (intervalId) clearInterval(intervalId);
        });
      });
    },
    
    // Scroll animations
    animations: function() {
      const { animations, window } = this.dom;
      
      if (!animations.length) return;
      
      // Check if element is in viewport
      function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
          rect.top <= (window.height() * 0.8) &&
          rect.bottom >= 0
        );
      }
      
      // Animate elements in viewport
      function animateElements() {
        animations.each(function() {
          const $element = $(this);
          
          if (isInViewport(this) && !$element.hasClass('animated')) {
            const animation = $element.data('animation');
            const delay = $element.data('delay') || 0;
            
            setTimeout(function() {
              $element.addClass('animated ' + animation);
              $element.trigger('animation:started');
              
              // Remove animation class after it completes
              const animationDuration = parseFloat(getComputedStyle($element[0]).animationDuration) * 1000;
              setTimeout(function() {
                $element.trigger('animation:completed');
              }, animationDuration);
            }, delay);
          }
        });
      }
      
      // Run on page load and scroll
      animateElements();
      window.on('scroll resize', animateElements);
    },
    
    // AJAX filters for products and posts
    ajaxFilters: function() {
      const { body } = this.dom;
      
      // Post filters
      $('.post-filters').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $container = $($form.data('target') || '.posts');
        const formData = $form.serialize();
        
        // Show loading state
        body.addClass('loading');
        $container.addClass('loading');
        
        // Fetch filtered posts via AJAX
        $.ajax({
          url: aqualuxeSettings.ajaxUrl,
          type: 'POST',
          data: formData + '&action=aqualuxe_filter_posts&nonce=' + aqualuxeSettings.nonce,
          success: function(response) {
            // Update posts container
            $container.html(response);
            
            // Update URL with filter parameters
            const newUrl = window.location.pathname + '?' + formData;
            history.pushState({}, '', newUrl);
            
            // Remove loading state
            body.removeClass('loading');
            $container.removeClass('loading');
            
            // Trigger custom event
            $(document).trigger('postsFiltered');
          },
          error: function() {
            body.removeClass('loading');
            $container.removeClass('loading');
            console.error('Error filtering posts.');
          }
        });
      });
      
      // Product filters - only initialize if WooCommerce is active
      if (this.isWooCommerceActive()) {
        $('.product-filters').on('submit', function(e) {
          e.preventDefault();
          
          const $form = $(this);
          const $container = $($form.data('target') || '.products');
          const formData = $form.serialize();
          
          // Show loading state
          body.addClass('loading');
          $container.addClass('loading');
          
          // Fetch filtered products via AJAX
          $.ajax({
            url: aqualuxeSettings.ajaxUrl,
            type: 'POST',
            data: formData + '&action=aqualuxe_filter_products&nonce=' + aqualuxeSettings.nonce,
            success: function(response) {
              // Update products container
              $container.html(response);
              
              // Update URL with filter parameters
              const newUrl = window.location.pathname + '?' + formData;
              history.pushState({}, '', newUrl);
              
              // Remove loading state
              body.removeClass('loading');
              $container.removeClass('loading');
              
              // Trigger custom event
              $(document).trigger('productsFiltered');
            },
            error: function() {
              body.removeClass('loading');
              $container.removeClass('loading');
              console.error('Error filtering products.');
            }
          });
        });
      }
    }
  };
  
  // Initialize when DOM is loaded
  $(document).ready(function() {
    aqualuxe.init();
  });
  
})(jQuery);