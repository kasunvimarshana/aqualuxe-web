/**
 * Main JavaScript file for AquaLuxe theme
 */

// Import dependencies
import Alpine from 'alpinejs';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// AquaLuxe namespace
window.AquaLuxe = window.AquaLuxe || {};

(function($) {
  'use strict';
  
  // DOM ready
  $(function() {
    AquaLuxe.init();
  });
  
  /**
   * Initialize theme functionality
   */
  AquaLuxe.init = function() {
    this.setupNavigation();
    this.setupHeaderActions();
    this.setupScrollEffects();
    this.setupForms();
    this.setupAccessibility();
    this.setupMisc();
  };
  
  /**
   * Setup navigation functionality
   */
  AquaLuxe.setupNavigation = function() {
    const $menuToggle = $('.menu-toggle');
    const $primaryMenu = $('#primary-menu');
    
    // Mobile menu toggle
    $menuToggle.on('click', function() {
      const isExpanded = $(this).attr('aria-expanded') === 'true';
      $(this).attr('aria-expanded', !isExpanded);
      $primaryMenu.toggleClass('active');
      
      if (!isExpanded) {
        $(this).addClass('is-active');
      } else {
        $(this).removeClass('is-active');
      }
    });
    
    // Sub-menu accessibility
    $('.menu-item-has-children > a').on('click', function(e) {
      const $parent = $(this).parent();
      
      if (window.innerWidth < 992) {
        if (!$parent.hasClass('sub-menu-open')) {
          e.preventDefault();
          $parent.addClass('sub-menu-open');
          $parent.siblings('.sub-menu-open').removeClass('sub-menu-open');
        }
      }
    });
    
    // Close menu when clicking outside
    $(document).on('click', function(e) {
      if (!$(e.target).closest('.main-navigation').length) {
        $('.sub-menu-open').removeClass('sub-menu-open');
      }
    });
    
    // Add dropdown toggles to mobile menu
    $('.menu-item-has-children').each(function() {
      const $toggle = $('<button class="dropdown-toggle" aria-expanded="false"><span class="screen-reader-text">Expand submenu</span></button>');
      
      $toggle.on('click', function(e) {
        e.preventDefault();
        const $parent = $(this).parent();
        const isExpanded = $(this).attr('aria-expanded') === 'true';
        
        $(this).attr('aria-expanded', !isExpanded);
        $parent.toggleClass('sub-menu-open');
      });
      
      $(this).append($toggle);
    });
    
    // Handle window resize
    let resizeTimer;
    $(window).on('resize', function() {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function() {
        if (window.innerWidth >= 992) {
          $('.sub-menu-open').removeClass('sub-menu-open');
          $menuToggle.attr('aria-expanded', 'false').removeClass('is-active');
          $primaryMenu.removeClass('active');
        }
      }, 250);
    });
  };
  
  /**
   * Setup header actions functionality
   */
  AquaLuxe.setupHeaderActions = function() {
    // Search toggle
    const $searchToggle = $('.header-search-toggle');
    const $searchDropdown = $('.header-search-dropdown');
    
    $searchToggle.on('click', function() {
      const isExpanded = $(this).attr('aria-expanded') === 'true';
      $(this).attr('aria-expanded', !isExpanded);
      $searchDropdown.toggleClass('active');
      
      if (!isExpanded) {
        setTimeout(function() {
          $searchDropdown.find('input[type="search"]').focus();
        }, 100);
      }
    });
    
    // Close search when clicking outside
    $(document).on('click', function(e) {
      if (!$(e.target).closest('.header-search').length) {
        $searchToggle.attr('aria-expanded', 'false');
        $searchDropdown.removeClass('active');
      }
    });
    
    // Account dropdown
    const $accountLink = $('.header-account-link');
    const $accountDropdown = $('.header-account-dropdown');
    
    if ($accountDropdown.length) {
      $accountLink.on('click', function(e) {
        if (!$(this).parent().hasClass('logged-in')) {
          e.preventDefault();
          $accountDropdown.toggleClass('active');
        }
      });
      
      // Close account dropdown when clicking outside
      $(document).on('click', function(e) {
        if (!$(e.target).closest('.header-account').length) {
          $accountDropdown.removeClass('active');
        }
      });
    }
    
    // Cart dropdown
    const $cartLink = $('.header-cart-link');
    const $cartDropdown = $('.header-cart-dropdown');
    
    if ($cartDropdown.length) {
      $cartLink.on('click', function(e) {
        e.preventDefault();
        $cartDropdown.toggleClass('active');
      });
      
      // Close cart dropdown when clicking outside
      $(document).on('click', function(e) {
        if (!$(e.target).closest('.header-cart').length) {
          $cartDropdown.removeClass('active');
        }
      });
      
      // Update cart count via AJAX
      $(document.body).on('added_to_cart removed_from_cart', function(e, fragments) {
        if (fragments && fragments['.header-cart-count']) {
          $('.header-cart-count').html(fragments['.header-cart-count']);
        }
      });
    }
  };
  
  /**
   * Setup scroll effects
   */
  AquaLuxe.setupScrollEffects = function() {
    const $header = $('.site-header');
    let lastScrollTop = 0;
    
    // Sticky header
    $(window).on('scroll', function() {
      const scrollTop = $(this).scrollTop();
      
      // Add sticky class when scrolling down
      if (scrollTop > 100) {
        $header.addClass('sticky');
      } else {
        $header.removeClass('sticky');
      }
      
      // Hide/show header when scrolling up/down
      if (scrollTop > lastScrollTop && scrollTop > 200) {
        // Scrolling down
        $header.addClass('header-hidden');
      } else {
        // Scrolling up
        $header.removeClass('header-hidden');
      }
      
      lastScrollTop = scrollTop;
    });
    
    // Smooth scroll for anchor links
    $('a[href^="#"]:not([href="#"])').on('click', function(e) {
      const target = $(this.hash);
      
      if (target.length) {
        e.preventDefault();
        
        $('html, body').animate({
          scrollTop: target.offset().top - 100
        }, 800);
        
        // Update URL hash without jumping
        if (history.pushState) {
          history.pushState(null, null, this.hash);
        }
      }
    });
    
    // Back to top button
    const $backToTop = $('.back-to-top');
    
    if ($backToTop.length) {
      $(window).on('scroll', function() {
        if ($(this).scrollTop() > 300) {
          $backToTop.addClass('show');
        } else {
          $backToTop.removeClass('show');
        }
      });
      
      $backToTop.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 800);
      });
    }
  };
  
  /**
   * Setup form functionality
   */
  AquaLuxe.setupForms = function() {
    // Form validation
    $('form').each(function() {
      const $form = $(this);
      
      $form.on('submit', function(e) {
        let isValid = true;
        
        $form.find('[required]').each(function() {
          if (!$(this).val()) {
            isValid = false;
            $(this).addClass('error');
          } else {
            $(this).removeClass('error');
          }
        });
        
        if (!isValid) {
          e.preventDefault();
          $form.find('.error').first().focus();
        }
      });
      
      // Remove error class on input
      $form.find('input, textarea, select').on('input change', function() {
        $(this).removeClass('error');
      });
    });
    
    // Custom file input
    $('.custom-file-input').each(function() {
      const $input = $(this);
      const $label = $input.next('label');
      const labelVal = $label.html();
      
      $input.on('change', function(e) {
        let fileName = '';
        
        if (this.files && this.files.length > 1) {
          fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
        } else if (e.target.value) {
          fileName = e.target.value.split('\\').pop();
        }
        
        if (fileName) {
          $label.html(fileName);
        } else {
          $label.html(labelVal);
        }
      });
    });
  };
  
  /**
   * Setup accessibility features
   */
  AquaLuxe.setupAccessibility = function() {
    // Skip link focus fix
    $(document).on('ready', function() {
      // Is there a skip link?
      const $skipLink = $('.skip-link');
      
      if ($skipLink.length) {
        // Does the target exist?
        const $target = $($skipLink.attr('href'));
        
        if ($target.length) {
          $skipLink.on('click', function() {
            $target.attr('tabindex', '-1');
            $target.focus();
            
            $target.on('blur focusout', function() {
              $(this).removeAttr('tabindex');
            });
          });
        }
      }
    });
    
    // Focus outline
    $(document).on('keydown', function(e) {
      if (e.key === 'Tab') {
        $('body').addClass('keyboard-navigation');
      }
    });
    
    $(document).on('mousedown', function() {
      $('body').removeClass('keyboard-navigation');
    });
  };
  
  /**
   * Setup miscellaneous functionality
   */
  AquaLuxe.setupMisc = function() {
    // Lazy load images
    if ('loading' in HTMLImageElement.prototype) {
      // Native lazy loading
      const lazyImages = document.querySelectorAll('img[loading="lazy"]');
      lazyImages.forEach(img => {
        img.src = img.dataset.src;
        if (img.dataset.srcset) {
          img.srcset = img.dataset.srcset;
        }
      });
    } else {
      // Fallback for browsers that don't support native lazy loading
      const lazyImageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const lazyImage = entry.target;
            lazyImage.src = lazyImage.dataset.src;
            if (lazyImage.dataset.srcset) {
              lazyImage.srcset = lazyImage.dataset.srcset;
            }
            lazyImage.classList.remove('lazy');
            lazyImageObserver.unobserve(lazyImage);
          }
        });
      });
      
      document.querySelectorAll('img.lazy').forEach(img => {
        lazyImageObserver.observe(img);
      });
    }
    
    // Responsive tables
    $('table').wrap('<div class="table-responsive"></div>');
    
    // External links
    $('a[href^="http"]').not(`a[href*="${window.location.hostname}"]`).attr({
      target: '_blank',
      rel: 'noopener noreferrer'
    });
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').each(function() {
      const $tooltip = $(this);
      
      $tooltip.on('mouseenter focus', function() {
        $tooltip.addClass('tooltip-active');
      }).on('mouseleave blur', function() {
        $tooltip.removeClass('tooltip-active');
      });
    });
  };
  
})(jQuery);