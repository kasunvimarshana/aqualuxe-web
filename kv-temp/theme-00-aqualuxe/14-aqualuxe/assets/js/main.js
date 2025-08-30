/*
 * Main JavaScript for AquaLuxe Theme
 */

(function($) {
	// Document ready
	$(document).ready(function() {
		// Mobile menu toggle
		$('.menu-toggle').click(function() {
			$('.main-navigation ul').slideToggle();
		});
		
		// Testimonial slider
		if ($('.testimonials-slider').length > 0) {
			let currentIndex = 0;
			const testimonials = $('.testimonial-item');
			const totalTestimonials = testimonials.length;
			
			// Hide all testimonials except the first one
			testimonials.hide().eq(0).show();
			
			// Auto slide testimonials
			setInterval(function() {
				testimonials.hide().eq(currentIndex).fadeIn();
				currentIndex = (currentIndex + 1) % totalTestimonials;
			}, 5000);
		}
		
		// Smooth scrolling for anchor links
		$('a[href^="#"]').on('click', function(event) {
			const target = $(this.getAttribute('href'));
			
			if (target.length) {
				event.preventDefault();
				$('html, body').animate({
					scrollTop: target.offset().top - 80
				}, 500);
			}
		});
		
		// Product hover effect
		$('.product-item').hover(
			function() {
				$(this).find('img').css('transform', 'scale(1.05)');
			},
			function() {
				$(this).find('img').css('transform', 'scale(1)');
			}
		);
		
		// Newsletter form submission
		$('.newsletter form').on('submit', function(e) {
			e.preventDefault();
			
			const form = $(this);
			const email = form.find('input[type="email"]').val();
			const submitButton = form.find('input[type="submit"]');
			
			// Basic email validation
			if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
				alert('Please enter a valid email address');
				return;
			}
			
			// Disable submit button and show loading text
			submitButton.prop('disabled', true).val('Submitting...');
			
			// Simulate form submission
			setTimeout(function() {
				alert('Thank you for subscribing to our newsletter!');
				form.find('input[type="email"]').val('');
				submitButton.prop('disabled', false).val('Subscribe');
			}, 1000);
		});
		
		// Add to cart animation
		$(document).on('click', '.add_to_cart_button', function() {
			const button = $(this);
			const originalText = button.text();
			
			button.addClass('adding');
			button.text('Adding...');
			
			setTimeout(() => {
				button.removeClass('adding');
				button.text('Added to cart');
				
				setTimeout(() => {
					button.text(originalText);
				}, 2000);
			}, 1000);
		});
		
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
})(jQuery);