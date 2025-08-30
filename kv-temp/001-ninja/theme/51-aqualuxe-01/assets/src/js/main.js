/**
 * AquaLuxe Theme Main JavaScript
 *
 * This file contains the main JavaScript functionality for the AquaLuxe theme.
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

  /**
   * Main AquaLuxe object
   */
  const AquaLuxe = {
    /**
     * Initialize the theme
     */
    init: function() {
      // Initialize components
      this.navigation.init();
      this.forms.init();
      this.modals.init();
      this.sliders.init();
      this.misc.init();

      // Initialize WooCommerce components if WooCommerce is active
      if (typeof aqualuxeSettings !== 'undefined' && aqualuxeSettings.isWooCommerceActive) {
        this.woocommerce.init();
      }

      // Trigger init event
      $(document).trigger('aqualuxe:init');
    },

    /**
     * Navigation functionality
     */
    navigation: {
      init: function() {
        this.setupMobileMenu();
        this.setupDropdowns();
        this.setupScrollBehavior();
      },

      /**
       * Setup mobile menu
       */
      setupMobileMenu: function() {
        const $mobileMenuToggle = $('.mobile-navigation-toggle');
        const $mobileMenu = $('.mobile-navigation');
        const $mobileMenuClose = $('.mobile-navigation-close');

        $mobileMenuToggle.on('click', function() {
          $mobileMenu.toggleClass('hidden');
          $('body').toggleClass('mobile-menu-open');
        });

        $mobileMenuClose.on('click', function() {
          $mobileMenu.addClass('hidden');
          $('body').removeClass('mobile-menu-open');
        });

        // Close mobile menu when clicking outside
        $(document).on('click', function(e) {
          if (
            !$(e.target).closest('.mobile-navigation').length &&
            !$(e.target).closest('.mobile-navigation-toggle').length &&
            !$mobileMenu.hasClass('hidden')
          ) {
            $mobileMenu.addClass('hidden');
            $('body').removeClass('mobile-menu-open');
          }
        });
      },

      /**
       * Setup dropdown menus
       */
      setupDropdowns: function() {
        const $dropdownToggles = $('.dropdown-toggle');

        $dropdownToggles.each(function() {
          const $toggle = $(this);
          const $dropdown = $toggle.next('.dropdown-menu');

          $toggle.on('click', function(e) {
            e.preventDefault();
            $dropdown.toggleClass('hidden');
          });

          // Close dropdown when clicking outside
          $(document).on('click', function(e) {
            if (
              !$(e.target).closest($toggle).length &&
              !$(e.target).closest($dropdown).length &&
              !$dropdown.hasClass('hidden')
            ) {
              $dropdown.addClass('hidden');
            }
          });
        });
      },

      /**
       * Setup scroll behavior
       */
      setupScrollBehavior: function() {
        // Smooth scroll for anchor links
        $('a[href^="#"]:not([href="#"])').on('click', function(e) {
          const target = $(this.hash);
          if (target.length) {
            e.preventDefault();
            $('html, body').animate({
              scrollTop: target.offset().top - 100
            }, 500);
          }
        });

        // Sticky header
        const $header = $('.site-header');
        const headerHeight = $header.outerHeight();
        let lastScrollTop = 0;

        $(window).on('scroll', function() {
          const scrollTop = $(this).scrollTop();

          // Add sticky class when scrolling down
          if (scrollTop > headerHeight) {
            $header.addClass('sticky-header');
            $('body').css('padding-top', headerHeight);
          } else {
            $header.removeClass('sticky-header');
            $('body').css('padding-top', 0);
          }

          // Hide/show header when scrolling up/down
          if (scrollTop > lastScrollTop && scrollTop > headerHeight) {
            // Scrolling down
            $header.addClass('header-hidden');
          } else {
            // Scrolling up
            $header.removeClass('header-hidden');
          }

          lastScrollTop = scrollTop;
        });
      }
    },

    /**
     * Forms functionality
     */
    forms: {
      init: function() {
        this.setupValidation();
        this.setupAjaxForms();
      },

      /**
       * Setup form validation
       */
      setupValidation: function() {
        const $forms = $('form.validate');

        $forms.each(function() {
          const $form = $(this);

          $form.on('submit', function(e) {
            let isValid = true;
            const $requiredFields = $form.find('[required]');

            $requiredFields.each(function() {
              const $field = $(this);
              const $formGroup = $field.closest('.form-group');
              const $errorMessage = $formGroup.find('.form-error');

              if (!$field.val()) {
                isValid = false;
                $formGroup.addClass('has-error');
                if ($errorMessage.length) {
                  $errorMessage.text('This field is required.');
                } else {
                  $formGroup.append('<div class="form-error">This field is required.</div>');
                }
              } else {
                $formGroup.removeClass('has-error');
                $errorMessage.remove();
              }
            });

            if (!isValid) {
              e.preventDefault();
            }
          });

          // Clear error messages on input
          $form.find('input, textarea, select').on('input change', function() {
            const $field = $(this);
            const $formGroup = $field.closest('.form-group');
            const $errorMessage = $formGroup.find('.form-error');

            $formGroup.removeClass('has-error');
            $errorMessage.remove();
          });
        });
      },

      /**
       * Setup AJAX forms
       */
      setupAjaxForms: function() {
        const $ajaxForms = $('form.ajax-form');

        $ajaxForms.each(function() {
          const $form = $(this);
          const $submitButton = $form.find('[type="submit"]');
          const $responseContainer = $form.find('.form-response');

          $form.on('submit', function(e) {
            e.preventDefault();

            // Disable submit button and show loading state
            $submitButton.prop('disabled', true).addClass('loading');

            // Clear previous response
            $responseContainer.empty();

            // Get form data
            const formData = new FormData($form[0]);

            // Add action if not present
            if (!formData.has('action')) {
              formData.append('action', $form.data('action') || 'aqualuxe_ajax_form');
            }

            // Add nonce if not present
            if (!formData.has('nonce') && typeof aqualuxeSettings !== 'undefined') {
              formData.append('nonce', aqualuxeSettings.nonce);
            }

            // Send AJAX request
            $.ajax({
              url: typeof aqualuxeSettings !== 'undefined' ? aqualuxeSettings.ajaxUrl : '/wp-admin/admin-ajax.php',
              type: 'POST',
              data: formData,
              processData: false,
              contentType: false,
              success: function(response) {
                // Enable submit button and remove loading state
                $submitButton.prop('disabled', false).removeClass('loading');

                // Handle response
                if (response.success) {
                  // Show success message
                  $responseContainer.html('<div class="alert alert-success">' + response.data.message + '</div>');

                  // Reset form if specified
                  if ($form.data('reset-on-success')) {
                    $form[0].reset();
                  }

                  // Trigger success event
                  $form.trigger('aqualuxe:form:success', [response]);

                  // Redirect if specified
                  if (response.data.redirect) {
                    window.location.href = response.data.redirect;
                  }
                } else {
                  // Show error message
                  $responseContainer.html('<div class="alert alert-danger">' + response.data.message + '</div>');

                  // Trigger error event
                  $form.trigger('aqualuxe:form:error', [response]);
                }
              },
              error: function(xhr, status, error) {
                // Enable submit button and remove loading state
                $submitButton.prop('disabled', false).removeClass('loading');

                // Show error message
                $responseContainer.html('<div class="alert alert-danger">An error occurred. Please try again later.</div>');

                // Trigger error event
                $form.trigger('aqualuxe:form:error', [xhr, status, error]);
              }
            });
          });
        });
      }
    },

    /**
     * Modals functionality
     */
    modals: {
      init: function() {
        this.setupModals();
      },

      /**
       * Setup modals
       */
      setupModals: function() {
        const $modalTriggers = $('[data-modal]');
        const $modals = $('.modal');
        const $modalCloses = $('.modal-close');

        // Open modal when clicking on trigger
        $modalTriggers.on('click', function(e) {
          e.preventDefault();
          const modalId = $(this).data('modal');
          const $modal = $('#' + modalId);

          if ($modal.length) {
            $modal.addClass('active');
            $('body').addClass('modal-open');
          }
        });

        // Close modal when clicking on close button
        $modalCloses.on('click', function() {
          const $modal = $(this).closest('.modal');
          $modal.removeClass('active');
          $('body').removeClass('modal-open');
        });

        // Close modal when clicking on backdrop
        $modals.on('click', function(e) {
          if ($(e.target).hasClass('modal')) {
            $(this).removeClass('active');
            $('body').removeClass('modal-open');
          }
        });

        // Close modal when pressing ESC key
        $(document).on('keydown', function(e) {
          if (e.key === 'Escape' && $('.modal.active').length) {
            $('.modal.active').removeClass('active');
            $('body').removeClass('modal-open');
          }
        });
      }
    },

    /**
     * Sliders functionality
     */
    sliders: {
      init: function() {
        this.setupSliders();
      },

      /**
       * Setup sliders
       */
      setupSliders: function() {
        // Check if Swiper is available
        if (typeof Swiper === 'undefined') {
          return;
        }

        // Hero slider
        if ($('.hero-slider').length) {
          new Swiper('.hero-slider', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            autoplay: {
              delay: 5000,
              disableOnInteraction: false,
            },
            pagination: {
              el: '.hero-slider-pagination',
              clickable: true,
            },
            navigation: {
              nextEl: '.hero-slider-next',
              prevEl: '.hero-slider-prev',
            },
          });
        }

        // Testimonials slider
        if ($('.testimonials-slider').length) {
          new Swiper('.testimonials-slider', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
              delay: 5000,
              disableOnInteraction: false,
            },
            pagination: {
              el: '.testimonials-slider-pagination',
              clickable: true,
            },
            breakpoints: {
              640: {
                slidesPerView: 2,
              },
              1024: {
                slidesPerView: 3,
              },
            },
          });
        }

        // Products slider
        if ($('.products-slider').length) {
          new Swiper('.products-slider', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            pagination: {
              el: '.products-slider-pagination',
              clickable: true,
            },
            navigation: {
              nextEl: '.products-slider-next',
              prevEl: '.products-slider-prev',
            },
            breakpoints: {
              640: {
                slidesPerView: 2,
              },
              1024: {
                slidesPerView: 4,
              },
            },
          });
        }
      }
    },

    /**
     * Miscellaneous functionality
     */
    misc: {
      init: function() {
        this.setupAOS();
        this.setupTooltips();
        this.setupAccordions();
        this.setupTabs();
        this.setupCounters();
        this.setupLanguageSwitcher();
        this.setupCurrencySwitcher();
      },

      /**
       * Setup AOS (Animate On Scroll)
       */
      setupAOS: function() {
        // Check if AOS is available
        if (typeof AOS !== 'undefined') {
          AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
          });
        }
      },

      /**
       * Setup tooltips
       */
      setupTooltips: function() {
        const $tooltips = $('[data-tooltip]');

        $tooltips.each(function() {
          const $tooltip = $(this);
          const tooltipText = $tooltip.data('tooltip');
          const tooltipPosition = $tooltip.data('tooltip-position') || 'top';

          // Create tooltip element
          const $tooltipElement = $('<div class="tooltip" role="tooltip">' + tooltipText + '</div>');
          $('body').append($tooltipElement);

          // Show tooltip on hover
          $tooltip.on('mouseenter', function() {
            const tooltipWidth = $tooltipElement.outerWidth();
            const tooltipHeight = $tooltipElement.outerHeight();
            const elementWidth = $tooltip.outerWidth();
            const elementHeight = $tooltip.outerHeight();
            const elementOffset = $tooltip.offset();

            let top, left;

            switch (tooltipPosition) {
              case 'top':
                top = elementOffset.top - tooltipHeight - 10;
                left = elementOffset.left + (elementWidth / 2) - (tooltipWidth / 2);
                break;
              case 'bottom':
                top = elementOffset.top + elementHeight + 10;
                left = elementOffset.left + (elementWidth / 2) - (tooltipWidth / 2);
                break;
              case 'left':
                top = elementOffset.top + (elementHeight / 2) - (tooltipHeight / 2);
                left = elementOffset.left - tooltipWidth - 10;
                break;
              case 'right':
                top = elementOffset.top + (elementHeight / 2) - (tooltipHeight / 2);
                left = elementOffset.left + elementWidth + 10;
                break;
            }

            $tooltipElement.css({
              top: top + 'px',
              left: left + 'px',
            }).addClass('visible');
          });

          // Hide tooltip on mouse leave
          $tooltip.on('mouseleave', function() {
            $tooltipElement.removeClass('visible');
          });
        });
      },

      /**
       * Setup accordions
       */
      setupAccordions: function() {
        const $accordions = $('.accordion');

        $accordions.each(function() {
          const $accordion = $(this);
          const $items = $accordion.find('.accordion-item');
          const $headers = $accordion.find('.accordion-header');
          const isMultiple = $accordion.data('multiple') === true;

          $headers.on('click', function() {
            const $header = $(this);
            const $item = $header.closest('.accordion-item');
            const $content = $item.find('.accordion-content');
            const isActive = $item.hasClass('active');

            // Close other items if not multiple
            if (!isMultiple && !isActive) {
              $items.removeClass('active');
              $items.find('.accordion-content').slideUp(300);
            }

            // Toggle current item
            if (isActive) {
              $item.removeClass('active');
              $content.slideUp(300);
            } else {
              $item.addClass('active');
              $content.slideDown(300);
            }
          });

          // Open default active items
          $items.filter('.active').find('.accordion-content').show();
        });
      },

      /**
       * Setup tabs
       */
      setupTabs: function() {
        const $tabContainers = $('.tabs');

        $tabContainers.each(function() {
          const $container = $(this);
          const $tabLinks = $container.find('.tab-link');
          const $tabContents = $container.find('.tab-content');

          $tabLinks.on('click', function(e) {
            e.preventDefault();
            const tabId = $(this).attr('href');

            // Update active tab link
            $tabLinks.removeClass('active');
            $(this).addClass('active');

            // Show active tab content
            $tabContents.removeClass('active');
            $(tabId).addClass('active');
          });

          // Activate first tab by default if none is active
          if (!$tabLinks.filter('.active').length) {
            $tabLinks.first().trigger('click');
          }
        });
      },

      /**
       * Setup counters
       */
      setupCounters: function() {
        const $counters = $('.counter');

        if ($counters.length) {
          // Check if CountUp.js is available
          if (typeof CountUp === 'undefined') {
            return;
          }

          // Setup Intersection Observer
          const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
              if (entry.isIntersecting) {
                const $counter = $(entry.target);
                const value = parseFloat($counter.data('count'));
                const duration = parseInt($counter.data('duration')) || 2;
                const decimals = parseInt($counter.data('decimals')) || 0;
                const prefix = $counter.data('prefix') || '';
                const suffix = $counter.data('suffix') || '';

                const countUp = new CountUp(entry.target, value, {
                  duration: duration,
                  decimalPlaces: decimals,
                  prefix: prefix,
                  suffix: suffix,
                });

                countUp.start();
                observer.unobserve(entry.target);
              }
            });
          }, {
            threshold: 0.1
          });

          $counters.each(function() {
            observer.observe(this);
          });
        }
      },

      /**
       * Setup language switcher
       */
      setupLanguageSwitcher: function() {
        const $languageSwitcher = $('.language-switcher');
        const $languageSwitcherButton = $languageSwitcher.find('.language-switcher-button');
        const $languageSwitcherDropdown = $languageSwitcher.find('.language-switcher-dropdown');
        const $languageSwitcherItems = $languageSwitcher.find('.language-switcher-item');

        $languageSwitcherButton.on('click', function(e) {
          e.preventDefault();
          $languageSwitcherDropdown.toggleClass('hidden');
        });

        $languageSwitcherItems.on('click', function(e) {
          e.preventDefault();
          const language = $(this).data('language');
          const currentUrl = window.location.href;

          // Trigger language change event
          $(document).trigger('aqualuxe:language:change', [language]);

          // Redirect to language URL if provided
          const languageUrl = $(this).attr('href');
          if (languageUrl) {
            window.location.href = languageUrl;
          }
        });

        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
          if (
            !$(e.target).closest($languageSwitcherButton).length &&
            !$(e.target).closest($languageSwitcherDropdown).length &&
            !$languageSwitcherDropdown.hasClass('hidden')
          ) {
            $languageSwitcherDropdown.addClass('hidden');
          }
        });
      },

      /**
       * Setup currency switcher
       */
      setupCurrencySwitcher: function() {
        const $currencySwitcher = $('.currency-switcher');
        const $currencySwitcherButton = $currencySwitcher.find('.currency-switcher-button');
        const $currencySwitcherDropdown = $currencySwitcher.find('.currency-switcher-dropdown');
        const $currencySwitcherItems = $currencySwitcher.find('.currency-switcher-item');

        $currencySwitcherButton.on('click', function(e) {
          e.preventDefault();
          $currencySwitcherDropdown.toggleClass('hidden');
        });

        $currencySwitcherItems.on('click', function(e) {
          e.preventDefault();
          const currency = $(this).data('currency');

          // Trigger currency change event
          $(document).trigger('aqualuxe:currency:change', [currency]);

          // Set currency cookie
          document.cookie = 'aqualuxe_currency=' + currency + '; path=/; max-age=31536000';

          // Reload page to apply currency
          window.location.reload();
        });

        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
          if (
            !$(e.target).closest($currencySwitcherButton).length &&
            !$(e.target).closest($currencySwitcherDropdown).length &&
            !$currencySwitcherDropdown.hasClass('hidden')
          ) {
            $currencySwitcherDropdown.addClass('hidden');
          }
        });
      }
    },

    /**
     * WooCommerce functionality
     */
    woocommerce: {
      init: function() {
        // Initialize WooCommerce components
        // Note: This is just a placeholder. The actual WooCommerce functionality
        // is implemented in the woocommerce.js file.
      }
    }
  };

  // Initialize AquaLuxe when DOM is ready
  $(document).ready(function() {
    AquaLuxe.init();
  });

  // Expose AquaLuxe to global scope
  window.AquaLuxe = AquaLuxe;

})(jQuery);