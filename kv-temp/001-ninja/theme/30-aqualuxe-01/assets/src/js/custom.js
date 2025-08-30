/**
 * File custom.js.
 *
 * Custom JavaScript functionality for the AquaLuxe theme.
 */
(function($) {
  'use strict';
  
  // Initialize when DOM is fully loaded
  $(document).ready(function() {
    // Smooth scroll for anchor links
    initSmoothScroll();
    
    // Initialize sliders if Swiper is available
    initSliders();
    
    // Initialize animations
    initAnimations();
    
    // Initialize product quick view
    initQuickView();
    
    // Initialize sticky header
    initStickyHeader();
    
    // Initialize back to top button
    initBackToTop();
    
    // Initialize custom select dropdowns
    initCustomSelects();
  });
  
  /**
   * Initialize smooth scrolling for anchor links
   */
  function initSmoothScroll() {
    $('a[href*="#"]:not([href="#"])').on('click', function(e) {
      if (
        location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') &&
        location.hostname === this.hostname
      ) {
        let target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        
        if (target.length) {
          e.preventDefault();
          $('html, body').animate(
            {
              scrollTop: target.offset().top - 100
            },
            800
          );
          
          // Update URL hash without jumping
          if (history.pushState) {
            history.pushState(null, null, this.hash);
          } else {
            location.hash = this.hash;
          }
          
          return false;
        }
      }
    });
  }
  
  /**
   * Initialize sliders if Swiper is available
   */
  function initSliders() {
    if (typeof Swiper !== 'undefined') {
      // Hero slider
      new Swiper('.hero-slider', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        autoplay: {
          delay: 5000,
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
      
      // Products slider
      new Swiper('.products-slider', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        navigation: {
          nextEl: '.products-slider-next',
          prevEl: '.products-slider-prev',
        },
        breakpoints: {
          640: {
            slidesPerView: 2,
          },
          768: {
            slidesPerView: 3,
          },
          1024: {
            slidesPerView: 4,
          },
        },
      });
      
      // Testimonials slider
      new Swiper('.testimonials-slider', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
          delay: 6000,
        },
        pagination: {
          el: '.testimonials-pagination',
          clickable: true,
        },
        breakpoints: {
          768: {
            slidesPerView: 2,
          },
          1024: {
            slidesPerView: 3,
          },
        },
      });
      
      // Product gallery slider
      const galleryThumbs = new Swiper('.gallery-thumbs', {
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesVisibility: true,
        watchSlidesProgress: true,
      });
      
      new Swiper('.gallery-main', {
        spaceBetween: 0,
        navigation: {
          nextEl: '.gallery-next',
          prevEl: '.gallery-prev',
        },
        thumbs: {
          swiper: galleryThumbs,
        },
      });
    }
  }
  
  /**
   * Initialize animations
   */
  function initAnimations() {
    // Only initialize if IntersectionObserver is supported
    if ('IntersectionObserver' in window) {
      const animatedElements = document.querySelectorAll('.animate-on-scroll');
      
      const animationObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('animated');
            animationObserver.unobserve(entry.target);
          }
        });
      }, {
        rootMargin: '0px 0px -100px 0px'
      });
      
      animatedElements.forEach(element => {
        animationObserver.observe(element);
      });
    } else {
      // Fallback for browsers that don't support IntersectionObserver
      document.querySelectorAll('.animate-on-scroll').forEach(element => {
        element.classList.add('animated');
      });
    }
  }
  
  /**
   * Initialize product quick view
   */
  function initQuickView() {
    $('.quick-view-button').on('click', function(e) {
      e.preventDefault();
      
      const productId = $(this).data('product-id');
      const modal = $('#quick-view-modal');
      const content = modal.find('.quick-view-content');
      
      // Show loading state
      modal.addClass('active');
      content.html('<div class="quick-view-loading">Loading...</div>');
      
      // Fetch product data via AJAX
      $.ajax({
        url: aqualuxeSettings.ajaxUrl,
        type: 'POST',
        data: {
          action: 'aqualuxe_quick_view',
          product_id: productId,
          nonce: aqualuxeSettings.nonce
        },
        success: function(response) {
          content.html(response);
          
          // Initialize product gallery in quick view
          if (typeof Swiper !== 'undefined') {
            const quickViewThumbs = new Swiper('.quick-view-thumbs', {
              spaceBetween: 10,
              slidesPerView: 4,
              freeMode: true,
              watchSlidesVisibility: true,
              watchSlidesProgress: true,
            });
            
            new Swiper('.quick-view-main', {
              spaceBetween: 0,
              thumbs: {
                swiper: quickViewThumbs,
              },
            });
          }
          
          // Initialize quantity buttons
          initQuantityButtons();
        },
        error: function() {
          content.html('<div class="quick-view-error">Error loading product data. Please try again.</div>');
        }
      });
    });
    
    // Close quick view modal
    $(document).on('click', '.quick-view-close, .quick-view-overlay', function(e) {
      e.preventDefault();
      $('#quick-view-modal').removeClass('active');
    });
    
    // Close on ESC key
    $(document).keyup(function(e) {
      if (e.key === 'Escape') {
        $('#quick-view-modal').removeClass('active');
      }
    });
  }
  
  /**
   * Initialize sticky header
   */
  function initStickyHeader() {
    const header = $('.site-header');
    const headerHeight = header.outerHeight();
    let lastScrollTop = 0;
    
    $(window).scroll(function() {
      const scrollTop = $(this).scrollTop();
      
      // Add sticky class when scrolled down
      if (scrollTop > headerHeight) {
        header.addClass('sticky');
        $('body').css('padding-top', headerHeight);
      } else {
        header.removeClass('sticky');
        $('body').css('padding-top', 0);
      }
      
      // Hide/show header on scroll direction
      if (scrollTop > lastScrollTop && scrollTop > headerHeight * 2) {
        // Scrolling down
        header.addClass('header-hidden');
      } else {
        // Scrolling up
        header.removeClass('header-hidden');
      }
      
      lastScrollTop = scrollTop;
    });
  }
  
  /**
   * Initialize back to top button
   */
  function initBackToTop() {
    const backToTop = $('.back-to-top');
    
    $(window).scroll(function() {
      if ($(this).scrollTop() > 300) {
        backToTop.addClass('active');
      } else {
        backToTop.removeClass('active');
      }
    });
    
    backToTop.on('click', function(e) {
      e.preventDefault();
      $('html, body').animate({scrollTop: 0}, 800);
    });
  }
  
  /**
   * Initialize custom select dropdowns
   */
  function initCustomSelects() {
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
        const customOption = $('<div class="custom-select-option" data-value="' + option.val() + '">' + option.text() + '</div>');
        
        if (option.is(':selected')) {
          customOption.addClass('selected');
        }
        
        customOption.on('click', function() {
          select.val($(this).data('value'));
          customSelectTrigger.text($(this).text());
          customSelectOptions.find('.selected').removeClass('selected');
          $(this).addClass('selected');
          select.trigger('change');
          customSelect.removeClass('open');
        });
        
        customSelectOptions.append(customOption);
      });
      
      // Build custom select
      customSelect.append(customSelectTrigger);
      customSelect.append(customSelectOptions);
      select.after(customSelect);
      select.hide();
      
      // Toggle custom select
      customSelectTrigger.on('click', function() {
        $('div.custom-select-wrapper.open').not(customSelect).removeClass('open');
        customSelect.toggleClass('open');
      });
      
      // Close custom select when clicking outside
      $(document).on('click', function(e) {
        if (!customSelect.is(e.target) && customSelect.has(e.target).length === 0) {
          customSelect.removeClass('open');
        }
      });
    });
  }
  
  /**
   * Initialize quantity buttons
   */
  function initQuantityButtons() {
    $('.quantity').each(function() {
      const wrapper = $(this);
      const input = wrapper.find('.qty');
      const minValue = parseInt(input.attr('min'), 10) || 1;
      const maxValue = parseInt(input.attr('max'), 10) || 999;
      
      // Add increment/decrement buttons if they don't exist
      if (!wrapper.find('.quantity-button').length) {
        input.before('<button type="button" class="quantity-button quantity-down">-</button>');
        input.after('<button type="button" class="quantity-button quantity-up">+</button>');
      }
      
      // Handle button clicks
      wrapper.on('click', '.quantity-button', function() {
        const currentVal = parseInt(input.val(), 10) || 0;
        
        if ($(this).hasClass('quantity-up')) {
          if (currentVal < maxValue) {
            input.val(currentVal + 1).trigger('change');
          }
        } else {
          if (currentVal > minValue) {
            input.val(currentVal - 1).trigger('change');
          }
        }
      });
    });
  }
  
})(jQuery);