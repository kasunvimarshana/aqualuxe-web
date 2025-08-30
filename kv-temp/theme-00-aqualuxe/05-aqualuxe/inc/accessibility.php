<?php
/**
 * AquaLuxe Accessibility Functions
 *
 * @package AquaLuxe
 */

/**
 * Add accessibility attributes to navigation
 */
function aqualuxe_nav_accessibility() {
	// Add aria-label to main navigation
	echo '<script>
	document.addEventListener("DOMContentLoaded", function() {
		var nav = document.querySelector(".main-navigation");
		if (nav) {
			nav.setAttribute("aria-label", "Main navigation");
		}
		
		// Add aria-expanded to menu toggle
		var menuToggle = document.querySelector(".menu-toggle");
		if (menuToggle) {
			menuToggle.setAttribute("aria-expanded", "false");
			menuToggle.addEventListener("click", function() {
				var expanded = this.getAttribute("aria-expanded") === "true" || false;
				this.setAttribute("aria-expanded", !expanded);
			});
		}
	});
	</script>';
}
add_action( 'wp_head', 'aqualuxe_nav_accessibility' );

/**
 * Add skip link to content
 */
function aqualuxe_skip_link() {
	echo '<a class="skip-link screen-reader-text" href="#content">' . esc_html__( 'Skip to content', 'aqualuxe' ) . '</a>';
}
add_action( 'wp_body_open', 'aqualuxe_skip_link' );

/**
 * Add accessibility attributes to search form
 */
function aqualuxe_search_form_accessibility( $form ) {
	// Add aria-label to search form
	$form = str_replace( '<form', '<form aria-label="' . esc_attr__( 'Search form', 'aqualuxe' ) . '"', $form );
	
	// Add aria-label to search input
	$form = str_replace( '<input type="search"', '<input type="search" aria-label="' . esc_attr__( 'Search', 'aqualuxe' ) . '"', $form );
	
	return $form;
}
add_filter( 'get_search_form', 'aqualuxe_search_form_accessibility' );

/**
 * Add accessibility attributes to WooCommerce elements
 */
function aqualuxe_woocommerce_accessibility() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	// Add aria-label to cart icon
	echo '<script>
	document.addEventListener("DOMContentLoaded", function() {
		var cartIcon = document.querySelector(".cart-icon, .cart-contents");
		if (cartIcon) {
			cartIcon.setAttribute("aria-label", "' . esc_js( __( 'Shopping cart', 'aqualuxe' ) ) . '");
		}
		
		// Add aria-label to add to cart buttons
		var addToCartButtons = document.querySelectorAll(".add_to_cart_button");
		addToCartButtons.forEach(function(button) {
			if (!button.hasAttribute("aria-label")) {
				var productTitle = button.closest(".product").querySelector(".woocommerce-loop-product__title");
				if (productTitle) {
					button.setAttribute("aria-label", "' . esc_js( __( 'Add', 'aqualuxe' ) ) . ' " + productTitle.textContent + " ' . esc_js( __( 'to cart', 'aqualuxe' ) ) . '");
				}
			}
		});
	});
	</script>';
}
add_action( 'wp_head', 'aqualuxe_woocommerce_accessibility' );

/**
 * Add focus styles for keyboard navigation
 */
function aqualuxe_focus_styles() {
	echo '<style>
		/* Focus styles for keyboard navigation */
		*:focus {
			outline: 2px solid #0073e6;
			outline-offset: 2px;
		}
		
		/* Skip link focus style */
		.skip-link:focus {
			background-color: #f1f1f1;
			border-radius: 3px;
			box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.6);
			clip: auto !important;
			-webkit-clip-path: none;
			clip-path: none;
			color: #21759b;
			display: block;
			font-size: 14px;
			font-weight: bold;
			height: auto;
			left: 5px;
			line-height: normal;
			padding: 15px 23px 14px;
			text-decoration: none;
			top: 5px;
			width: auto;
			z-index: 100000;
		}
		
		/* Screen reader text */
		.screen-reader-text {
			border: 0;
			clip: rect(1px, 1px, 1px, 1px);
			clip-path: inset(50%);
			height: 1px;
			margin: -1px;
			overflow: hidden;
			padding: 0;
			position: absolute !important;
			width: 1px;
			word-wrap: normal !important;
		}
		
		.screen-reader-text:focus {
			background-color: #eee;
			clip: auto !important;
			clip-path: none;
			color: #444;
			display: block;
			font-size: 1em;
			height: auto;
			left: 5px;
			line-height: normal;
			padding: 15px 23px 14px;
			text-decoration: none;
			top: 5px;
			width: auto;
			z-index: 100000;
		}
	</style>';
}
add_action( 'wp_head', 'aqualuxe_focus_styles' );

/**
 * Add landmark roles to template parts
 */
function aqualuxe_landmark_roles() {
	echo '<script>
	document.addEventListener("DOMContentLoaded", function() {
		// Add role to main content area
		var main = document.querySelector("main, #main");
		if (main) {
			main.setAttribute("role", "main");
		}
		
		// Add role to search
		var search = document.querySelector(".search-form, .search");
		if (search) {
			search.setAttribute("role", "search");
		}
		
		// Add role to navigation
		var nav = document.querySelector("nav, .navigation");
		if (nav) {
			nav.setAttribute("role", "navigation");
		}
		
		// Add role to complementary areas
		var sidebar = document.querySelector(".sidebar, #secondary");
		if (sidebar) {
			sidebar.setAttribute("role", "complementary");
		}
		
		// Add role to footer
		var footer = document.querySelector("footer, #colophon");
		if (footer) {
			footer.setAttribute("role", "contentinfo");
		}
	});
	</script>';
}
add_action( 'wp_head', 'aqualuxe_landmark_roles' );

/**
 * Add ARIA attributes to product images
 */
function aqualuxe_product_image_accessibility( $html, $attachment_id ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return $html;
	}
	
	// Add aria-describedby to product images
	$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
	if ( empty( $alt ) ) {
		$alt = get_the_title( $attachment_id );
	}
	
	// Add role and alt attributes
	$html = str_replace( '<img', '<img role="img" alt="' . esc_attr( $alt ) . '"', $html );
	
	return $html;
}
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'aqualuxe_product_image_accessibility', 10, 2 );
add_filter( 'woocommerce_product_get_image', 'aqualuxe_product_image_accessibility', 10, 2 );

/**
 * Add ARIA attributes to form fields
 */
function aqualuxe_form_field_accessibility( $field, $key, $args, $value ) {
	// Add aria-describedby for description
	if ( ! empty( $args['description'] ) ) {
		$description_id = $args['id'] . '-description';
		$field = str_replace( '<input', '<input aria-describedby="' . esc_attr( $description_id ) . '"', $field );
		$field = str_replace( '</label>', '</label><div id="' . esc_attr( $description_id ) . '" class="description">' . $args['description'] . '</div>', $field );
	}
	
	// Add aria-invalid for error fields
	if ( ! empty( $args['custom_attributes']['aria-invalid'] ) ) {
		$field = str_replace( '<input', '<input aria-invalid="true"', $field );
	}
	
	return $field;
}
add_filter( 'woocommerce_form_field', 'aqualuxe_form_field_accessibility', 10, 4 );