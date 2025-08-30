<?php
/**
 * AquaLuxe Accessibility Helper Functions
 *
 * This file contains helper functions for accessibility features.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add ARIA attributes to menu items
 *
 * @param array $atts The HTML attributes applied to the menu item's <a> element.
 * @param object $item The current menu item.
 * @param array $args An array of wp_nav_menu() arguments.
 * @param int $depth Depth of menu item.
 * @return array Modified attributes array.
 */
function aqualuxe_menu_aria_attributes( $atts, $item, $args, $depth ) {
	// Add ARIA attributes to menu items with children
	if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
		$atts['aria-haspopup'] = 'true';
		$atts['aria-expanded'] = 'false';
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'aqualuxe_menu_aria_attributes', 10, 4 );

/**
 * Add screen reader text to social icons
 *
 * @param string $html The HTML output for the social icon.
 * @param string $network The social network name.
 * @return string Modified HTML with screen reader text.
 */
function aqualuxe_social_icon_accessibility( $html, $network ) {
	// Add screen reader text to social icons
	$network_name = ucfirst( $network );
	$screen_reader_text = sprintf( '<span class="sr-only">%s</span>', esc_html__( 'Follow us on', 'aqualuxe' ) . ' ' . $network_name );
	
	return str_replace( '</a>', $screen_reader_text . '</a>', $html );
}
add_filter( 'aqualuxe_social_icon_html', 'aqualuxe_social_icon_accessibility', 10, 2 );

/**
 * Add ARIA attributes to pagination links
 *
 * @param string $template The HTML output for the pagination.
 * @return string Modified HTML with ARIA attributes.
 */
function aqualuxe_pagination_accessibility( $template ) {
	// Add ARIA attributes to pagination
	$template = str_replace( '<nav class="navigation pagination"', '<nav class="navigation pagination" role="navigation" aria-label="' . esc_attr__( 'Posts Navigation', 'aqualuxe' ) . '"', $template );
	$template = str_replace( '<div class="nav-links">', '<div class="nav-links" role="menubar">', $template );
	
	return $template;
}
add_filter( 'navigation_markup_template', 'aqualuxe_pagination_accessibility' );

/**
 * Add ARIA attributes to comment form
 *
 * @param array $args Comment form arguments.
 * @return array Modified arguments.
 */
function aqualuxe_comment_form_accessibility( $args ) {
	// Add ARIA attributes to comment form
	$args['title_reply_before'] = '<h3 id="reply-title" class="comment-reply-title" aria-label="' . esc_attr__( 'Leave a comment', 'aqualuxe' ) . '">';
	$args['comment_notes_before'] = '<p class="comment-notes" aria-live="polite">' . esc_html__( 'Your email address will not be published. Required fields are marked *', 'aqualuxe' ) . '</p>';
	
	return $args;
}
add_filter( 'comment_form_defaults', 'aqualuxe_comment_form_accessibility' );

/**
 * Add ARIA attributes to search form
 *
 * @param string $form The search form HTML.
 * @return string Modified search form HTML.
 */
function aqualuxe_search_form_accessibility( $form ) {
	// Add ARIA attributes to search form
	$form = str_replace( 'role="search"', 'role="search" aria-label="' . esc_attr__( 'Site Search', 'aqualuxe' ) . '"', $form );
	
	return $form;
}
add_filter( 'get_search_form', 'aqualuxe_search_form_accessibility' );

/**
 * Add skip links for keyboard navigation
 *
 * @return void
 */
function aqualuxe_add_skip_links() {
	// Skip links are already added in header.php
}

/**
 * Add ARIA attributes to WooCommerce elements
 *
 * @return void
 */
function aqualuxe_woocommerce_accessibility() {
	// Only run if WooCommerce is active
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	// Add ARIA attributes to product sorting dropdown
	add_filter( 'woocommerce_catalog_ordering', function( $html ) {
		return str_replace( '<select name="orderby"', '<select name="orderby" aria-label="' . esc_attr__( 'Shop order', 'aqualuxe' ) . '"', $html );
	} );
	
	// Add ARIA attributes to product gallery
	add_filter( 'woocommerce_single_product_image_gallery_classes', function( $classes ) {
		$classes[] = 'aria-label="' . esc_attr__( 'Product Gallery', 'aqualuxe' ) . '"';
		return $classes;
	} );
	
	// Add ARIA attributes to add to cart button
	add_filter( 'woocommerce_loop_add_to_cart_args', function( $args ) {
		$args['attributes']['aria-label'] = sprintf( esc_attr__( 'Add %s to your cart', 'aqualuxe' ), get_the_title() );
		return $args;
	} );
}
add_action( 'init', 'aqualuxe_woocommerce_accessibility' );

/**
 * Add screen reader text to icons
 *
 * @param string $icon The icon HTML.
 * @param string $icon_name The icon name.
 * @param string $label Optional. The screen reader text.
 * @return string Modified icon HTML with screen reader text.
 */
function aqualuxe_icon_accessibility( $icon, $icon_name, $label = '' ) {
	if ( empty( $label ) ) {
		// If no label is provided, make the icon decorative
		$icon = str_replace( '<svg', '<svg aria-hidden="true"', $icon );
	} else {
		// If a label is provided, add screen reader text
		$icon = str_replace( '</svg>', '</svg><span class="sr-only">' . esc_html( $label ) . '</span>', $icon );
	}
	
	return $icon;
}
add_filter( 'aqualuxe_icon_html', 'aqualuxe_icon_accessibility', 10, 3 );

/**
 * Add ARIA attributes to accordion elements
 *
 * @param string $output The accordion HTML.
 * @param array $args The accordion arguments.
 * @return string Modified accordion HTML with ARIA attributes.
 */
function aqualuxe_accordion_accessibility( $output, $args ) {
	// Add ARIA attributes to accordion
	$output = str_replace( '<div class="accordion">', '<div class="accordion" role="region" aria-label="' . esc_attr( $args['title'] ?? __( 'Accordion', 'aqualuxe' ) ) . '">', $output );
	
	return $output;
}
add_filter( 'aqualuxe_accordion_html', 'aqualuxe_accordion_accessibility', 10, 2 );

/**
 * Add ARIA attributes to tab elements
 *
 * @param string $output The tabs HTML.
 * @param array $args The tabs arguments.
 * @return string Modified tabs HTML with ARIA attributes.
 */
function aqualuxe_tabs_accessibility( $output, $args ) {
	// Add ARIA attributes to tabs
	$output = str_replace( '<div class="tabs">', '<div class="tabs" role="tablist" aria-label="' . esc_attr( $args['title'] ?? __( 'Tabs', 'aqualuxe' ) ) . '">', $output );
	
	return $output;
}
add_filter( 'aqualuxe_tabs_html', 'aqualuxe_tabs_accessibility', 10, 2 );

/**
 * Add ARIA attributes to modal elements
 *
 * @param string $output The modal HTML.
 * @param array $args The modal arguments.
 * @return string Modified modal HTML with ARIA attributes.
 */
function aqualuxe_modal_accessibility( $output, $args ) {
	// Add ARIA attributes to modal
	$output = str_replace( '<div class="modal"', '<div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title-' . esc_attr( $args['id'] ) . '"', $output );
	$output = str_replace( '<h2 class="modal-title">', '<h2 id="modal-title-' . esc_attr( $args['id'] ) . '" class="modal-title">', $output );
	
	return $output;
}
add_filter( 'aqualuxe_modal_html', 'aqualuxe_modal_accessibility', 10, 2 );

/**
 * Add ARIA attributes to alert elements
 *
 * @param string $output The alert HTML.
 * @param string $type The alert type.
 * @param string $message The alert message.
 * @return string Modified alert HTML with ARIA attributes.
 */
function aqualuxe_alert_accessibility( $output, $type, $message ) {
	// Add ARIA attributes to alert
	$output = str_replace( '<div class="alert alert-' . $type . '">', '<div class="alert alert-' . $type . '" role="alert" aria-live="assertive">', $output );
	
	return $output;
}
add_filter( 'aqualuxe_alert_html', 'aqualuxe_alert_accessibility', 10, 3 );

/**
 * Add screen reader text for required form fields
 *
 * @param string $html The field HTML.
 * @param array $args The field arguments.
 * @return string Modified field HTML with screen reader text.
 */
function aqualuxe_form_field_accessibility( $html, $args ) {
	// Add screen reader text for required fields
	if ( isset( $args['required'] ) && $args['required'] ) {
		$html = str_replace( '<span class="required">*</span>', '<span class="required" aria-hidden="true">*</span><span class="sr-only">' . esc_html__( 'required', 'aqualuxe' ) . '</span>', $html );
	}
	
	return $html;
}
add_filter( 'aqualuxe_form_field_html', 'aqualuxe_form_field_accessibility', 10, 2 );

/**
 * Get dark mode class for HTML tag
 *
 * @return string Dark mode class if enabled, empty string otherwise.
 */
function aqualuxe_get_dark_mode_class() {
	// Check if dark mode is enabled
	$dark_mode = get_theme_mod( 'aqualuxe_dark_mode', 'auto' );
	
	if ( 'always' === $dark_mode ) {
		return 'dark';
	} elseif ( 'auto' === $dark_mode ) {
		return 'dark-mode-auto';
	}
	
	return '';
}

/**
 * Add dark mode toggle button
 *
 * @return void
 */
function aqualuxe_dark_mode_toggle() {
	// Only show toggle if dark mode is not disabled
	if ( 'disabled' === get_theme_mod( 'aqualuxe_dark_mode', 'auto' ) ) {
		return;
	}
	
	// Dark mode toggle button
	$toggle_html = '<button id="dark-mode-toggle" class="dark-mode-toggle" aria-pressed="false" aria-label="' . esc_attr__( 'Toggle dark mode', 'aqualuxe' ) . '">';
	$toggle_html .= '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">';
	$toggle_html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />';
	$toggle_html .= '</svg>';
	$toggle_html .= '<span class="sr-only">' . esc_html__( 'Toggle dark mode', 'aqualuxe' ) . '</span>';
	$toggle_html .= '</button>';
	
	echo wp_kses(
		$toggle_html,
		array(
			'button' => array(
				'id'          => array(),
				'class'       => array(),
				'aria-pressed' => array(),
				'aria-label'  => array(),
			),
			'svg'    => array(
				'xmlns'       => array(),
				'class'       => array(),
				'fill'        => array(),
				'viewBox'     => array(),
				'stroke'      => array(),
				'aria-hidden' => array(),
			),
			'path'   => array(
				'stroke-linecap' => array(),
				'stroke-linejoin' => array(),
				'stroke-width'   => array(),
				'd'              => array(),
			),
			'span'   => array(
				'class'       => array(),
			),
		)
	);
}

/**
 * Add back to top button
 *
 * @return void
 */
function aqualuxe_back_to_top() {
	// Back to top button
	$back_to_top_html = '<button id="back-to-top" class="back-to-top" aria-label="' . esc_attr__( 'Back to top', 'aqualuxe' ) . '">';
	$back_to_top_html .= '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">';
	$back_to_top_html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />';
	$back_to_top_html .= '</svg>';
	$back_to_top_html .= '<span class="sr-only">' . esc_html__( 'Back to top', 'aqualuxe' ) . '</span>';
	$back_to_top_html .= '</button>';
	
	echo wp_kses(
		$back_to_top_html,
		array(
			'button' => array(
				'id'          => array(),
				'class'       => array(),
				'aria-label'  => array(),
			),
			'svg'    => array(
				'xmlns'       => array(),
				'class'       => array(),
				'fill'        => array(),
				'viewBox'     => array(),
				'stroke'      => array(),
				'aria-hidden' => array(),
			),
			'path'   => array(
				'stroke-linecap' => array(),
				'stroke-linejoin' => array(),
				'stroke-width'   => array(),
				'd'              => array(),
			),
			'span'   => array(
				'class'       => array(),
			),
		)
	);
}