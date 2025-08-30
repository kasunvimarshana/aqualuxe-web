/**
 * AquaLuxe Custom JavaScript
 *
 * @package AquaLuxe
 */

(function ($) {
  'use strict';

  // Document Ready
  $(document).ready(function () {
    // Initialize Quick View
    initQuickView();

    // Initialize Product Gallery
    initProductGallery();

    // Initialize Sticky Add to Cart
    initStickyAddToCart();

    // Initialize Back to Top
    initBackToTop();
  });

  // Quick View Function
  function initQuickView() {
    $('.quick-view-button').on('click', function (e) {
      e.preventDefault();

      var product_id = $(this).data('product-id');

      $.ajax({
        type: 'POST',
        url: aqualuxe_vars.ajaxurl,
        data: {
          action: 'aqualuxe_quick_view',
          product_id: product_id,
        },
        beforeSend: function () {
          $('body').addClass('aqualuxe-loading');
        },
        success: function (response) {
          $('body').removeClass('aqualuxe-loading');

          $.magnificPopup.open({
            items: {
              src: response,
              type: 'inline',
            },
            mainClass: 'mfp-fade',
            removalDelay: 300,
            showCloseBtn: true,
            closeBtnInside: true,
          });
        },
      });
    });
  }

  // Product Gallery Function
  function initProductGallery() {
    var gallery = $('.product .images');

    if (gallery.length) {
      gallery.flexslider({
        animation: 'slide',
        controlNav: 'thumbnails',
        animationLoop: false,
        slideshow: false,
        smoothHeight: true,
      });
    }
  }

  // Sticky Add to Cart Function
  function initStickyAddToCart() {
    if ($('.single-product').length) {
      var addToCartForm = $('.cart'),
        addToCartButton = $('.single_add_to_cart_button'),
        stickyAddToCart = $('<div class="sticky-add-to-cart"></div>');

      stickyAddToCart.html(
        '<div class="container"><div class="product-info">' +
          '<span class="product-title">' +
          $('.entry-title').text() +
          '</span>' +
          '<span class="product-price">' +
          $('.price').html() +
          '</span>' +
          '</div><div class="add-to-cart">' +
          addToCartButton
            .clone()
            .removeClass('single_add_to_cart_button')
            .addClass('sticky-add-to-cart-button') +
          '</div></div>'
      );

      $('body').append(stickyAddToCart);

      $(window).on('scroll', function () {
        if (
          $(window).scrollTop() >
          addToCartForm.offset().top + addToCartForm.outerHeight()
        ) {
          stickyAddToCart.addClass('active');
        } else {
          stickyAddToCart.removeClass('active');
        }
      });

      $('.sticky-add-to-cart-button').on('click', function () {
        $('html, body').animate(
          {
            scrollTop: addToCartForm.offset().top - 100,
          },
          500
        );
      });
    }
  }

  // Back to Top Function
  function initBackToTop() {
    var backToTop = $(
      '<a href="#" class="back-to-top"><i class="fas fa-arrow-up"></i></a>'
    );

    $('body').append(backToTop);

    $(window).on('scroll', function () {
      if ($(window).scrollTop() > 300) {
        backToTop.addClass('active');
      } else {
        backToTop.removeClass('active');
      }
    });

    backToTop.on('click', function (e) {
      e.preventDefault();
      $('html, body').animate(
        {
          scrollTop: 0,
        },
        800
      );
    });
  }
})(jQuery);
