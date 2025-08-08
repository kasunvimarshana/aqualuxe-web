/*
 * Customizer JavaScript for AquaLuxe Theme
 */

(function($) {
	// Site title and description changes
	wp.customize('blogname', function(value) {
		value.bind(function(to) {
			$('.site-title a').text(to);
		});
	});
	
	wp.customize('blogdescription', function(value) {
		value.bind(function(to) {
			$('.site-description').text(to);
		});
	});
	
	// Header text color changes
	wp.customize('header_textcolor', function(value) {
		value.bind(function(to) {
			if ('blank' === to) {
				$('.site-title, .site-description').css({
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				});
			} else {
				$('.site-title, .site-description').css({
					'clip': 'auto',
					'position': 'relative'
				});
				$('.site-title a, .site-description').css({
					'color': to
				});
			}
		});
	});
	
	// Hero section changes
	wp.customize('hero_title', function(value) {
		value.bind(function(to) {
			$('.hero-title').text(to);
		});
	});
	
	wp.customize('hero_description', function(value) {
		value.bind(function(to) {
			$('.hero-description').text(to);
		});
	});
	
	wp.customize('hero_button_text', function(value) {
		value.bind(function(to) {
			$('.hero-button').text(to);
		});
	});
	
	wp.customize('hero_button_url', function(value) {
		value.bind(function(to) {
			$('.hero-button').attr('href', to);
		});
	});
	
	// Featured products title changes
	wp.customize('featured_products_title', function(value) {
		value.bind(function(to) {
			$('.featured-products .section-title').text(to);
		});
	});
	
	// Testimonials title changes
	wp.customize('testimonials_title', function(value) {
		value.bind(function(to) {
			$('.testimonials .section-title').text(to);
		});
	});
	
	// Newsletter title changes
	wp.customize('newsletter_title', function(value) {
		value.bind(function(to) {
			$('.newsletter .section-title').text(to);
		});
	});
	
	// Newsletter description changes
	wp.customize('newsletter_description', function(value) {
		value.bind(function(to) {
			$('.newsletter-description').text(to);
		});
	});
	
	// About page changes
	wp.customize('history_title', function(value) {
		value.bind(function(to) {
			$('.company-history h2').text(to);
		});
	});
	
	wp.customize('team_title', function(value) {
		value.bind(function(to) {
			$('.team-section h2').text(to);
		});
	});
	
	// Services page changes
	wp.customize('breeding_title', function(value) {
		value.bind(function(to) {
			$('.breeding-programs h2').text(to);
		});
	});
	
	wp.customize('consultation_title', function(value) {
		value.bind(function(to) {
			$('.consultation h2').text(to);
		});
	});
	
	// Color scheme changes
	wp.customize('aqualuxe_color_scheme', function(value) {
		value.bind(function(to) {
			// Remove existing color scheme classes
			$('body').removeClass('color-scheme-blue color-scheme-green color-scheme-purple');
			
			// Add new color scheme class
			if (to) {
				$('body').addClass('color-scheme-' + to);
			}
		});
	});
	
	// Typography changes
	wp.customize('aqualuxe_heading_font', function(value) {
		value.bind(function(to) {
			$('h1, h2, h3, h4, h5, h6').css('font-family', to);
		});
	});
	
	wp.customize('aqualuxe_body_font', function(value) {
		value.bind(function(to) {
			$('body').css('font-family', to);
		});
	});
	
	// Layout changes
	wp.customize('aqualuxe_layout', function(value) {
		value.bind(function(to) {
			// Remove existing layout classes
			$('body').removeClass('layout-wide layout-boxed layout-framed');
			
			// Add new layout class
			if (to) {
				$('body').addClass('layout-' + to);
			}
		});
	});
	
	// Background image changes
	wp.customize('background_image', function(value) {
		value.bind(function(to) {
			if (to) {
				$('body').css('background-image', 'url(' + to + ')');
			} else {
				$('body').css('background-image', 'none');
			}
		});
	});
	
	// Background color changes
	wp.customize('background_color', function(value) {
		value.bind(function(to) {
			$('body').css('background-color', to);
		});
	});
	
	// Link color changes
	wp.customize('aqualuxe_link_color', function(value) {
		value.bind(function(to) {
			$('a').css('color', to);
		});
	});
	
	// Header background color changes
	wp.customize('aqualuxe_header_background_color', function(value) {
		value.bind(function(to) {
			$('.site-header').css('background-color', to);
		});
	});
	
	// Footer background color changes
	wp.customize('aqualuxe_footer_background_color', function(value) {
		value.bind(function(to) {
			$('.site-footer').css('background-color', to);
		});
	});
	
	// Button color changes
	wp.customize('aqualuxe_button_color', function(value) {
		value.bind(function(to) {
			$('.hero-button, .button, button, input[type="submit"]').css('background-color', to);
		});
	});
	
	// Button hover color changes
	wp.customize('aqualuxe_button_hover_color', function(value) {
		value.bind(function(to) {
			// We'll need to add CSS for hover states separately
			// This is just a placeholder for the customization
		});
	});
	
	// Border radius changes
	wp.customize('aqualuxe_border_radius', function(value) {
		value.bind(function(to) {
			$('.hero-button, .button, button, input[type="submit"], .product-item, .testimonials-slider, .newsletter').css('border-radius', to + 'px');
		});
	});
	
	// Box shadow changes
	wp.customize('aqualuxe_box_shadow', function(value) {
		value.bind(function(to) {
			if (to) {
				$('.product-item, .testimonials-slider, .newsletter').css('box-shadow', '0 2px 10px rgba(0, 0, 0, 0.1)');
			} else {
				$('.product-item, .testimonials-slider, .newsletter').css('box-shadow', 'none');
			}
		});
	});
	
	// Animation speed changes
	wp.customize('aqualuxe_animation_speed', function(value) {
		value.bind(function(to) {
			// Update CSS transition durations
			const styleId = 'aqualuxe-animation-speed';
			const style = $('#' + styleId);
			
			if (style.length === 0) {
				$('head').append('<style id="' + styleId + '"></style>');
			}
			
			$('#' + styleId).text(
				'* { transition-duration: ' + to + 's !important; }'
			);
		});
	});
	
	// Custom CSS changes
	wp.customize('aqualuxe_custom_css', function(value) {
		value.bind(function(to) {
			const styleId = 'aqualuxe-custom-css';
			const style = $('#' + styleId);
			
			if (style.length === 0) {
				$('head').append('<style id="' + styleId + '"></style>');
			}
			
			$('#' + styleId).text(to);
		});
	});
	
	// WooCommerce color changes
	wp.customize('aqualuxe_woocommerce_color', function(value) {
		value.bind(function(to) {
			$('.woocommerce div.product .cart .single_add_to_cart_button, .woocommerce-cart .cart-collaterals .wc-proceed-to-checkout a.checkout-button').css('background-color', to);
		});
	});
	
	// WooCommerce sale color changes
	wp.customize('aqualuxe_woocommerce_sale_color', function(value) {
		value.bind(function(to) {
			$('.woocommerce span.onsale').css('background-color', to);
		});
	});
	
	// WooCommerce price color changes
	wp.customize('aqualuxe_woocommerce_price_color', function(value) {
		value.bind(function(to) {
			$('.woocommerce div.product .price, .woocommerce ul.products li.product .price').css('color', to);
		});
	});
	
	// Social media icon changes
	wp.customize('aqualuxe_social_media_icons', function(value) {
		value.bind(function(to) {
			// This would require more complex handling to update social media icons
			// For now, we'll just log the change
			console.log('Social media icons updated:', to);
		});
	});
	
	// Contact information changes
	wp.customize('contact_address', function(value) {
		value.bind(function(to) {
			$('.contact-address').html(to);
		});
	});
	
	wp.customize('contact_phone', function(value) {
		value.bind(function(to) {
			$('.contact-phone a').text(to).attr('href', 'tel:' + to);
		});
	});
	
	wp.customize('contact_email', function(value) {
		value.bind(function(to) {
			$('.contact-email a').text(to).attr('href', 'mailto:' + to);
		});
	});
	
	// Initialize any components that need to wait for the customizer to be ready
	$(document).ready(function() {
		// Add any initialization code here
	});
	
	// Handle window resize events
	$(window).on('resize', function() {
		// Handle responsive adjustments in the customizer preview
	});
	
	// Handle scroll events
	$(window).on('scroll', function() {
		// Handle scroll-based adjustments in the customizer preview
	});
	
	// Handle customizer section activation
	$(document).on('click', '.control-section .accordion-section-title', function() {
		// Handle section activation for better user experience
	});
	
	// Handle customizer control focus
	$(document).on('focus', '.customize-control input, .customize-control select, .customize-control textarea', function() {
		// Highlight the corresponding element in the preview
		const controlId = $(this).closest('.customize-control').attr('id');
		if (controlId) {
			const elementClass = controlId.replace('customize-control-', '').replace(/_/g, '-');
			$('.' + elementClass).addClass('customizer-focus');
		}
	});
	
	// Handle customizer control blur
	$(document).on('blur', '.customize-control input, .customize-control select, .customize-control textarea', function() {
		// Remove highlight from the corresponding element in the preview
		const controlId = $(this).closest('.customize-control').attr('id');
		if (controlId) {
			const elementClass = controlId.replace('customize-control-', '').replace(/_/g, '-');
			$('.' + elementClass).removeClass('customizer-focus');
		}
	});
	
	// Handle customizer control change with delay for better performance
	let changeTimeout;
	$(document).on('input change', '.customize-control input, .customize-control select, .customize-control textarea', function() {
		clearTimeout(changeTimeout);
		changeTimeout = setTimeout(function() {
			// Trigger any necessary updates after a delay
		}, 300);
	});
	
	// Handle customizer partial refresh
	if ('undefined' !== typeof wp && wp.customize && wp.customize.selectiveRefresh) {
		// Site title partial refresh
		wp.customize.selectiveRefresh.bind('partial-content-rendered', function(placement) {
			if (placement.partial.id === 'blogname') {
				// Handle site title refresh
			}
		});
		
		// Site description partial refresh
		wp.customize.selectiveRefresh.bind('partial-content-rendered', function(placement) {
			if (placement.partial.id === 'blogdescription') {
				// Handle site description refresh
			}
		});
	}
})(jQuery);