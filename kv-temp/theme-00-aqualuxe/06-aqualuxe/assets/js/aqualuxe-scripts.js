/**
 * File aqualuxe-scripts.js.
 *
 * Custom scripts for the AquaLuxe theme.
 */
jQuery(document).ready(function ($) {
	// AJAX Add to Cart
	$(document).on('click', '.ajax_add_to_cart', function (e) {
		e.preventDefault();

		var $thisbutton = $(this),
			product_id = $thisbutton.data('product_id'),
			quantity = $thisbutton.data('quantity') || 1;

		// Add loading class
		$thisbutton.addClass('loading');

		// AJAX request
		$.ajax({
			url: aqualuxe_ajax.ajax_url,
			type: 'POST',
			data: {
				action: 'aqualuxe_add_to_cart',
				product_id: product_id,
				quantity: quantity,
				nonce: aqualuxe_ajax.nonce
			},
			success: function (response) {
				if (response.success) {
					// Update cart fragments
					$(document.body).trigger('wc_fragment_refresh');

					// Show success message
					$('.woocommerce-notices-wrapper').html('<div class="woocommerce-message">' + response.data.message + '</div>');

					// Remove loading class
					$thisbutton.removeClass('loading');
				} else {
					// Show error message
					$('.woocommerce-notices-wrapper').html('<div class="woocommerce-error">' + response.data.message + '</div>');

					// Remove loading class
					$thisbutton.removeClass('loading');
				}
			},
			error: function () {
				// Show error message
				$('.woocommerce-notices-wrapper').html('<div class="woocommerce-error">Error adding product to cart.</div>');

				// Remove loading class
				$thisbutton.removeClass('loading');
			}
		});
	});

	// Product Quick View
	$(document).on('click', '.quick-view-button', function (e) {
		e.preventDefault();

		var product_id = $(this).data('product-id');

		// Show loading indicator
		$.blockUI({
			message: '<div class="loading">Loading...</div>',
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});

		// AJAX request
		$.ajax({
			url: aqualuxe_ajax.ajax_url,
			type: 'POST',
			data: {
				action: 'aqualuxe_quick_view',
				product_id: product_id,
				nonce: aqualuxe_ajax.nonce
			},
			success: function (response) {
				$.unblockUI();

				if (response.success) {
					// Create modal
					var modal = $('<div class="quick-view-modal"><div class="quick-view-content">' + response.data.content + '<button class="quick-view-close">×</button></div></div>');
					$('body').append(modal);

					// Show modal
					modal.fadeIn();

					// Close modal on click close button or outside content
					$(document).on('click', '.quick-view-close, .quick-view-modal', function (e) {
						if (e.target === this) {
							modal.fadeOut(function () {
								modal.remove();
							});
						}
					});

					// Close modal on ESC key
					$(document).on('keyup', function (e) {
						if (e.keyCode === 27) { // ESC key
							modal.fadeOut(function () {
								modal.remove();
							});
						}
					});
				} else {
					// Show error message
					alert(response.data.message);
				}
			},
			error: function () {
				$.unblockUI();
				alert('Error loading quick view.');
			}
		});
	});

	// Lazy loading for images
	const images = document.querySelectorAll('img[data-src]');
	const imageObserver = new IntersectionObserver((entries, observer) => {
		entries.forEach(entry => {
			if (entry.isIntersecting) {
				const img = entry.target;
				img.src = img.dataset.src;
				img.classList.remove('lazy');
				imageObserver.unobserve(img);
			}
		});
	});

	images.forEach(img => {
		imageObserver.observe(img);
	});
});