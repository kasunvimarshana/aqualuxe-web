<?php
/**
 * Hooks system for AquaLuxe theme
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AquaLuxe Hooks Class
 */
class AquaLuxe_Hooks {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Register theme hooks.
		$this->register_hooks();
	}

	/**
	 * Register theme hooks
	 */
	public function register_hooks() {
		// Header hooks.
		add_action( 'aqualuxe_before_header', array( $this, 'before_header' ), 10 );
		add_action( 'aqualuxe_header', array( $this, 'header' ), 10 );
		add_action( 'aqualuxe_after_header', array( $this, 'after_header' ), 10 );
		
		// Content hooks.
		add_action( 'aqualuxe_before_content', array( $this, 'before_content' ), 10 );
		add_action( 'aqualuxe_content', array( $this, 'content' ), 10 );
		add_action( 'aqualuxe_after_content', array( $this, 'after_content' ), 10 );
		
		// Footer hooks.
		add_action( 'aqualuxe_before_footer', array( $this, 'before_footer' ), 10 );
		add_action( 'aqualuxe_footer', array( $this, 'footer' ), 10 );
		add_action( 'aqualuxe_after_footer', array( $this, 'after_footer' ), 10 );
		
		// Sidebar hooks.
		add_action( 'aqualuxe_before_sidebar', array( $this, 'before_sidebar' ), 10 );
		add_action( 'aqualuxe_sidebar', array( $this, 'sidebar' ), 10 );
		add_action( 'aqualuxe_after_sidebar', array( $this, 'after_sidebar' ), 10 );
		
		// Post hooks.
		add_action( 'aqualuxe_before_post', array( $this, 'before_post' ), 10 );
		add_action( 'aqualuxe_post_header', array( $this, 'post_header' ), 10 );
		add_action( 'aqualuxe_post_content', array( $this, 'post_content' ), 10 );
		add_action( 'aqualuxe_post_footer', array( $this, 'post_footer' ), 10 );
		add_action( 'aqualuxe_after_post', array( $this, 'after_post' ), 10 );
		
		// Page hooks.
		add_action( 'aqualuxe_before_page', array( $this, 'before_page' ), 10 );
		add_action( 'aqualuxe_page_header', array( $this, 'page_header' ), 10 );
		add_action( 'aqualuxe_page_content', array( $this, 'page_content' ), 10 );
		add_action( 'aqualuxe_page_footer', array( $this, 'page_footer' ), 10 );
		add_action( 'aqualuxe_after_page', array( $this, 'after_page' ), 10 );
		
		// Comment hooks.
		add_action( 'aqualuxe_before_comments', array( $this, 'before_comments' ), 10 );
		add_action( 'aqualuxe_comments', array( $this, 'comments' ), 10 );
		add_action( 'aqualuxe_after_comments', array( $this, 'after_comments' ), 10 );
		add_action( 'aqualuxe_comment_form', array( $this, 'comment_form' ), 10 );
		
		// Archive hooks.
		add_action( 'aqualuxe_before_archive', array( $this, 'before_archive' ), 10 );
		add_action( 'aqualuxe_archive_header', array( $this, 'archive_header' ), 10 );
		add_action( 'aqualuxe_archive_content', array( $this, 'archive_content' ), 10 );
		add_action( 'aqualuxe_archive_footer', array( $this, 'archive_footer' ), 10 );
		add_action( 'aqualuxe_after_archive', array( $this, 'after_archive' ), 10 );
		
		// Search hooks.
		add_action( 'aqualuxe_before_search', array( $this, 'before_search' ), 10 );
		add_action( 'aqualuxe_search_header', array( $this, 'search_header' ), 10 );
		add_action( 'aqualuxe_search_content', array( $this, 'search_content' ), 10 );
		add_action( 'aqualuxe_search_footer', array( $this, 'search_footer' ), 10 );
		add_action( 'aqualuxe_after_search', array( $this, 'after_search' ), 10 );
		
		// 404 hooks.
		add_action( 'aqualuxe_before_404', array( $this, 'before_404' ), 10 );
		add_action( 'aqualuxe_404_header', array( $this, 'header_404' ), 10 );
		add_action( 'aqualuxe_404_content', array( $this, 'content_404' ), 10 );
		add_action( 'aqualuxe_404_footer', array( $this, 'footer_404' ), 10 );
		add_action( 'aqualuxe_after_404', array( $this, 'after_404' ), 10 );
		
		// WooCommerce hooks.
		if ( class_exists( 'WooCommerce' ) ) {
			$this->register_woocommerce_hooks();
		}
	}

	/**
	 * Register WooCommerce hooks
	 */
	public function register_woocommerce_hooks() {
		// Shop hooks.
		add_action( 'aqualuxe_before_shop', array( $this, 'before_shop' ), 10 );
		add_action( 'aqualuxe_shop_header', array( $this, 'shop_header' ), 10 );
		add_action( 'aqualuxe_shop_content', array( $this, 'shop_content' ), 10 );
		add_action( 'aqualuxe_shop_footer', array( $this, 'shop_footer' ), 10 );
		add_action( 'aqualuxe_after_shop', array( $this, 'after_shop' ), 10 );
		
		// Product hooks.
		add_action( 'aqualuxe_before_product', array( $this, 'before_product' ), 10 );
		add_action( 'aqualuxe_product_header', array( $this, 'product_header' ), 10 );
		add_action( 'aqualuxe_product_content', array( $this, 'product_content' ), 10 );
		add_action( 'aqualuxe_product_footer', array( $this, 'product_footer' ), 10 );
		add_action( 'aqualuxe_after_product', array( $this, 'after_product' ), 10 );
		
		// Cart hooks.
		add_action( 'aqualuxe_before_cart', array( $this, 'before_cart' ), 10 );
		add_action( 'aqualuxe_cart_header', array( $this, 'cart_header' ), 10 );
		add_action( 'aqualuxe_cart_content', array( $this, 'cart_content' ), 10 );
		add_action( 'aqualuxe_cart_footer', array( $this, 'cart_footer' ), 10 );
		add_action( 'aqualuxe_after_cart', array( $this, 'after_cart' ), 10 );
		
		// Checkout hooks.
		add_action( 'aqualuxe_before_checkout', array( $this, 'before_checkout' ), 10 );
		add_action( 'aqualuxe_checkout_header', array( $this, 'checkout_header' ), 10 );
		add_action( 'aqualuxe_checkout_content', array( $this, 'checkout_content' ), 10 );
		add_action( 'aqualuxe_checkout_footer', array( $this, 'checkout_footer' ), 10 );
		add_action( 'aqualuxe_after_checkout', array( $this, 'after_checkout' ), 10 );
		
		// Account hooks.
		add_action( 'aqualuxe_before_account', array( $this, 'before_account' ), 10 );
		add_action( 'aqualuxe_account_header', array( $this, 'account_header' ), 10 );
		add_action( 'aqualuxe_account_content', array( $this, 'account_content' ), 10 );
		add_action( 'aqualuxe_account_footer', array( $this, 'account_footer' ), 10 );
		add_action( 'aqualuxe_after_account', array( $this, 'after_account' ), 10 );
	}

	/**
	 * Header hooks
	 */
	public function before_header() {
		/**
		 * Fires before the header.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_before_header_action' );
	}

	public function header() {
		/**
		 * Fires in the header.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_header_action' );
	}

	public function after_header() {
		/**
		 * Fires after the header.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_after_header_action' );
	}

	/**
	 * Content hooks
	 */
	public function before_content() {
		/**
		 * Fires before the content.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_before_content_action' );
	}

	public function content() {
		/**
		 * Fires in the content.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_content_action' );
	}

	public function after_content() {
		/**
		 * Fires after the content.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_after_content_action' );
	}

	/**
	 * Footer hooks
	 */
	public function before_footer() {
		/**
		 * Fires before the footer.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_before_footer_action' );
	}

	public function footer() {
		/**
		 * Fires in the footer.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_footer_action' );
	}

	public function after_footer() {
		/**
		 * Fires after the footer.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_after_footer_action' );
	}

	/**
	 * Sidebar hooks
	 */
	public function before_sidebar() {
		/**
		 * Fires before the sidebar.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_before_sidebar_action' );
	}

	public function sidebar() {
		/**
		 * Fires in the sidebar.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_sidebar_action' );
	}

	public function after_sidebar() {
		/**
		 * Fires after the sidebar.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_after_sidebar_action' );
	}

	/**
	 * Post hooks
	 */
	public function before_post() {
		/**
		 * Fires before the post.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_before_post_action' );
	}

	public function post_header() {
		/**
		 * Fires in the post header.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_post_header_action' );
	}

	public function post_content() {
		/**
		 * Fires in the post content.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_post_content_action' );
	}

	public function post_footer() {
		/**
		 * Fires in the post footer.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_post_footer_action' );
	}

	public function after_post() {
		/**
		 * Fires after the post.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_after_post_action' );
	}

	/**
	 * Page hooks
	 */
	public function before_page() {
		/**
		 * Fires before the page.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_before_page_action' );
	}

	public function page_header() {
		/**
		 * Fires in the page header.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_page_header_action' );
	}

	public function page_content() {
		/**
		 * Fires in the page content.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_page_content_action' );
	}

	public function page_footer() {
		/**
		 * Fires in the page footer.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_page_footer_action' );
	}

	public function after_page() {
		/**
		 * Fires after the page.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_after_page_action' );
	}

	/**
	 * Comment hooks
	 */
	public function before_comments() {
		/**
		 * Fires before the comments.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_before_comments_action' );
	}

	public function comments() {
		/**
		 * Fires in the comments.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_comments_action' );
	}

	public function after_comments() {
		/**
		 * Fires after the comments.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_after_comments_action' );
	}

	public function comment_form() {
		/**
		 * Fires in the comment form.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_comment_form_action' );
	}

	/**
	 * Archive hooks
	 */
	public function before_archive() {
		/**
		 * Fires before the archive.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_before_archive_action' );
	}

	public function archive_header() {
		/**
		 * Fires in the archive header.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_archive_header_action' );
	}

	public function archive_content() {
		/**
		 * Fires in the archive content.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_archive_content_action' );
	}

	public function archive_footer() {
		/**
		 * Fires in the archive footer.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_archive_footer_action' );
	}

	public function after_archive() {
		/**
		 * Fires after the archive.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_after_archive_action' );
	}

	/**
	 * Search hooks
	 */
	public function before_search() {
		/**
		 * Fires before the search.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_before_search_action' );
	}

	public function search_header() {
		/**
		 * Fires in the search header.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_search_header_action' );
	}

	public function search_content() {
		/**
		 * Fires in the search content.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_search_content_action' );
	}

	public function search_footer() {
		/**
		 * Fires in the search footer.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_search_footer_action' );
	}

	public function after_search() {
		/**
		 * Fires after the search.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_after_search_action' );
	}

	/**
	 * 404 hooks
	 */
	public function before_404() {
		/**
		 * Fires before the 404.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_before_404_action' );
	}

	public function header_404() {
		/**
		 * Fires in the 404 header.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_404_header_action' );
	}

	public function content_404() {
		/**
		 * Fires in the 404 content.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_404_content_action' );
	}

	public function footer_404() {
		/**
		 * Fires in the 404 footer.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_404_footer_action' );
	}

	public function after_404() {
		/**
		 * Fires after the 404.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_after_404_action' );
	}

	/**
	 * WooCommerce hooks
	 */
	public function before_shop() {
		/**
		 * Fires before the shop.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_before_shop_action' );
	}

	public function shop_header() {
		/**
		 * Fires in the shop header.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_shop_header_action' );
	}

	public function shop_content() {
		/**
		 * Fires in the shop content.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_shop_content_action' );
	}

	public function shop_footer() {
		/**
		 * Fires in the shop footer.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_shop_footer_action' );
	}

	public function after_shop() {
		/**
		 * Fires after the shop.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_after_shop_action' );
	}

	public function before_product() {
		/**
		 * Fires before the product.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_before_product_action' );
	}

	public function product_header() {
		/**
		 * Fires in the product header.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_product_header_action' );
	}

	public function product_content() {
		/**
		 * Fires in the product content.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_product_content_action' );
	}

	public function product_footer() {
		/**
		 * Fires in the product footer.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_product_footer_action' );
	}

	public function after_product() {
		/**
		 * Fires after the product.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_after_product_action' );
	}

	public function before_cart() {
		/**
		 * Fires before the cart.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_before_cart_action' );
	}

	public function cart_header() {
		/**
		 * Fires in the cart header.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_cart_header_action' );
	}

	public function cart_content() {
		/**
		 * Fires in the cart content.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_cart_content_action' );
	}

	public function cart_footer() {
		/**
		 * Fires in the cart footer.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_cart_footer_action' );
	}

	public function after_cart() {
		/**
		 * Fires after the cart.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_after_cart_action' );
	}

	public function before_checkout() {
		/**
		 * Fires before the checkout.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_before_checkout_action' );
	}

	public function checkout_header() {
		/**
		 * Fires in the checkout header.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_checkout_header_action' );
	}

	public function checkout_content() {
		/**
		 * Fires in the checkout content.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_checkout_content_action' );
	}

	public function checkout_footer() {
		/**
		 * Fires in the checkout footer.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_checkout_footer_action' );
	}

	public function after_checkout() {
		/**
		 * Fires after the checkout.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_after_checkout_action' );
	}

	public function before_account() {
		/**
		 * Fires before the account.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_before_account_action' );
	}

	public function account_header() {
		/**
		 * Fires in the account header.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_account_header_action' );
	}

	public function account_content() {
		/**
		 * Fires in the account content.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_account_content_action' );
	}

	public function account_footer() {
		/**
		 * Fires in the account footer.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_account_footer_action' );
	}

	public function after_account() {
		/**
		 * Fires after the account.
		 *
		 * @since 1.0.0
		 */
		do_action( 'aqualuxe_after_account_action' );
	}
}

// Initialize hooks.
new AquaLuxe_Hooks();

/**
 * Helper function to check if a hook has callbacks registered.
 *
 * @param string $hook Hook name.
 * @return bool
 */
function aqualuxe_has_hook( $hook ) {
	return has_action( $hook ) || has_filter( $hook );
}

/**
 * Helper function to get the number of callbacks registered to a hook.
 *
 * @param string $hook Hook name.
 * @return int
 */
function aqualuxe_get_hook_count( $hook ) {
	global $wp_filter;
	
	if ( ! isset( $wp_filter[ $hook ] ) ) {
		return 0;
	}
	
	return count( $wp_filter[ $hook ]->callbacks );
}

/**
 * Helper function to get all callbacks registered to a hook.
 *
 * @param string $hook Hook name.
 * @return array
 */
function aqualuxe_get_hook_callbacks( $hook ) {
	global $wp_filter;
	
	if ( ! isset( $wp_filter[ $hook ] ) ) {
		return array();
	}
	
	return $wp_filter[ $hook ]->callbacks;
}