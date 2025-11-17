(function ($) {
  'use strict';

  $(document).ready(function () {
    // Initialize product image gallery
    initProductGallery();

    // Initialize mobile menu
    initMobileMenu();

    // Initialize product filters
    initProductFilters();
  });

  /**
   * Initialize product image gallery
   */
  function initProductGallery() {
    $('.product-images').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: true,
      dots: true,
      adaptiveHeight: true,
      prevArrow:
        '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
      nextArrow:
        '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
    });
  }

  /**
   * Initialize mobile menu
   */
  function initMobileMenu() {
    $('.mobile-menu-toggle').on('click', function () {
      $('.main-navigation').toggleClass('active');
      $(this).toggleClass('active');
    });
  }

  /**
   * Initialize product filters
   */
  function initProductFilters() {
    $('.filter-checkbox').on('change', function () {
      const selectedFilters = [];

      $('.filter-checkbox:checked').each(function () {
        selectedFilters.push($(this).val());
      });

      // Send AJAX request to filter products
      $.ajax({
        type: 'POST',
        url: aqualuxe_vars.ajax_url,
        data: {
          action: 'aqualuxe_filter_products',
          filters: selectedFilters,
        },
        beforeSend: function () {
          $('.products-container').addClass('loading');
        },
        success: function (response) {
          $('.products-container').html(response);
          $('.products-container').removeClass('loading');
        },
      });
    });
  }
})(jQuery);
