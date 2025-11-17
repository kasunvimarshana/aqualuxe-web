/*
 * WooCommerce JavaScript for AquaLuxe Theme
 */

(function($) {
	// Document ready
	$(document).ready(function() {
		// Product gallery
		if ($('.woocommerce-product-gallery').length > 0) {
			// Initialize product gallery
			$('.woocommerce-product-gallery').each(function() {
				const gallery = $(this);
				const thumbnails = gallery.find('.flex-control-thumbs li');
				const mainImage = gallery.find('.woocommerce-product-gallery__image img');
				
				// Thumbnail click event
				thumbnails.on('click', function() {
					const thumbnail = $(this);
					const imageSrc = thumbnail.find('img').attr('src');
					
					// Update main image
					mainImage.attr('src', imageSrc);
					
					// Update active class
					thumbnails.removeClass('active');
					thumbnail.addClass('active');
				});
			});
		}
		
		// Quantity input spinner
		$(document).on('click', '.quantity .plus, .quantity .minus', function() {
			const input = $(this).siblings('.qty');
			const currentValue = parseFloat(input.val());
			const max = parseFloat(input.attr('max'));
			const min = parseFloat(input.attr('min'));
			const step = parseFloat(input.attr('step'));
			
			if ($(this).hasClass('plus')) {
				let newValue = currentValue + step;
				if (max && newValue > max) {
					newValue = max;
				}
				input.val(newValue);
			} else {
				let newValue = currentValue - step;
				if (min && newValue < min) {
					newValue = min;
				}
				input.val(newValue);
			}
			
			input.trigger('change');
		});
		
		// Add to cart button
		$(document).on('click', '.single_add_to_cart_button', function() {
			const button = $(this);
			const originalText = button.text();
			
			// Disable button and show loading text
			button.prop('disabled', true).text('Adding to cart...');
			
			// Simulate adding to cart
			setTimeout(function() {
				button.text('Added to cart');
				
				// Update cart count in header
				const cartCount = $('.cart-count');
				if (cartCount.length > 0) {
					const currentCount = parseInt(cartCount.text()) || 0;
					cartCount.text(currentCount + 1);
				}
				
				// Re-enable button after delay
				setTimeout(function() {
					button.prop('disabled', false).text(originalText);
				}, 2000);
			}, 1000);
		});
		
		// Remove from cart button
		$(document).on('click', '.product-remove a', function(e) {
			e.preventDefault();
			
			const removeLink = $(this);
			const cartItem = removeLink.closest('.cart_item');
			
			// Fade out cart item
			cartItem.fadeOut(function() {
				$(this).remove();
				
				// Update cart totals
				updateCartTotals();
			});
		});
		
		// Update cart button
		$(document).on('change', '.cart input.qty', function() {
			$('[name="update_cart"]').prop('disabled', false);
		});
		
		// Apply coupon button
		$(document).on('click', '.coupon button', function() {
			$(this).prop('disabled', true);
		});
		
		// Checkout form validation
		if ($('.woocommerce-checkout').length > 0) {
			$('.woocommerce-checkout').on('submit', function() {
				let isValid = true;
				
				// Validate required fields
				$(this).find('input, select, textarea').each(function() {
					if ($(this).prop('required') && !$(this).val()) {
						isValid = false;
						$(this).addClass('error');
					} else {
						$(this).removeClass('error');
					}
				});
				
				// Validate email fields
				$(this).find('input[type="email"]').each(function() {
					const email = $(this).val();
					const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
					
					if (email && !emailRegex.test(email)) {
						isValid = false;
						$(this).addClass('error');
					} else {
						$(this).removeClass('error');
					}
				});
				
				if (!isValid) {
					$('html, body').animate({
						scrollTop: $('.error:first').offset().top - 100
					}, 500);
					
					return false;
				}
			});
		}
		
		// My Account navigation
		if ($('.woocommerce-MyAccount-navigation').length > 0) {
			$('.woocommerce-MyAccount-navigation a').on('click', function() {
				$('.woocommerce-MyAccount-navigation a').removeClass('active');
				$(this).addClass('active');
			});
		}
		
		// Product filter
		if ($('.product-filter').length > 0) {
			$('.filter-button').on('click', function(e) {
				e.preventDefault();
				const filterValue = $(this).data('filter');
				
				// Remove active class from all buttons
				$('.filter-button').removeClass('active');
				
				// Add active class to clicked button
				$(this).addClass('active');
				
				// Filter products
				if (filterValue === 'all') {
					$('.product-item').show();
				} else {
					$('.product-item').hide();
					$('.product-item.' + filterValue).show();
				}
			});
		}
		
		// Wishlist functionality
		$(document).on('click', '.add_to_wishlist', function() {
			const button = $(this);
			const productID = button.data('product-id');
			
			// Simulate adding to wishlist
			button.text('Added to Wishlist');
			button.removeClass('add_to_wishlist').addClass('added_to_wishlist');
		});
		
		// Quick view modal
		$(document).on('click', '.quick-view', function(e) {
			e.preventDefault();
			const productID = $(this).data('product-id');
			
			// Create modal with product content
			const modal = $('<div class="quick-view-modal"><p>Quick view content for product ID: ' + productID + '</p><button class="close">Close</button></div>');
			$('body').append(modal);
			modal.fadeIn();
		});
		
		// Close quick view modal
		$(document).on('click', '.quick-view-modal .close, .quick-view-modal', function(e) {
			if (e.target === this) {
				$(this).fadeOut(function() {
					$(this).remove();
				});
			}
		});
		
		// Variation selection
		if ($('.variations_form').length > 0) {
			$('.variations_form').on('change', 'select', function() {
				const form = $(this).closest('.variations_form');
				const selectedVariation = form.find('input[name="variation_id"]').val();
				
				if (selectedVariation) {
					// Update price
					const variationPrice = form.find('.variation-price-' + selectedVariation).html();
					if (variationPrice) {
						$('.product_price').html(variationPrice);
					}
					
					// Update image
					const variationImage = form.find('.variation-image-' + selectedVariation).attr('src');
					if (variationImage) {
						$('.product-featured-image img').attr('src', variationImage);
					}
				}
			});
		}
		
		// Related products slider
		if ($('.related.products .products').length > 0) {
			$('.related.products .products').each(function() {
				const products = $(this);
				const productItems = products.find('li.product');
				const totalProducts = productItems.length;
				let currentIndex = 0;
				
				// Add navigation buttons
				products.after('<div class="related-products-nav"><button class="prev">‹</button><button class="next">›</button></div>');
				
				// Show first set of products
				showProducts(currentIndex);
				
				// Next button
				products.siblings('.related-products-nav').find('.next').on('click', function() {
					currentIndex += 3;
					if (currentIndex >= totalProducts) {
						currentIndex = 0;
					}
					showProducts(currentIndex);
				});
				
				// Previous button
				products.siblings('.related-products-nav').find('.prev').on('click', function() {
					currentIndex -= 3;
					if (currentIndex < 0) {
						currentIndex = Math.max(0, totalProducts - 3);
					}
					showProducts(currentIndex);
				});
				
				function showProducts(index) {
					productItems.hide();
					productItems.slice(index, index + 3).show();
				}
			});
		}
		
		// Product search
		if ($('.woocommerce-product-search').length > 0) {
			$('.woocommerce-product-search').on('submit', function(e) {
				e.preventDefault();
				const searchTerm = $(this).find('input[name="s"]').val();
				
				if (searchTerm.length > 2) {
					// Simulate search
					console.log('Searching for: ' + searchTerm);
				}
			});
		}
		
		// Mini cart
		if ($('.widget_shopping_cart').length > 0) {
			$('.widget_shopping_cart').on('click', '.remove', function(e) {
				e.preventDefault();
				const removeLink = $(this);
				const cartItem = removeLink.closest('.mini_cart_item');
				
				// Fade out cart item
				cartItem.fadeOut(function() {
					$(this).remove();
					
					// Update cart totals
					updateCartTotals();
				});
			});
		}
		
		// Product tabs
		if ($('.woocommerce-tabs').length > 0) {
			$('.woocommerce-tabs ul.tabs li a').on('click', function(e) {
				e.preventDefault();
				const tabLink = $(this);
				const tabId = tabLink.attr('href');
				
				// Remove active class from all tabs
				$('.woocommerce-tabs ul.tabs li').removeClass('active');
				
				// Add active class to clicked tab
				tabLink.parent().addClass('active');
				
				// Hide all panels
				$('.woocommerce-tabs .panel').hide();
				
				// Show selected panel
				$(tabId).show();
			});
		}
		
		// Star ratings
		if ($('.woocommerce-product-rating').length > 0) {
			$('.woocommerce-product-rating .star-rating').on('click', function() {
				const rating = $(this).data('rating');
				$(this).parent().find('input[name="rating"]').val(rating);
			});
		}
		
		// Review form
		if ($('#review_form').length > 0) {
			$('#review_form').on('submit', function() {
				let isValid = true;
				
				// Validate required fields
				$(this).find('input, select, textarea').each(function() {
					if ($(this).prop('required') && !$(this).val()) {
						isValid = false;
						$(this).addClass('error');
					} else {
						$(this).removeClass('error');
					}
				});
				
				if (!isValid) {
					return false;
				}
			});
		}
		
		// Price filter
		if ($('.price_slider').length > 0) {
			// Initialize price slider
			$('.price_slider').slider({
				range: true,
				min: 0,
				max: 1000,
				values: [0, 1000],
				slide: function(event, ui) {
					$('.price_slider_amount #min_price').val(ui.values[0]);
					$('.price_slider_amount #max_price').val(ui.values[1]);
				}
			});
		}
		
		// AJAX add to cart
		$(document).on('click', '.ajax_add_to_cart', function() {
			const button = $(this);
			const productID = button.data('product_id');
			
			// Disable button and show loading text
			button.prop('disabled', true).text('Adding...');
			
			// Simulate AJAX request
			setTimeout(function() {
				button.text('Added to cart');
				
				// Update cart count in header
				const cartCount = $('.cart-count');
				if (cartCount.length > 0) {
					const currentCount = parseInt(cartCount.text()) || 0;
					cartCount.text(currentCount + 1);
				}
				
				// Show success message
				$('.woocommerce-message').remove();
				$('form.cart').before('<div class="woocommerce-message">Product added to cart successfully.</div>');
				
				// Re-enable button after delay
				setTimeout(function() {
					button.prop('disabled', false).text('Add to cart');
				}, 2000);
			}, 1000);
		});
	});
	
	// Window load
	$(window).on('load', function() {
		// Remove loading class from body
		$('body').removeClass('loading');
		
		// Initialize any components that need to wait for all resources to load
	});
	
	// Window resize
	$(window).on('resize', function() {
		// Handle responsive adjustments
	});
	
	// Window scroll
	$(window).on('scroll', function() {
		// Handle scroll-based animations or effects
		const scrollTop = $(window).scrollTop();
		
		// Add sticky header class when scrolled
		if (scrollTop > 100) {
			$('.site-header').addClass('sticky');
		} else {
			$('.site-header').removeClass('sticky');
		}
	});
	
	// Helper function to update cart totals
	function updateCartTotals() {
		// This is a simplified version - in a real implementation, 
		// you would recalculate the actual cart totals
		const itemCount = $('.cart_item').length;
		const total = 0;
		
		$('.cart_item').each(function() {
			const itemTotal = parseFloat($(this).find('.product-subtotal').text().replace('$', ''));
			if (!isNaN(itemTotal)) {
				total += itemTotal;
			}
		});
		
		$('.cart-subtotal .amount').text('$' + total.toFixed(2));
		$('.order-total .amount').text('$' + total.toFixed(2));
	}
	
	// Helper function to show/hide related products
	function toggleRelatedProducts() {
		const relatedProducts = $('.related.products');
		if (relatedProducts.length > 0) {
			const products = relatedProducts.find('li.product');
			if (products.length > 0) {
				relatedProducts.show();
			} else {
				relatedProducts.hide();
			}
		}
	}
})(jQuery);