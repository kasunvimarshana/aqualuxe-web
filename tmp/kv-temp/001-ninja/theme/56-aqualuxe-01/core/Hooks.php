<?php
/**
 * AquaLuxe Theme Hooks
 *
 * This file contains the Hooks class for the AquaLuxe theme.
 *
 * @package AquaLuxe\Core
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hooks class.
 */
class Hooks {
	/**
	 * Constructor.
	 */
	public function __construct() {
		// Register hooks.
		$this->register_hooks();
	}

	/**
	 * Register hooks.
	 */
	private function register_hooks() {
		// Header hooks.
		add_action( 'aqualuxe_header', [ $this, 'header_before' ], 5 );
		add_action( 'aqualuxe_header', [ $this, 'header_top' ], 10 );
		add_action( 'aqualuxe_header', [ $this, 'header_main' ], 20 );
		add_action( 'aqualuxe_header', [ $this, 'header_bottom' ], 30 );
		add_action( 'aqualuxe_header', [ $this, 'header_after' ], 35 );

		// Header components.
		add_action( 'aqualuxe_header_top', [ $this, 'header_top_left' ], 10 );
		add_action( 'aqualuxe_header_top', [ $this, 'header_top_right' ], 20 );
		add_action( 'aqualuxe_header_main', [ $this, 'header_logo' ], 10 );
		add_action( 'aqualuxe_header_main', [ $this, 'header_navigation' ], 20 );
		add_action( 'aqualuxe_header_main', [ $this, 'header_actions' ], 30 );
		add_action( 'aqualuxe_header_bottom', [ $this, 'header_bottom_content' ], 10 );

		// Footer hooks.
		add_action( 'aqualuxe_footer', [ $this, 'footer_before' ], 5 );
		add_action( 'aqualuxe_footer', [ $this, 'footer_top' ], 10 );
		add_action( 'aqualuxe_footer', [ $this, 'footer_main' ], 20 );
		add_action( 'aqualuxe_footer', [ $this, 'footer_bottom' ], 30 );
		add_action( 'aqualuxe_footer', [ $this, 'footer_after' ], 35 );

		// Footer components.
		add_action( 'aqualuxe_footer_top', [ $this, 'footer_top_content' ], 10 );
		add_action( 'aqualuxe_footer_main', [ $this, 'footer_widgets' ], 10 );
		add_action( 'aqualuxe_footer_bottom', [ $this, 'footer_copyright' ], 10 );
		add_action( 'aqualuxe_footer_bottom', [ $this, 'footer_menu' ], 20 );

		// Content hooks.
		add_action( 'aqualuxe_content_before', [ $this, 'content_before' ], 10 );
		add_action( 'aqualuxe_content_after', [ $this, 'content_after' ], 10 );
		add_action( 'aqualuxe_content_top', [ $this, 'content_top' ], 10 );
		add_action( 'aqualuxe_content_bottom', [ $this, 'content_bottom' ], 10 );

		// Sidebar hooks.
		add_action( 'aqualuxe_sidebar_before', [ $this, 'sidebar_before' ], 10 );
		add_action( 'aqualuxe_sidebar_after', [ $this, 'sidebar_after' ], 10 );
		add_action( 'aqualuxe_sidebar', [ $this, 'sidebar' ], 10 );

		// Post hooks.
		add_action( 'aqualuxe_post_before', [ $this, 'post_before' ], 10 );
		add_action( 'aqualuxe_post_after', [ $this, 'post_after' ], 10 );
		add_action( 'aqualuxe_post_top', [ $this, 'post_top' ], 10 );
		add_action( 'aqualuxe_post_bottom', [ $this, 'post_bottom' ], 10 );
		add_action( 'aqualuxe_post_content_before', [ $this, 'post_content_before' ], 10 );
		add_action( 'aqualuxe_post_content_after', [ $this, 'post_content_after' ], 10 );
		add_action( 'aqualuxe_post_header', [ $this, 'post_header' ], 10 );
		add_action( 'aqualuxe_post_footer', [ $this, 'post_footer' ], 10 );

		// Page hooks.
		add_action( 'aqualuxe_page_before', [ $this, 'page_before' ], 10 );
		add_action( 'aqualuxe_page_after', [ $this, 'page_after' ], 10 );
		add_action( 'aqualuxe_page_top', [ $this, 'page_top' ], 10 );
		add_action( 'aqualuxe_page_bottom', [ $this, 'page_bottom' ], 10 );
		add_action( 'aqualuxe_page_content_before', [ $this, 'page_content_before' ], 10 );
		add_action( 'aqualuxe_page_content_after', [ $this, 'page_content_after' ], 10 );
		add_action( 'aqualuxe_page_header', [ $this, 'page_header' ], 10 );
		add_action( 'aqualuxe_page_footer', [ $this, 'page_footer' ], 10 );

		// Archive hooks.
		add_action( 'aqualuxe_archive_before', [ $this, 'archive_before' ], 10 );
		add_action( 'aqualuxe_archive_after', [ $this, 'archive_after' ], 10 );
		add_action( 'aqualuxe_archive_top', [ $this, 'archive_top' ], 10 );
		add_action( 'aqualuxe_archive_bottom', [ $this, 'archive_bottom' ], 10 );
		add_action( 'aqualuxe_archive_header', [ $this, 'archive_header' ], 10 );
		add_action( 'aqualuxe_archive_footer', [ $this, 'archive_footer' ], 10 );

		// Search hooks.
		add_action( 'aqualuxe_search_before', [ $this, 'search_before' ], 10 );
		add_action( 'aqualuxe_search_after', [ $this, 'search_after' ], 10 );
		add_action( 'aqualuxe_search_top', [ $this, 'search_top' ], 10 );
		add_action( 'aqualuxe_search_bottom', [ $this, 'search_bottom' ], 10 );
		add_action( 'aqualuxe_search_header', [ $this, 'search_header' ], 10 );
		add_action( 'aqualuxe_search_footer', [ $this, 'search_footer' ], 10 );

		// 404 hooks.
		add_action( 'aqualuxe_404_before', [ $this, 'error_404_before' ], 10 );
		add_action( 'aqualuxe_404_after', [ $this, 'error_404_after' ], 10 );
		add_action( 'aqualuxe_404_top', [ $this, 'error_404_top' ], 10 );
		add_action( 'aqualuxe_404_bottom', [ $this, 'error_404_bottom' ], 10 );
		add_action( 'aqualuxe_404_header', [ $this, 'error_404_header' ], 10 );
		add_action( 'aqualuxe_404_content', [ $this, 'error_404_content' ], 10 );
		add_action( 'aqualuxe_404_footer', [ $this, 'error_404_footer' ], 10 );

		// Comments hooks.
		add_action( 'aqualuxe_comments_before', [ $this, 'comments_before' ], 10 );
		add_action( 'aqualuxe_comments_after', [ $this, 'comments_after' ], 10 );
		add_action( 'aqualuxe_comments_list', [ $this, 'comments_list' ], 10 );
		add_action( 'aqualuxe_comments_navigation', [ $this, 'comments_navigation' ], 10 );
		add_action( 'aqualuxe_comments_form', [ $this, 'comments_form' ], 10 );

		// WooCommerce hooks.
		if ( class_exists( 'WooCommerce' ) ) {
			$this->register_woocommerce_hooks();
		}
	}

	/**
	 * Register WooCommerce hooks.
	 */
	private function register_woocommerce_hooks() {
		// Shop hooks.
		add_action( 'aqualuxe_shop_before', [ $this, 'shop_before' ], 10 );
		add_action( 'aqualuxe_shop_after', [ $this, 'shop_after' ], 10 );
		add_action( 'aqualuxe_shop_top', [ $this, 'shop_top' ], 10 );
		add_action( 'aqualuxe_shop_bottom', [ $this, 'shop_bottom' ], 10 );
		add_action( 'aqualuxe_shop_header', [ $this, 'shop_header' ], 10 );
		add_action( 'aqualuxe_shop_footer', [ $this, 'shop_footer' ], 10 );

		// Product hooks.
		add_action( 'aqualuxe_product_before', [ $this, 'product_before' ], 10 );
		add_action( 'aqualuxe_product_after', [ $this, 'product_after' ], 10 );
		add_action( 'aqualuxe_product_top', [ $this, 'product_top' ], 10 );
		add_action( 'aqualuxe_product_bottom', [ $this, 'product_bottom' ], 10 );
		add_action( 'aqualuxe_product_header', [ $this, 'product_header' ], 10 );
		add_action( 'aqualuxe_product_footer', [ $this, 'product_footer' ], 10 );
		add_action( 'aqualuxe_product_content_before', [ $this, 'product_content_before' ], 10 );
		add_action( 'aqualuxe_product_content_after', [ $this, 'product_content_after' ], 10 );
		add_action( 'aqualuxe_product_summary_before', [ $this, 'product_summary_before' ], 10 );
		add_action( 'aqualuxe_product_summary_after', [ $this, 'product_summary_after' ], 10 );
		add_action( 'aqualuxe_product_gallery_before', [ $this, 'product_gallery_before' ], 10 );
		add_action( 'aqualuxe_product_gallery_after', [ $this, 'product_gallery_after' ], 10 );

		// Cart hooks.
		add_action( 'aqualuxe_cart_before', [ $this, 'cart_before' ], 10 );
		add_action( 'aqualuxe_cart_after', [ $this, 'cart_after' ], 10 );
		add_action( 'aqualuxe_cart_top', [ $this, 'cart_top' ], 10 );
		add_action( 'aqualuxe_cart_bottom', [ $this, 'cart_bottom' ], 10 );
		add_action( 'aqualuxe_cart_header', [ $this, 'cart_header' ], 10 );
		add_action( 'aqualuxe_cart_footer', [ $this, 'cart_footer' ], 10 );

		// Checkout hooks.
		add_action( 'aqualuxe_checkout_before', [ $this, 'checkout_before' ], 10 );
		add_action( 'aqualuxe_checkout_after', [ $this, 'checkout_after' ], 10 );
		add_action( 'aqualuxe_checkout_top', [ $this, 'checkout_top' ], 10 );
		add_action( 'aqualuxe_checkout_bottom', [ $this, 'checkout_bottom' ], 10 );
		add_action( 'aqualuxe_checkout_header', [ $this, 'checkout_header' ], 10 );
		add_action( 'aqualuxe_checkout_footer', [ $this, 'checkout_footer' ], 10 );

		// Account hooks.
		add_action( 'aqualuxe_account_before', [ $this, 'account_before' ], 10 );
		add_action( 'aqualuxe_account_after', [ $this, 'account_after' ], 10 );
		add_action( 'aqualuxe_account_top', [ $this, 'account_top' ], 10 );
		add_action( 'aqualuxe_account_bottom', [ $this, 'account_bottom' ], 10 );
		add_action( 'aqualuxe_account_header', [ $this, 'account_header' ], 10 );
		add_action( 'aqualuxe_account_footer', [ $this, 'account_footer' ], 10 );
	}

	/**
	 * Header hooks.
	 */
	public function header_before() {
		do_action( 'aqualuxe_header_before' );
	}

	public function header_top() {
		get_template_part( 'templates/parts/header/top' );
		do_action( 'aqualuxe_header_top' );
	}

	public function header_main() {
		get_template_part( 'templates/parts/header/main' );
		do_action( 'aqualuxe_header_main' );
	}

	public function header_bottom() {
		get_template_part( 'templates/parts/header/bottom' );
		do_action( 'aqualuxe_header_bottom' );
	}

	public function header_after() {
		do_action( 'aqualuxe_header_after' );
	}

	/**
	 * Header components.
	 */
	public function header_top_left() {
		get_template_part( 'templates/parts/header/top-left' );
		do_action( 'aqualuxe_header_top_left' );
	}

	public function header_top_right() {
		get_template_part( 'templates/parts/header/top-right' );
		do_action( 'aqualuxe_header_top_right' );
	}

	public function header_logo() {
		get_template_part( 'templates/parts/header/logo' );
		do_action( 'aqualuxe_header_logo' );
	}

	public function header_navigation() {
		get_template_part( 'templates/parts/header/navigation' );
		do_action( 'aqualuxe_header_navigation' );
	}

	public function header_actions() {
		get_template_part( 'templates/parts/header/actions' );
		do_action( 'aqualuxe_header_actions' );
	}

	public function header_bottom_content() {
		get_template_part( 'templates/parts/header/bottom-content' );
		do_action( 'aqualuxe_header_bottom_content' );
	}

	/**
	 * Footer hooks.
	 */
	public function footer_before() {
		do_action( 'aqualuxe_footer_before' );
	}

	public function footer_top() {
		get_template_part( 'templates/parts/footer/top' );
		do_action( 'aqualuxe_footer_top' );
	}

	public function footer_main() {
		get_template_part( 'templates/parts/footer/main' );
		do_action( 'aqualuxe_footer_main' );
	}

	public function footer_bottom() {
		get_template_part( 'templates/parts/footer/bottom' );
		do_action( 'aqualuxe_footer_bottom' );
	}

	public function footer_after() {
		do_action( 'aqualuxe_footer_after' );
	}

	/**
	 * Footer components.
	 */
	public function footer_top_content() {
		get_template_part( 'templates/parts/footer/top-content' );
		do_action( 'aqualuxe_footer_top_content' );
	}

	public function footer_widgets() {
		get_template_part( 'templates/parts/footer/widgets' );
		do_action( 'aqualuxe_footer_widgets' );
	}

	public function footer_copyright() {
		get_template_part( 'templates/parts/footer/copyright' );
		do_action( 'aqualuxe_footer_copyright' );
	}

	public function footer_menu() {
		get_template_part( 'templates/parts/footer/menu' );
		do_action( 'aqualuxe_footer_menu' );
	}

	/**
	 * Content hooks.
	 */
	public function content_before() {
		do_action( 'aqualuxe_content_before' );
	}

	public function content_after() {
		do_action( 'aqualuxe_content_after' );
	}

	public function content_top() {
		get_template_part( 'templates/parts/content/top' );
		do_action( 'aqualuxe_content_top' );
	}

	public function content_bottom() {
		get_template_part( 'templates/parts/content/bottom' );
		do_action( 'aqualuxe_content_bottom' );
	}

	/**
	 * Sidebar hooks.
	 */
	public function sidebar_before() {
		do_action( 'aqualuxe_sidebar_before' );
	}

	public function sidebar_after() {
		do_action( 'aqualuxe_sidebar_after' );
	}

	public function sidebar() {
		get_template_part( 'templates/parts/sidebar' );
		do_action( 'aqualuxe_sidebar' );
	}

	/**
	 * Post hooks.
	 */
	public function post_before() {
		do_action( 'aqualuxe_post_before' );
	}

	public function post_after() {
		do_action( 'aqualuxe_post_after' );
	}

	public function post_top() {
		get_template_part( 'templates/parts/post/top' );
		do_action( 'aqualuxe_post_top' );
	}

	public function post_bottom() {
		get_template_part( 'templates/parts/post/bottom' );
		do_action( 'aqualuxe_post_bottom' );
	}

	public function post_content_before() {
		do_action( 'aqualuxe_post_content_before' );
	}

	public function post_content_after() {
		do_action( 'aqualuxe_post_content_after' );
	}

	public function post_header() {
		get_template_part( 'templates/parts/post/header' );
		do_action( 'aqualuxe_post_header' );
	}

	public function post_footer() {
		get_template_part( 'templates/parts/post/footer' );
		do_action( 'aqualuxe_post_footer' );
	}

	/**
	 * Page hooks.
	 */
	public function page_before() {
		do_action( 'aqualuxe_page_before' );
	}

	public function page_after() {
		do_action( 'aqualuxe_page_after' );
	}

	public function page_top() {
		get_template_part( 'templates/parts/page/top' );
		do_action( 'aqualuxe_page_top' );
	}

	public function page_bottom() {
		get_template_part( 'templates/parts/page/bottom' );
		do_action( 'aqualuxe_page_bottom' );
	}

	public function page_content_before() {
		do_action( 'aqualuxe_page_content_before' );
	}

	public function page_content_after() {
		do_action( 'aqualuxe_page_content_after' );
	}

	public function page_header() {
		get_template_part( 'templates/parts/page/header' );
		do_action( 'aqualuxe_page_header' );
	}

	public function page_footer() {
		get_template_part( 'templates/parts/page/footer' );
		do_action( 'aqualuxe_page_footer' );
	}

	/**
	 * Archive hooks.
	 */
	public function archive_before() {
		do_action( 'aqualuxe_archive_before' );
	}

	public function archive_after() {
		do_action( 'aqualuxe_archive_after' );
	}

	public function archive_top() {
		get_template_part( 'templates/parts/archive/top' );
		do_action( 'aqualuxe_archive_top' );
	}

	public function archive_bottom() {
		get_template_part( 'templates/parts/archive/bottom' );
		do_action( 'aqualuxe_archive_bottom' );
	}

	public function archive_header() {
		get_template_part( 'templates/parts/archive/header' );
		do_action( 'aqualuxe_archive_header' );
	}

	public function archive_footer() {
		get_template_part( 'templates/parts/archive/footer' );
		do_action( 'aqualuxe_archive_footer' );
	}

	/**
	 * Search hooks.
	 */
	public function search_before() {
		do_action( 'aqualuxe_search_before' );
	}

	public function search_after() {
		do_action( 'aqualuxe_search_after' );
	}

	public function search_top() {
		get_template_part( 'templates/parts/search/top' );
		do_action( 'aqualuxe_search_top' );
	}

	public function search_bottom() {
		get_template_part( 'templates/parts/search/bottom' );
		do_action( 'aqualuxe_search_bottom' );
	}

	public function search_header() {
		get_template_part( 'templates/parts/search/header' );
		do_action( 'aqualuxe_search_header' );
	}

	public function search_footer() {
		get_template_part( 'templates/parts/search/footer' );
		do_action( 'aqualuxe_search_footer' );
	}

	/**
	 * 404 hooks.
	 */
	public function error_404_before() {
		do_action( 'aqualuxe_404_before' );
	}

	public function error_404_after() {
		do_action( 'aqualuxe_404_after' );
	}

	public function error_404_top() {
		get_template_part( 'templates/parts/404/top' );
		do_action( 'aqualuxe_404_top' );
	}

	public function error_404_bottom() {
		get_template_part( 'templates/parts/404/bottom' );
		do_action( 'aqualuxe_404_bottom' );
	}

	public function error_404_header() {
		get_template_part( 'templates/parts/404/header' );
		do_action( 'aqualuxe_404_header' );
	}

	public function error_404_content() {
		get_template_part( 'templates/parts/404/content' );
		do_action( 'aqualuxe_404_content' );
	}

	public function error_404_footer() {
		get_template_part( 'templates/parts/404/footer' );
		do_action( 'aqualuxe_404_footer' );
	}

	/**
	 * Comments hooks.
	 */
	public function comments_before() {
		do_action( 'aqualuxe_comments_before' );
	}

	public function comments_after() {
		do_action( 'aqualuxe_comments_after' );
	}

	public function comments_list() {
		get_template_part( 'templates/parts/comments/list' );
		do_action( 'aqualuxe_comments_list' );
	}

	public function comments_navigation() {
		get_template_part( 'templates/parts/comments/navigation' );
		do_action( 'aqualuxe_comments_navigation' );
	}

	public function comments_form() {
		get_template_part( 'templates/parts/comments/form' );
		do_action( 'aqualuxe_comments_form' );
	}

	/**
	 * WooCommerce hooks.
	 */
	public function shop_before() {
		do_action( 'aqualuxe_shop_before' );
	}

	public function shop_after() {
		do_action( 'aqualuxe_shop_after' );
	}

	public function shop_top() {
		get_template_part( 'templates/parts/shop/top' );
		do_action( 'aqualuxe_shop_top' );
	}

	public function shop_bottom() {
		get_template_part( 'templates/parts/shop/bottom' );
		do_action( 'aqualuxe_shop_bottom' );
	}

	public function shop_header() {
		get_template_part( 'templates/parts/shop/header' );
		do_action( 'aqualuxe_shop_header' );
	}

	public function shop_footer() {
		get_template_part( 'templates/parts/shop/footer' );
		do_action( 'aqualuxe_shop_footer' );
	}

	public function product_before() {
		do_action( 'aqualuxe_product_before' );
	}

	public function product_after() {
		do_action( 'aqualuxe_product_after' );
	}

	public function product_top() {
		get_template_part( 'templates/parts/product/top' );
		do_action( 'aqualuxe_product_top' );
	}

	public function product_bottom() {
		get_template_part( 'templates/parts/product/bottom' );
		do_action( 'aqualuxe_product_bottom' );
	}

	public function product_header() {
		get_template_part( 'templates/parts/product/header' );
		do_action( 'aqualuxe_product_header' );
	}

	public function product_footer() {
		get_template_part( 'templates/parts/product/footer' );
		do_action( 'aqualuxe_product_footer' );
	}

	public function product_content_before() {
		do_action( 'aqualuxe_product_content_before' );
	}

	public function product_content_after() {
		do_action( 'aqualuxe_product_content_after' );
	}

	public function product_summary_before() {
		do_action( 'aqualuxe_product_summary_before' );
	}

	public function product_summary_after() {
		do_action( 'aqualuxe_product_summary_after' );
	}

	public function product_gallery_before() {
		do_action( 'aqualuxe_product_gallery_before' );
	}

	public function product_gallery_after() {
		do_action( 'aqualuxe_product_gallery_after' );
	}

	public function cart_before() {
		do_action( 'aqualuxe_cart_before' );
	}

	public function cart_after() {
		do_action( 'aqualuxe_cart_after' );
	}

	public function cart_top() {
		get_template_part( 'templates/parts/cart/top' );
		do_action( 'aqualuxe_cart_top' );
	}

	public function cart_bottom() {
		get_template_part( 'templates/parts/cart/bottom' );
		do_action( 'aqualuxe_cart_bottom' );
	}

	public function cart_header() {
		get_template_part( 'templates/parts/cart/header' );
		do_action( 'aqualuxe_cart_header' );
	}

	public function cart_footer() {
		get_template_part( 'templates/parts/cart/footer' );
		do_action( 'aqualuxe_cart_footer' );
	}

	public function checkout_before() {
		do_action( 'aqualuxe_checkout_before' );
	}

	public function checkout_after() {
		do_action( 'aqualuxe_checkout_after' );
	}

	public function checkout_top() {
		get_template_part( 'templates/parts/checkout/top' );
		do_action( 'aqualuxe_checkout_top' );
	}

	public function checkout_bottom() {
		get_template_part( 'templates/parts/checkout/bottom' );
		do_action( 'aqualuxe_checkout_bottom' );
	}

	public function checkout_header() {
		get_template_part( 'templates/parts/checkout/header' );
		do_action( 'aqualuxe_checkout_header' );
	}

	public function checkout_footer() {
		get_template_part( 'templates/parts/checkout/footer' );
		do_action( 'aqualuxe_checkout_footer' );
	}

	public function account_before() {
		do_action( 'aqualuxe_account_before' );
	}

	public function account_after() {
		do_action( 'aqualuxe_account_after' );
	}

	public function account_top() {
		get_template_part( 'templates/parts/account/top' );
		do_action( 'aqualuxe_account_top' );
	}

	public function account_bottom() {
		get_template_part( 'templates/parts/account/bottom' );
		do_action( 'aqualuxe_account_bottom' );
	}

	public function account_header() {
		get_template_part( 'templates/parts/account/header' );
		do_action( 'aqualuxe_account_header' );
	}

	public function account_footer() {
		get_template_part( 'templates/parts/account/footer' );
		do_action( 'aqualuxe_account_footer' );
	}
}