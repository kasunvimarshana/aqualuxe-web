<?php
/**
 * AquaLuxe WooCommerce Class
 *
 * @package AquaLuxe
 * @subpackage WooCommerce
 * @since 1.2.0
 */

namespace AquaLuxe\WooCommerce;

use AquaLuxe\Core\Assets;
use AquaLuxe\Core\Template;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce Class
 *
 * This class handles all WooCommerce integration for the theme.
 *
 * @since 1.2.0
 */
class WooCommerce {

	/**
	 * The single instance of this class.
	 *
	 * @var WooCommerce
	 */
	private static $instance = null;

	/**
	 * Constructor.
	 */
	private function __construct() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Add theme support for WooCommerce.
		$this->add_theme_support();

		// Register hooks.
		$this->register_hooks();

		// Register assets.
		$this->register_assets();

		// Register body classes.
		$this->register_body_classes();
	}

	/**
	 * Get the single instance of this class.
	 *
	 * @return WooCommerce
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Add theme support for WooCommerce.
	 *
	 * @return void
	 */
	private function add_theme_support() {
		// Add theme support for WooCommerce.
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		// Declare WooCommerce support.
		add_theme_support(
			'woocommerce',
			array(
				'thumbnail_image_width' => 400,
				'single_image_width'    => 800,
				'product_grid'          => array(
					'default_rows'    => 3,
					'min_rows'        => 1,
					'max_rows'        => 8,
					'default_columns' => 4,
					'min_columns'     => 1,
					'max_columns'     => 6,
				),
			)
		);
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	private function register_hooks() {
		// Remove default WooCommerce styles.
		add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

		// Related products.
		add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );

		// Product thumbnails.
		add_filter( 'woocommerce_product_thumbnails_columns', array( $this, 'thumbnail_columns' ) );

		// Products per page.
		add_filter( 'loop_shop_per_page', array( $this, 'products_per_page' ) );

		// Product columns.
		add_filter( 'loop_shop_columns', array( $this, 'loop_columns' ) );

		// Breadcrumbs.
		add_filter( 'woocommerce_breadcrumb_defaults', array( $this, 'breadcrumb_defaults' ) );

		// Checkout fields.
		add_filter( 'woocommerce_checkout_fields', array( $this, 'checkout_fields' ) );

		// Cart fragments.
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_fragments' ) );
	}

	/**
	 * Register assets with the unified asset loading system.
	 *
	 * @return void
	 */
	private function register_assets() {
		$assets = Assets::get_instance();

		// Register WooCommerce styles.
		$assets->register_style(
			'aqualuxe-woocommerce',
			AQUALUXE_URI . '/assets/css/woocommerce.css',
			array( 'aqualuxe-main' ),
			AQUALUXE_VERSION
		);

		// Register WooCommerce scripts.
		$assets->register_script(
			'aqualuxe-woocommerce',
			AQUALUXE_URI . '/assets/js/woocommerce.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);

		// Add script localization.
		$assets->add_localization(
			'aqualuxe-woocommerce',
			'aqualuxeWooCommerce',
			array(
				'ajaxUrl'             => admin_url( 'admin-ajax.php' ),
				'nonce'               => wp_create_nonce( 'aqualuxe-woocommerce' ),
				'addToCartText'       => esc_html__( 'Add to cart', 'aqualuxe' ),
				'viewCartText'        => esc_html__( 'View cart', 'aqualuxe' ),
				'addingToCartText'    => esc_html__( 'Adding...', 'aqualuxe' ),
				'addedToCartText'     => esc_html__( 'Added!', 'aqualuxe' ),
				'wishlistAddText'     => esc_html__( 'Add to wishlist', 'aqualuxe' ),
				'wishlistAddedText'   => esc_html__( 'Added to wishlist', 'aqualuxe' ),
				'wishlistExistsText'  => esc_html__( 'Already in wishlist', 'aqualuxe' ),
				'quickViewText'       => esc_html__( 'Quick view', 'aqualuxe' ),
				'loadingText'         => esc_html__( 'Loading...', 'aqualuxe' ),
				'cartUrl'             => wc_get_cart_url(),
				'isRTL'               => is_rtl(),
			)
		);
	}

	/**
	 * Register body classes with the unified template system.
	 *
	 * @return void
	 */
	private function register_body_classes() {
		$template = Template::get_instance();

		// Add 'woocommerce-active' class.
		$template->register_body_class(
			function() {
				return class_exists( 'WooCommerce' );
			},
			'woocommerce-active'
		);

		// Add shop layout class.
		$template->register_body_class(
			function() {
				return is_shop() || is_product_category() || is_product_tag();
			},
			function() {
				$shop_layout = get_theme_mod( 'aqualuxe_shop_layout', 'grid' );
				return 'woocommerce-shop-layout-' . $shop_layout;
			}
		);

		// Add shop sidebar class.
		$template->register_body_class(
			function() {
				return is_shop() || is_product_category() || is_product_tag();
			},
			function() {
				$shop_sidebar = get_theme_mod( 'aqualuxe_shop_sidebar', 'right' );
				
				if ( 'none' === $shop_sidebar ) {
					return 'woocommerce-shop-no-sidebar';
				} else {
					return array(
						'woocommerce-shop-has-sidebar',
						'woocommerce-shop-sidebar-' . $shop_sidebar,
					);
				}
			}
		);

		// Add product layout class.
		$template->register_body_class(
			'is_product',
			function() {
				$product_layout = get_theme_mod( 'aqualuxe_product_layout', 'standard' );
				return 'woocommerce-product-layout-' . $product_layout;
			}
		);

		// Add product sidebar class.
		$template->register_body_class(
			'is_product',
			function() {
				$product_sidebar = get_theme_mod( 'aqualuxe_product_sidebar', 'none' );
				
				if ( 'none' === $product_sidebar ) {
					return 'woocommerce-product-no-sidebar';
				} else {
					return array(
						'woocommerce-product-has-sidebar',
						'woocommerce-product-sidebar-' . $product_sidebar,
					);
				}
			}
		);

		// Add cart layout class.
		$template->register_body_class(
			'is_cart',
			function() {
				$cart_layout = get_theme_mod( 'aqualuxe_cart_layout', 'standard' );
				return 'woocommerce-cart-layout-' . $cart_layout;
			}
		);

		// Add checkout layout class.
		$template->register_body_class(
			'is_checkout',
			function() {
				$checkout_layout = get_theme_mod( 'aqualuxe_checkout_layout', 'standard' );
				return 'woocommerce-checkout-layout-' . $checkout_layout;
			}
		);

		// Add account layout class.
		$template->register_body_class(
			'is_account_page',
			function() {
				$account_layout = get_theme_mod( 'aqualuxe_account_layout', 'standard' );
				return 'woocommerce-account-layout-' . $account_layout;
			}
		);
	}

	/**
	 * Related Products Args.
	 *
	 * @param array $args Related products args.
	 * @return array
	 */
	public function related_products_args( $args ) {
		$columns = get_theme_mod( 'aqualuxe_related_products_columns', 4 );
		$count   = get_theme_mod( 'aqualuxe_related_products_count', 4 );
		
		$args['posts_per_page'] = $count;
		$args['columns']        = $columns;
		
		return $args;
	}

	/**
	 * Product gallery thumbnail columns.
	 *
	 * @return int
	 */
	public function thumbnail_columns() {
		return get_theme_mod( 'aqualuxe_product_gallery_columns', 4 );
	}

	/**
	 * Products per page.
	 *
	 * @return int
	 */
	public function products_per_page() {
		return get_theme_mod( 'aqualuxe_products_per_page', 12 );
	}

	/**
	 * Product gallery thumbnail columns.
	 *
	 * @return int
	 */
	public function loop_columns() {
		return get_theme_mod( 'aqualuxe_shop_columns', 4 );
	}

	/**
	 * Breadcrumb defaults.
	 *
	 * @param array $defaults Breadcrumb defaults.
	 * @return array
	 */
	public function breadcrumb_defaults( $defaults ) {
		$defaults['delimiter']   = '<span class="breadcrumb-separator">/</span>';
		$defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';
		$defaults['wrap_after']  = '</nav>';
		
		return $defaults;
	}

	/**
	 * Checkout fields.
	 *
	 * @param array $fields Checkout fields.
	 * @return array
	 */
	public function checkout_fields( $fields ) {
		// Add placeholder text to fields.
		foreach ( $fields as $section => $section_fields ) {
			foreach ( $section_fields as $key => $field ) {
				if ( ! isset( $field['placeholder'] ) && isset( $field['label'] ) ) {
					$fields[ $section ][ $key ]['placeholder'] = $field['label'];
				}
			}
		}
		
		return $fields;
	}

	/**
	 * Cart fragments.
	 *
	 * @param array $fragments Cart fragments.
	 * @return array
	 */
	public function cart_fragments( $fragments ) {
		$fragments['span.cart-count'] = '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
		
		return $fragments;
	}
}