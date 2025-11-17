/**
 * AquaLuxe WooCommerce JavaScript
 */
(function () {
  'use strict';

  // Document ready
  document.addEventListener('DOMContentLoaded', function () {
    // Quantity buttons
    var quantityInputs = document.querySelectorAll('input[type="number"].qty');

    quantityInputs.forEach(function (input) {
      var container = input.closest('.quantity');
      var minus = document.createElement('button');
      var plus = document.createElement('button');

      minus.type = 'button';
      minus.className = 'minus';
      minus.innerHTML = '-';
      minus.addEventListener('click', function () {
        if (input.value > 1) {
          input.value = parseInt(input.value) - 1;
          input.dispatchEvent(new Event('change'));
        }
      });

      plus.type = 'button';
      plus.className = 'plus';
      plus.innerHTML = '+';
      plus.addEventListener('click', function () {
        input.value = parseInt(input.value) + 1;
        input.dispatchEvent(new Event('change'));
      });

      container.insertBefore(minus, input);
      container.appendChild(plus);
    });

    // Variation form
    var variationForms = document.querySelectorAll('.variations_form');

    variationForms.forEach(function (form) {
      form.addEventListener('wc_variation_form', function () {
        // Reset variation image when reset button is clicked
        var resetButton = form.querySelector('.reset_variations');
        if (resetButton) {
          resetButton.addEventListener('click', function () {
            var productImage = document.querySelector(
              '.woocommerce-product-gallery__image img'
            );
            if (productImage) {
              productImage.setAttribute(
                'src',
                productImage.getAttribute('data-o_src')
              );
              productImage.setAttribute(
                'srcset',
                productImage.getAttribute('data-o_srcset')
              );
            }
          });
        }
      });
    });
  });
})();
