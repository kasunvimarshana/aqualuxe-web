<?php
/**
 * Theme Hooks
 *
 * This file contains all the hooks used in the theme.
 *
 * @package AquaLuxe
 */

/**
 * Header hooks
 */
function aqualuxe_header_hooks() {
	/**
	 * Hook: aqualuxe_before_header
	 *
	 * @hooked aqualuxe_skip_links - 10
	 * @hooked aqualuxe_announcement_bar - 20
	 */
	do_action( 'aqualuxe_before_header' );

	/**
	 * Hook: aqualuxe_header
	 *
	 * @hooked aqualuxe_header_content - 10
	 */
	do_action( 'aqualuxe_header' );

	/**
	 * Hook: aqualuxe_after_header
	 *
	 * @hooked aqualuxe_breadcrumbs - 10
	 */
	do_action( 'aqualuxe_after_header' );
}

/**
 * Footer hooks
 */
function aqualuxe_footer_hooks() {
	/**
	 * Hook: aqualuxe_before_footer
	 *
	 * @hooked aqualuxe_newsletter_signup - 10
	 * @hooked aqualuxe_instagram_feed - 20
	 */
	do_action( 'aqualuxe_before_footer' );

	/**
	 * Hook: aqualuxe_footer
	 *
	 * @hooked aqualuxe_footer_content - 10
	 */
	do_action( 'aqualuxe_footer' );

	/**
	 * Hook: aqualuxe_after_footer
	 *
	 * @hooked aqualuxe_back_to_top - 10
	 * @hooked aqualuxe_cookie_notice - 20
	 */
	do_action( 'aqualuxe_after_footer' );
}

/**
 * Content hooks
 */
function aqualuxe_content_hooks() {
	/**
	 * Hook: aqualuxe_before_content
	 *
	 * @hooked aqualuxe_page_header - 10
	 */
	do_action( 'aqualuxe_before_content' );

	/**
	 * Hook: aqualuxe_content
	 *
	 * @hooked aqualuxe_content_area - 10
	 */
	do_action( 'aqualuxe_content' );

	/**
	 * Hook: aqualuxe_after_content
	 *
	 * @hooked aqualuxe_related_posts - 10
	 * @hooked aqualuxe_post_navigation - 20
	 */
	do_action( 'aqualuxe_after_content' );
}

/**
 * Entry hooks
 */
function aqualuxe_entry_hooks() {
	/**
	 * Hook: aqualuxe_before_entry
	 */
	do_action( 'aqualuxe_before_entry' );

	/**
	 * Hook: aqualuxe_entry_header
	 *
	 * @hooked aqualuxe_post_thumbnail - 10
	 * @hooked aqualuxe_entry_header_content - 20
	 */
	do_action( 'aqualuxe_entry_header' );

	/**
	 * Hook: aqualuxe_entry_content
	 *
	 * @hooked aqualuxe_entry_content_area - 10
	 */
	do_action( 'aqualuxe_entry_content' );

	/**
	 * Hook: aqualuxe_entry_footer
	 *
	 * @hooked aqualuxe_entry_footer_content - 10
	 */
	do_action( 'aqualuxe_entry_footer' );

	/**
	 * Hook: aqualuxe_after_entry
	 *
	 * @hooked aqualuxe_author_bio - 10
	 * @hooked aqualuxe_social_sharing - 20
	 */
	do_action( 'aqualuxe_after_entry' );
}

/**
 * Sidebar hooks
 */
function aqualuxe_sidebar_hooks() {
	/**
	 * Hook: aqualuxe_before_sidebar
	 */
	do_action( 'aqualuxe_before_sidebar' );

	/**
	 * Hook: aqualuxe_sidebar
	 *
	 * @hooked aqualuxe_get_sidebar - 10
	 */
	do_action( 'aqualuxe_sidebar' );

	/**
	 * Hook: aqualuxe_after_sidebar
	 */
	do_action( 'aqualuxe_after_sidebar' );
}

/**
 * Comments hooks
 */
function aqualuxe_comments_hooks() {
	/**
	 * Hook: aqualuxe_before_comments
	 */
	do_action( 'aqualuxe_before_comments' );

	/**
	 * Hook: aqualuxe_comments
	 *
	 * @hooked aqualuxe_comments_list - 10
	 */
	do_action( 'aqualuxe_comments' );

	/**
	 * Hook: aqualuxe_after_comments
	 *
	 * @hooked aqualuxe_comments_navigation - 10
	 * @hooked aqualuxe_comment_form - 20
	 */
	do_action( 'aqualuxe_after_comments' );
}

/**
 * WooCommerce hooks
 */
function aqualuxe_woocommerce_hooks() {
	/**
	 * Hook: aqualuxe_before_shop
	 *
	 * @hooked aqualuxe_shop_filters - 10
	 */
	do_action( 'aqualuxe_before_shop' );

	/**
	 * Hook: aqualuxe_shop_loop
	 *
	 * @hooked aqualuxe_shop_loop_content - 10
	 */
	do_action( 'aqualuxe_shop_loop' );

	/**
	 * Hook: aqualuxe_after_shop
	 *
	 * @hooked aqualuxe_shop_pagination - 10
	 */
	do_action( 'aqualuxe_after_shop' );
}

/**
 * Product hooks
 */
function aqualuxe_product_hooks() {
	/**
	 * Hook: aqualuxe_before_product
	 *
	 * @hooked aqualuxe_product_breadcrumbs - 10
	 */
	do_action( 'aqualuxe_before_product' );

	/**
	 * Hook: aqualuxe_product_content
	 *
	 * @hooked aqualuxe_product_gallery - 10
	 * @hooked aqualuxe_product_summary - 20
	 */
	do_action( 'aqualuxe_product_content' );

	/**
	 * Hook: aqualuxe_after_product
	 *
	 * @hooked aqualuxe_product_tabs - 10
	 * @hooked aqualuxe_related_products - 20
	 * @hooked aqualuxe_upsell_products - 30
	 */
	do_action( 'aqualuxe_after_product' );
}

/**
 * Cart hooks
 */
function aqualuxe_cart_hooks() {
	/**
	 * Hook: aqualuxe_before_cart
	 *
	 * @hooked aqualuxe_cart_notices - 10
	 */
	do_action( 'aqualuxe_before_cart' );

	/**
	 * Hook: aqualuxe_cart
	 *
	 * @hooked aqualuxe_cart_content - 10
	 */
	do_action( 'aqualuxe_cart' );

	/**
	 * Hook: aqualuxe_after_cart
	 *
	 * @hooked aqualuxe_cross_sell_products - 10
	 */
	do_action( 'aqualuxe_after_cart' );
}

/**
 * Checkout hooks
 */
function aqualuxe_checkout_hooks() {
	/**
	 * Hook: aqualuxe_before_checkout
	 *
	 * @hooked aqualuxe_checkout_notices - 10
	 * @hooked aqualuxe_checkout_steps - 20
	 */
	do_action( 'aqualuxe_before_checkout' );

	/**
	 * Hook: aqualuxe_checkout
	 *
	 * @hooked aqualuxe_checkout_content - 10
	 */
	do_action( 'aqualuxe_checkout' );

	/**
	 * Hook: aqualuxe_after_checkout
	 *
	 * @hooked aqualuxe_checkout_upsell - 10
	 */
	do_action( 'aqualuxe_after_checkout' );
}

/**
 * Account hooks
 */
function aqualuxe_account_hooks() {
	/**
	 * Hook: aqualuxe_before_account
	 *
	 * @hooked aqualuxe_account_navigation - 10
	 */
	do_action( 'aqualuxe_before_account' );

	/**
	 * Hook: aqualuxe_account
	 *
	 * @hooked aqualuxe_account_content - 10
	 */
	do_action( 'aqualuxe_account' );

	/**
	 * Hook: aqualuxe_after_account
	 */
	do_action( 'aqualuxe_after_account' );
}

/**
 * Theme setup hooks
 */
function aqualuxe_setup_hooks() {
	/**
	 * Hook: aqualuxe_setup
	 *
	 * @hooked aqualuxe_setup_theme - 10
	 * @hooked aqualuxe_register_sidebars - 20
	 * @hooked aqualuxe_register_nav_menus - 30
	 */
	do_action( 'aqualuxe_setup' );

	/**
	 * Hook: aqualuxe_after_setup
	 *
	 * @hooked aqualuxe_content_width - 10
	 */
	do_action( 'aqualuxe_after_setup' );
}

/**
 * Assets hooks
 */
function aqualuxe_assets_hooks() {
	/**
	 * Hook: aqualuxe_enqueue_scripts
	 *
	 * @hooked aqualuxe_enqueue_styles - 10
	 * @hooked aqualuxe_enqueue_scripts - 20
	 */
	do_action( 'aqualuxe_enqueue_scripts' );

	/**
	 * Hook: aqualuxe_admin_enqueue_scripts
	 *
	 * @hooked aqualuxe_admin_enqueue_styles - 10
	 * @hooked aqualuxe_admin_enqueue_scripts - 20
	 */
	do_action( 'aqualuxe_admin_enqueue_scripts' );
}

/**
 * Customizer hooks
 */
function aqualuxe_customizer_hooks() {
	/**
	 * Hook: aqualuxe_customize_register
	 *
	 * @hooked aqualuxe_customize_register_settings - 10
	 * @hooked aqualuxe_customize_register_controls - 20
	 * @hooked aqualuxe_customize_register_sections - 30
	 * @hooked aqualuxe_customize_register_panels - 40
	 */
	do_action( 'aqualuxe_customize_register' );

	/**
	 * Hook: aqualuxe_customize_preview_init
	 *
	 * @hooked aqualuxe_customize_preview_js - 10
	 */
	do_action( 'aqualuxe_customize_preview_init' );
}

/**
 * Widget hooks
 */
function aqualuxe_widget_hooks() {
	/**
	 * Hook: aqualuxe_widgets_init
	 *
	 * @hooked aqualuxe_register_widgets - 10
	 */
	do_action( 'aqualuxe_widgets_init' );

	/**
	 * Hook: aqualuxe_widget_display
	 *
	 * @hooked aqualuxe_widget_display_callback - 10
	 */
	do_action( 'aqualuxe_widget_display' );
}

/**
 * SEO hooks
 */
function aqualuxe_seo_hooks() {
	/**
	 * Hook: aqualuxe_head
	 *
	 * @hooked aqualuxe_meta_tags - 10
	 * @hooked aqualuxe_open_graph_tags - 20
	 * @hooked aqualuxe_twitter_card_tags - 30
	 */
	do_action( 'aqualuxe_head' );

	/**
	 * Hook: aqualuxe_footer_schema
	 *
	 * @hooked aqualuxe_json_ld_schema - 10
	 */
	do_action( 'aqualuxe_footer_schema' );
}

/**
 * Performance hooks
 */
function aqualuxe_performance_hooks() {
	/**
	 * Hook: aqualuxe_before_page
	 *
	 * @hooked aqualuxe_critical_css - 10
	 * @hooked aqualuxe_preload_assets - 20
	 */
	do_action( 'aqualuxe_before_page' );

	/**
	 * Hook: aqualuxe_after_page
	 *
	 * @hooked aqualuxe_deferred_scripts - 10
	 * @hooked aqualuxe_inline_scripts - 20
	 */
	do_action( 'aqualuxe_after_page' );
}

/**
 * Accessibility hooks
 */
function aqualuxe_accessibility_hooks() {
	/**
	 * Hook: aqualuxe_accessibility
	 *
	 * @hooked aqualuxe_skip_links - 10
	 * @hooked aqualuxe_aria_landmarks - 20
	 */
	do_action( 'aqualuxe_accessibility' );
}

/**
 * Dark mode hooks
 */
function aqualuxe_dark_mode_hooks() {
	/**
	 * Hook: aqualuxe_dark_mode
	 *
	 * @hooked aqualuxe_dark_mode_toggle - 10
	 * @hooked aqualuxe_dark_mode_class - 20
	 */
	do_action( 'aqualuxe_dark_mode' );
}

/**
 * Template hooks
 */
function aqualuxe_template_hooks() {
	/**
	 * Hook: aqualuxe_template_redirect
	 *
	 * @hooked aqualuxe_template_redirect_callback - 10
	 */
	do_action( 'aqualuxe_template_redirect' );

	/**
	 * Hook: aqualuxe_template_include
	 *
	 * @hooked aqualuxe_template_include_callback - 10
	 */
	do_action( 'aqualuxe_template_include' );
}

/**
 * Ajax hooks
 */
function aqualuxe_ajax_hooks() {
	/**
	 * Hook: aqualuxe_ajax_handler
	 *
	 * @hooked aqualuxe_ajax_handler_callback - 10
	 */
	do_action( 'aqualuxe_ajax_handler' );
}

/**
 * Admin hooks
 */
function aqualuxe_admin_hooks() {
	/**
	 * Hook: aqualuxe_admin_init
	 *
	 * @hooked aqualuxe_admin_init_callback - 10
	 */
	do_action( 'aqualuxe_admin_init' );

	/**
	 * Hook: aqualuxe_admin_menu
	 *
	 * @hooked aqualuxe_admin_menu_callback - 10
	 */
	do_action( 'aqualuxe_admin_menu' );
}

/**
 * Initialize all hooks
 */
function aqualuxe_init_hooks() {
	// Theme setup hooks
	add_action( 'after_setup_theme', 'aqualuxe_setup_hooks' );
	
	// Assets hooks
	add_action( 'wp_enqueue_scripts', 'aqualuxe_assets_hooks' );
	add_action( 'admin_enqueue_scripts', 'aqualuxe_admin_hooks' );
	
	// Template hooks
	add_action( 'template_redirect', 'aqualuxe_template_hooks' );
	
	// Widget hooks
	add_action( 'widgets_init', 'aqualuxe_widget_hooks' );
	
	// Customizer hooks
	add_action( 'customize_register', 'aqualuxe_customizer_hooks' );
	
	// Header hooks
	add_action( 'aqualuxe_header', 'aqualuxe_header_hooks' );
	
	// Footer hooks
	add_action( 'aqualuxe_footer', 'aqualuxe_footer_hooks' );
	
	// Content hooks
	add_action( 'aqualuxe_content', 'aqualuxe_content_hooks' );
	
	// Entry hooks
	add_action( 'aqualuxe_entry', 'aqualuxe_entry_hooks' );
	
	// Sidebar hooks
	add_action( 'aqualuxe_sidebar', 'aqualuxe_sidebar_hooks' );
	
	// Comments hooks
	add_action( 'aqualuxe_comments', 'aqualuxe_comments_hooks' );
	
	// SEO hooks
	add_action( 'wp_head', 'aqualuxe_seo_hooks' );
	
	// Performance hooks
	add_action( 'wp_head', 'aqualuxe_performance_hooks', 1 );
	
	// Accessibility hooks
	add_action( 'wp_footer', 'aqualuxe_accessibility_hooks' );
	
	// Dark mode hooks
	add_action( 'wp_body_open', 'aqualuxe_dark_mode_hooks' );
	
	// WooCommerce hooks
	if ( class_exists( 'WooCommerce' ) ) {
		add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_hooks' );
		add_action( 'woocommerce_before_single_product', 'aqualuxe_product_hooks' );
		add_action( 'woocommerce_before_cart', 'aqualuxe_cart_hooks' );
		add_action( 'woocommerce_before_checkout_form', 'aqualuxe_checkout_hooks' );
		add_action( 'woocommerce_account_navigation', 'aqualuxe_account_hooks' );
	}
	
	// Ajax hooks
	add_action( 'wp_ajax_aqualuxe_ajax', 'aqualuxe_ajax_hooks' );
	add_action( 'wp_ajax_nopriv_aqualuxe_ajax', 'aqualuxe_ajax_hooks' );
	
	// Admin hooks
	add_action( 'admin_init', 'aqualuxe_admin_hooks' );
}

// Initialize all hooks
aqualuxe_init_hooks();