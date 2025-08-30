<?php
/**
 * AquaLuxe WooCommerce Template Class
 *
 * @package AquaLuxe
 * @subpackage WooCommerce
 * @since 1.1.0
 */

namespace AquaLuxe\WooCommerce;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce Template Class
 *
 * Handles WooCommerce template customizations and overrides.
 *
 * @since 1.1.0
 */
class Template {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Template hooks.
		add_filter( 'woocommerce_locate_template', array( $this, 'locate_template' ), 10, 3 );
		add_filter( 'wc_get_template_part', array( $this, 'get_template_part' ), 10, 3 );
		
		// Layout hooks.
		add_action( 'woocommerce_before_main_content', array( $this, 'wrapper_start' ), 10 );
		add_action( 'woocommerce_after_main_content', array( $this, 'wrapper_end' ), 10 );
		add_action( 'woocommerce_sidebar', array( $this, 'get_sidebar' ), 10 );
		
		// Shop hooks.
		add_filter( 'woocommerce_product_loop_start', array( $this, 'product_loop_start' ), 10 );
		add_filter( 'woocommerce_product_loop_end', array( $this, 'product_loop_end' ), 10 );
		add_action( 'woocommerce_before_shop_loop', array( $this, 'shop_header' ), 10 );
		add_action( 'woocommerce_after_shop_loop', array( $this, 'shop_footer' ), 10 );
		
		// Product hooks.
		add_action( 'woocommerce_before_single_product', array( $this, 'product_header' ), 10 );
		add_action( 'woocommerce_after_single_product', array( $this, 'product_footer' ), 10 );
		
		// Cart hooks.
		add_action( 'woocommerce_before_cart', array( $this, 'cart_header' ), 10 );
		add_action( 'woocommerce_after_cart', array( $this, 'cart_footer' ), 10 );
		
		// Checkout hooks.
		add_action( 'woocommerce_before_checkout_form', array( $this, 'checkout_header' ), 10 );
		add_action( 'woocommerce_after_checkout_form', array( $this, 'checkout_footer' ), 10 );
		
		// Account hooks.
		add_action( 'woocommerce_before_account_navigation', array( $this, 'account_header' ), 10 );
		add_action( 'woocommerce_after_account_navigation', array( $this, 'account_footer' ), 10 );
	}

	/**
	 * Locate WooCommerce template.
	 *
	 * @param string $template      Template.
	 * @param string $template_name Template name.
	 * @param string $template_path Template path.
	 * @return string
	 */
	public function locate_template( $template, $template_name, $template_path ) {
		// Get theme template path.
		$theme_template = get_template_directory() . '/woocommerce/' . $template_name;
		
		// Return theme template if it exists.
		if ( file_exists( $theme_template ) ) {
			return $theme_template;
		}
		
		// Return original template.
		return $template;
	}

	/**
	 * Get WooCommerce template part.
	 *
	 * @param string $template Template.
	 * @param string $slug     Slug.
	 * @param string $name     Name.
	 * @return string
	 */
	public function get_template_part( $template, $slug, $name ) {
		// Get theme template part.
		$theme_template = get_template_directory() . '/woocommerce/' . $slug . '-' . $name . '.php';
		
		// Return theme template if it exists.
		if ( file_exists( $theme_template ) ) {
			return $theme_template;
		}
		
		// Return original template.
		return $template;
	}

	/**
	 * Wrapper start.
	 *
	 * @return void
	 */
	public function wrapper_start() {
		// Get shop layout.
		$shop_layout = get_theme_mod( 'aqualuxe_shop_layout', 'grid' );
		$shop_sidebar = get_theme_mod( 'aqualuxe_shop_sidebar', 'right' );
		
		// Get product layout.
		$product_layout = get_theme_mod( 'aqualuxe_product_layout', 'standard' );
		$product_sidebar = get_theme_mod( 'aqualuxe_product_sidebar', 'none' );
		
		// Get layout.
		$layout = '';
		$sidebar = '';
		
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			$layout = $shop_layout;
			$sidebar = $shop_sidebar;
		} elseif ( is_product() ) {
			$layout = $product_layout;
			$sidebar = $product_sidebar;
		}
		
		// Output wrapper.
		echo '<div class="woocommerce-content-wrapper layout-' . esc_attr( $layout ) . ' sidebar-' . esc_attr( $sidebar ) . '">';
		
		// Output main content.
		echo '<main class="woocommerce-main-content" role="main">';
	}

	/**
	 * Wrapper end.
	 *
	 * @return void
	 */
	public function wrapper_end() {
		// Close main content.
		echo '</main>';
		
		// Close wrapper.
		echo '</div>';
	}

	/**
	 * Get sidebar.
	 *
	 * @return void
	 */
	public function get_sidebar() {
		// Get shop sidebar.
		$shop_sidebar = get_theme_mod( 'aqualuxe_shop_sidebar', 'right' );
		
		// Get product sidebar.
		$product_sidebar = get_theme_mod( 'aqualuxe_product_sidebar', 'none' );
		
		// Get sidebar.
		$sidebar = '';
		
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			$sidebar = $shop_sidebar;
		} elseif ( is_product() ) {
			$sidebar = $product_sidebar;
		}
		
		// Check if sidebar is enabled.
		if ( 'none' === $sidebar ) {
			return;
		}
		
		// Get sidebar name.
		$sidebar_name = 'shop';
		
		if ( is_product() ) {
			$sidebar_name = 'product';
		}
		
		// Output sidebar.
		echo '<aside class="woocommerce-sidebar sidebar-' . esc_attr( $sidebar ) . '" role="complementary">';
		dynamic_sidebar( 'sidebar-' . $sidebar_name );
		echo '</aside>';
	}

	/**
	 * Product loop start.
	 *
	 * @param string $loop_start Loop start.
	 * @return string
	 */
	public function product_loop_start( $loop_start ) {
		// Get shop layout.
		$shop_layout = get_theme_mod( 'aqualuxe_shop_layout', 'grid' );
		
		// Get shop columns.
		$shop_columns = get_theme_mod( 'aqualuxe_shop_columns', 4 );
		
		// Replace loop start.
		$loop_start = '<ul class="products layout-' . esc_attr( $shop_layout ) . ' columns-' . esc_attr( $shop_columns ) . '">';
		
		return $loop_start;
	}

	/**
	 * Product loop end.
	 *
	 * @param string $loop_end Loop end.
	 * @return string
	 */
	public function product_loop_end( $loop_end ) {
		// Replace loop end.
		$loop_end = '</ul>';
		
		return $loop_end;
	}

	/**
	 * Shop header.
	 *
	 * @return void
	 */
	public function shop_header() {
		// Get shop header template.
		get_template_part( 'template-parts/woocommerce/shop', 'header' );
	}

	/**
	 * Shop footer.
	 *
	 * @return void
	 */
	public function shop_footer() {
		// Get shop footer template.
		get_template_part( 'template-parts/woocommerce/shop', 'footer' );
	}

	/**
	 * Product header.
	 *
	 * @return void
	 */
	public function product_header() {
		// Get product header template.
		get_template_part( 'template-parts/woocommerce/product', 'header' );
	}

	/**
	 * Product footer.
	 *
	 * @return void
	 */
	public function product_footer() {
		// Get product footer template.
		get_template_part( 'template-parts/woocommerce/product', 'footer' );
	}

	/**
	 * Cart header.
	 *
	 * @return void
	 */
	public function cart_header() {
		// Get cart header template.
		get_template_part( 'template-parts/woocommerce/cart', 'header' );
	}

	/**
	 * Cart footer.
	 *
	 * @return void
	 */
	public function cart_footer() {
		// Get cart footer template.
		get_template_part( 'template-parts/woocommerce/cart', 'footer' );
	}

	/**
	 * Checkout header.
	 *
	 * @return void
	 */
	public function checkout_header() {
		// Get checkout header template.
		get_template_part( 'template-parts/woocommerce/checkout', 'header' );
	}

	/**
	 * Checkout footer.
	 *
	 * @return void
	 */
	public function checkout_footer() {
		// Get checkout footer template.
		get_template_part( 'template-parts/woocommerce/checkout', 'footer' );
	}

	/**
	 * Account header.
	 *
	 * @return void
	 */
	public function account_header() {
		// Get account header template.
		get_template_part( 'template-parts/woocommerce/account', 'header' );
	}

	/**
	 * Account footer.
	 *
	 * @return void
	 */
	public function account_footer() {
		// Get account footer template.
		get_template_part( 'template-parts/woocommerce/account', 'footer' );
	}
}