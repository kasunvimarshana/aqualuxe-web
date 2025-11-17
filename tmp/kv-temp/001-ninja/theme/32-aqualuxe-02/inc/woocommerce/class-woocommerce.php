<?php
/**
 * AquaLuxe WooCommerce Integration
 *
 * @package AquaLuxe
 * @since 1.1.0
 */

namespace AquaLuxe\WooCommerce;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce Integration Class
 *
 * Handles WooCommerce integration and customization.
 *
 * @since 1.1.0
 */
class WooCommerce {

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

		// Setup.
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		
		// Styles and scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		// Layout.
		add_filter( 'body_class', array( $this, 'body_classes' ) );
		add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );
		add_filter( 'woocommerce_product_thumbnails_columns', array( $this, 'thumbnail_columns' ) );
		add_filter( 'woocommerce_breadcrumb_defaults', array( $this, 'breadcrumb_defaults' ) );
		
		// Cart fragments.
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_link_fragment' ) );
		
		// Checkout.
		add_filter( 'woocommerce_checkout_fields', array( $this, 'checkout_fields' ) );
		
		// Account.
		add_filter( 'woocommerce_account_menu_items', array( $this, 'account_menu_items' ) );
		
		// Product display.
		add_action( 'woocommerce_before_shop_loop_item', array( $this, 'product_wrapper_open' ), 5 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'product_wrapper_close' ), 15 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_image_wrapper_open' ), 5 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_image_wrapper_close' ), 15 );
		
		// Quick view.
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'quick_view_button' ), 10 );
		add_action( 'wp_footer', array( $this, 'quick_view_modal' ) );
		add_action( 'wp_ajax_aqualuxe_quick_view', array( $this, 'quick_view_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', array( $this, 'quick_view_ajax' ) );
		
		// Wishlist.
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'wishlist_button' ), 11 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'wishlist_button' ), 35 );
		add_action( 'wp_ajax_aqualuxe_wishlist_add', array( $this, 'wishlist_add_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_wishlist_add', array( $this, 'wishlist_add_ajax' ) );
		
		// AJAX cart.
		add_action( 'wp_ajax_aqualuxe_add_to_cart', array( $this, 'add_to_cart_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_add_to_cart', array( $this, 'add_to_cart_ajax' ) );
		add_action( 'wp_ajax_aqualuxe_update_cart', array( $this, 'update_cart_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_update_cart', array( $this, 'update_cart_ajax' ) );
		
		// Mini cart.
		add_action( 'aqualuxe_header_cart', array( $this, 'header_cart' ) );
		
		// Product filters.
		add_action( 'woocommerce_before_shop_loop', array( $this, 'product_filter' ), 30 );
		
		// Product badges.
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_badges' ), 10 );
		
		// Product tabs.
		add_filter( 'woocommerce_product_tabs', array( $this, 'product_tabs' ) );
		
		// Upsells and related products.
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'recently_viewed_products' ), 21 );
		
		// Structured data.
		add_filter( 'woocommerce_structured_data_product', array( $this, 'structured_data' ), 10, 2 );
	}

	/**
	 * WooCommerce setup function.
	 *
	 * @return void
	 */
	public function setup() {
		// Add theme support for WooCommerce.
		add_theme_support( 'woocommerce' );
		
		// Add support for WooCommerce features.
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
	 * Enqueue WooCommerce styles.
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		
		// Register WooCommerce styles.
		wp_register_style(
			'aqualuxe-woocommerce',
			AQUALUXE_URI . '/assets/css/woocommerce.css',
			array( 'aqualuxe-style' ),
			AQUALUXE_VERSION
		);
		
		// Enqueue WooCommerce styles.
		wp_enqueue_style( 'aqualuxe-woocommerce' );
		
		// Add RTL support.
		wp_style_add_data( 'aqualuxe-woocommerce', 'rtl', 'replace' );
	}

	/**
	 * Enqueue WooCommerce scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		
		// Register WooCommerce scripts.
		wp_register_script(
			'aqualuxe-woocommerce',
			AQUALUXE_URI . '/assets/js/woocommerce.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
		
		// Enqueue WooCommerce scripts.
		wp_enqueue_script( 'aqualuxe-woocommerce' );
		
		// Localize WooCommerce scripts.
		wp_localize_script(
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
	 * Add WooCommerce specific classes to the body tag.
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public function body_classes( $classes ) {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return $classes;
		}
		
		// Add WooCommerce class.
		$classes[] = 'woocommerce-active';
		
		// Add shop layout class.
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			$shop_layout = get_theme_mod( 'aqualuxe_shop_layout', 'grid' );
			$classes[]   = 'woocommerce-shop-layout-' . $shop_layout;
			
			// Add sidebar class.
			$shop_sidebar = get_theme_mod( 'aqualuxe_shop_sidebar', 'right' );
			
			if ( 'none' === $shop_sidebar ) {
				$classes[] = 'woocommerce-shop-no-sidebar';
			} else {
				$classes[] = 'woocommerce-shop-has-sidebar';
				$classes[] = 'woocommerce-shop-sidebar-' . $shop_sidebar;
			}
		}
		
		// Add product layout class.
		if ( is_product() ) {
			$product_layout = get_theme_mod( 'aqualuxe_product_layout', 'standard' );
			$classes[]      = 'woocommerce-product-layout-' . $product_layout;
			
			// Add sidebar class.
			$product_sidebar = get_theme_mod( 'aqualuxe_product_sidebar', 'none' );
			
			if ( 'none' === $product_sidebar ) {
				$classes[] = 'woocommerce-product-no-sidebar';
			} else {
				$classes[] = 'woocommerce-product-has-sidebar';
				$classes[] = 'woocommerce-product-sidebar-' . $product_sidebar;
			}
		}
		
		// Add cart layout class.
		if ( is_cart() ) {
			$cart_layout = get_theme_mod( 'aqualuxe_cart_layout', 'standard' );
			$classes[]   = 'woocommerce-cart-layout-' . $cart_layout;
		}
		
		// Add checkout layout class.
		if ( is_checkout() ) {
			$checkout_layout = get_theme_mod( 'aqualuxe_checkout_layout', 'standard' );
			$classes[]       = 'woocommerce-checkout-layout-' . $checkout_layout;
		}
		
		// Add account layout class.
		if ( is_account_page() ) {
			$account_layout = get_theme_mod( 'aqualuxe_account_layout', 'standard' );
			$classes[]      = 'woocommerce-account-layout-' . $account_layout;
		}
		
		return $classes;
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
	 * @param int $columns Product thumbnails columns.
	 * @return int
	 */
	public function thumbnail_columns( $columns ) {
		$columns = get_theme_mod( 'aqualuxe_product_gallery_columns', 4 );
		return $columns;
	}

	/**
	 * Breadcrumb defaults.
	 *
	 * @param array $defaults Breadcrumb defaults.
	 * @return array
	 */
	public function breadcrumb_defaults( $defaults ) {
		$defaults['delimiter']   = '<span class="breadcrumb-separator">' . esc_html( get_theme_mod( 'aqualuxe_breadcrumb_separator', '/' ) ) . '</span>';
		$defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';
		$defaults['wrap_after']  = '</nav>';
		
		return $defaults;
	}

	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array
	 */
	public function cart_link_fragment( $fragments ) {
		ob_start();
		$this->cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();
		
		ob_start();
		$this->cart_count();
		$fragments['span.cart-count'] = ob_get_clean();
		
		ob_start();
		$this->mini_cart();
		$fragments['div.mini-cart-content'] = ob_get_clean();
		
		return $fragments;
	}

	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	public function cart_link() {
		?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
			<?php $this->cart_count(); ?>
			<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
		</a>
		<?php
	}

	/**
	 * Cart Count.
	 *
	 * Displayed the number of items in the cart.
	 *
	 * @return void
	 */
	public function cart_count() {
		$item_count = WC()->cart->get_cart_contents_count();
		?>
		<span class="cart-count">
			<?php
			/* translators: %d: number of items in cart */
			echo wp_kses_data( sprintf( _n( '%d item', '%d items', $item_count, 'aqualuxe' ), $item_count ) );
			?>
		</span>
		<?php
	}

	/**
	 * Mini Cart.
	 *
	 * Displayed the mini cart.
	 *
	 * @return void
	 */
	public function mini_cart() {
		if ( ! function_exists( 'woocommerce_mini_cart' ) ) {
			return;
		}
		?>
		<div class="mini-cart-content">
			<?php woocommerce_mini_cart(); ?>
		</div>
		<?php
	}

	/**
	 * Header Cart.
	 *
	 * Display Header Cart.
	 *
	 * @return void
	 */
	public function header_cart() {
		if ( ! function_exists( 'woocommerce_mini_cart' ) ) {
			return;
		}
		?>
		<div class="site-header-cart">
			<div class="cart-header">
				<?php $this->cart_link(); ?>
			</div>
			<div class="cart-dropdown">
				<?php $this->mini_cart(); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Checkout Fields.
	 *
	 * Customize checkout fields.
	 *
	 * @param array $fields Checkout fields.
	 * @return array
	 */
	public function checkout_fields( $fields ) {
		// Add placeholder to fields.
		foreach ( $fields as $section => $section_fields ) {
			foreach ( $section_fields as $key => $field ) {
				if ( ! isset( $field['placeholder'] ) && isset( $field['label'] ) ) {
					$fields[ $section ][ $key ]['placeholder'] = $field['label'];
				}
			}
		}
		
		// Make company field optional.
		if ( isset( $fields['billing']['billing_company'] ) ) {
			$fields['billing']['billing_company']['required'] = false;
		}
		
		if ( isset( $fields['shipping']['shipping_company'] ) ) {
			$fields['shipping']['shipping_company']['required'] = false;
		}
		
		// Add custom fields.
		if ( get_theme_mod( 'aqualuxe_checkout_order_notes', true ) ) {
			$fields['order']['order_comments']['placeholder'] = esc_html__( 'Notes about your order, e.g. special notes for delivery.', 'aqualuxe' );
		} else {
			unset( $fields['order']['order_comments'] );
		}
		
		return $fields;
	}

	/**
	 * Account Menu Items.
	 *
	 * Customize account menu items.
	 *
	 * @param array $items Account menu items.
	 * @return array
	 */
	public function account_menu_items( $items ) {
		// Reorder items.
		$ordered_items = array();
		
		// Dashboard.
		if ( isset( $items['dashboard'] ) ) {
			$ordered_items['dashboard'] = $items['dashboard'];
		}
		
		// Orders.
		if ( isset( $items['orders'] ) ) {
			$ordered_items['orders'] = $items['orders'];
		}
		
		// Downloads.
		if ( isset( $items['downloads'] ) ) {
			$ordered_items['downloads'] = $items['downloads'];
		}
		
		// Addresses.
		if ( isset( $items['edit-address'] ) ) {
			$ordered_items['edit-address'] = $items['edit-address'];
		}
		
		// Payment methods.
		if ( isset( $items['payment-methods'] ) ) {
			$ordered_items['payment-methods'] = $items['payment-methods'];
		}
		
		// Wishlist.
		if ( get_theme_mod( 'aqualuxe_enable_wishlist', true ) ) {
			$ordered_items['wishlist'] = esc_html__( 'Wishlist', 'aqualuxe' );
		}
		
		// Account details.
		if ( isset( $items['edit-account'] ) ) {
			$ordered_items['edit-account'] = $items['edit-account'];
		}
		
		// Logout.
		if ( isset( $items['customer-logout'] ) ) {
			$ordered_items['customer-logout'] = $items['customer-logout'];
		}
		
		return $ordered_items;
	}

	/**
	 * Product Wrapper Open.
	 *
	 * Add product wrapper opening tag.
	 *
	 * @return void
	 */
	public function product_wrapper_open() {
		echo '<div class="product-wrapper">';
	}

	/**
	 * Product Wrapper Close.
	 *
	 * Add product wrapper closing tag.
	 *
	 * @return void
	 */
	public function product_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Product Image Wrapper Open.
	 *
	 * Add product image wrapper opening tag.
	 *
	 * @return void
	 */
	public function product_image_wrapper_open() {
		echo '<div class="product-image-wrapper">';
	}

	/**
	 * Product Image Wrapper Close.
	 *
	 * Add product image wrapper closing tag.
	 *
	 * @return void
	 */
	public function product_image_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Quick View Button.
	 *
	 * Add quick view button to product.
	 *
	 * @return void
	 */
	public function quick_view_button() {
		// Check if quick view is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_quick_view', true ) ) {
			return;
		}
		
		global $product;
		
		echo '<div class="quick-view-button">';
		echo '<a href="#" class="button quick-view" data-product-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__( 'Quick View', 'aqualuxe' ) . '</a>';
		echo '</div>';
	}

	/**
	 * Quick View Modal.
	 *
	 * Add quick view modal to footer.
	 *
	 * @return void
	 */
	public function quick_view_modal() {
		// Check if quick view is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_quick_view', true ) ) {
			return;
		}
		?>
		<div id="quick-view-modal" class="quick-view-modal">
			<div class="quick-view-modal-content">
				<span class="quick-view-close">&times;</span>
				<div class="quick-view-content"></div>
			</div>
		</div>
		<?php
	}

	/**
	 * Quick View AJAX.
	 *
	 * Handle quick view AJAX request.
	 *
	 * @return void
	 */
	public function quick_view_ajax() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-woocommerce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ) );
		}
		
		// Check product ID.
		if ( ! isset( $_POST['product_id'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID.', 'aqualuxe' ) ) );
		}
		
		// Get product.
		$product_id = absint( $_POST['product_id'] );
		$product    = wc_get_product( $product_id );
		
		if ( ! $product ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Product not found.', 'aqualuxe' ) ) );
		}
		
		// Get quick view content.
		ob_start();
		include get_template_directory() . '/woocommerce/quick-view-content.php';
		$content = ob_get_clean();
		
		wp_send_json_success( array( 'content' => $content ) );
	}

	/**
	 * Wishlist Button.
	 *
	 * Add wishlist button to product.
	 *
	 * @return void
	 */
	public function wishlist_button() {
		// Check if wishlist is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_wishlist', true ) ) {
			return;
		}
		
		global $product;
		
		// Check if product is in wishlist.
		$wishlist = $this->get_wishlist();
		$in_wishlist = in_array( $product->get_id(), $wishlist, true );
		
		echo '<div class="wishlist-button">';
		echo '<a href="#" class="button wishlist-add' . ( $in_wishlist ? ' in-wishlist' : '' ) . '" data-product-id="' . esc_attr( $product->get_id() ) . '">';
		
		if ( $in_wishlist ) {
			echo '<span class="wishlist-text">' . esc_html__( 'Added to wishlist', 'aqualuxe' ) . '</span>';
		} else {
			echo '<span class="wishlist-text">' . esc_html__( 'Add to wishlist', 'aqualuxe' ) . '</span>';
		}
		
		echo '</a>';
		echo '</div>';
	}

	/**
	 * Wishlist Add AJAX.
	 *
	 * Handle wishlist add AJAX request.
	 *
	 * @return void
	 */
	public function wishlist_add_ajax() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-woocommerce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ) );
		}
		
		// Check product ID.
		if ( ! isset( $_POST['product_id'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID.', 'aqualuxe' ) ) );
		}
		
		// Get product.
		$product_id = absint( $_POST['product_id'] );
		$product    = wc_get_product( $product_id );
		
		if ( ! $product ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Product not found.', 'aqualuxe' ) ) );
		}
		
		// Get wishlist.
		$wishlist = $this->get_wishlist();
		
		// Check if product is in wishlist.
		$in_wishlist = in_array( $product_id, $wishlist, true );
		
		if ( $in_wishlist ) {
			// Remove from wishlist.
			$wishlist = array_diff( $wishlist, array( $product_id ) );
			$this->set_wishlist( $wishlist );
			
			wp_send_json_success(
				array(
					'message'    => esc_html__( 'Product removed from wishlist.', 'aqualuxe' ),
					'in_wishlist' => false,
					'text'       => esc_html__( 'Add to wishlist', 'aqualuxe' ),
				)
			);
		} else {
			// Add to wishlist.
			$wishlist[] = $product_id;
			$this->set_wishlist( $wishlist );
			
			wp_send_json_success(
				array(
					'message'    => esc_html__( 'Product added to wishlist.', 'aqualuxe' ),
					'in_wishlist' => true,
					'text'       => esc_html__( 'Added to wishlist', 'aqualuxe' ),
				)
			);
		}
	}

	/**
	 * Get Wishlist.
	 *
	 * Get wishlist from cookie or user meta.
	 *
	 * @return array
	 */
	private function get_wishlist() {
		// Check if user is logged in.
		if ( is_user_logged_in() ) {
			// Get wishlist from user meta.
			$wishlist = get_user_meta( get_current_user_id(), 'aqualuxe_wishlist', true );
			
			if ( ! $wishlist ) {
				$wishlist = array();
			}
		} else {
			// Get wishlist from cookie.
			$wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : array();
			
			if ( ! $wishlist ) {
				$wishlist = array();
			}
		}
		
		return $wishlist;
	}

	/**
	 * Set Wishlist.
	 *
	 * Set wishlist to cookie or user meta.
	 *
	 * @param array $wishlist Wishlist.
	 * @return void
	 */
	private function set_wishlist( $wishlist ) {
		// Check if user is logged in.
		if ( is_user_logged_in() ) {
			// Set wishlist to user meta.
			update_user_meta( get_current_user_id(), 'aqualuxe_wishlist', $wishlist );
		} else {
			// Set wishlist to cookie.
			setcookie( 'aqualuxe_wishlist', wp_json_encode( $wishlist ), time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
		}
	}

	/**
	 * Add to Cart AJAX.
	 *
	 * Handle add to cart AJAX request.
	 *
	 * @return void
	 */
	public function add_to_cart_ajax() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-woocommerce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ) );
		}
		
		// Check product ID.
		if ( ! isset( $_POST['product_id'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID.', 'aqualuxe' ) ) );
		}
		
		// Get product.
		$product_id = absint( $_POST['product_id'] );
		$product    = wc_get_product( $product_id );
		
		if ( ! $product ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Product not found.', 'aqualuxe' ) ) );
		}
		
		// Get quantity.
		$quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
		
		// Get variation ID.
		$variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
		
		// Get variation attributes.
		$variation = array();
		
		if ( $variation_id ) {
			foreach ( $_POST as $key => $value ) {
				if ( strpos( $key, 'attribute_' ) === 0 ) {
					$variation[ sanitize_title( wp_unslash( $key ) ) ] = wp_unslash( $value );
				}
			}
		}
		
		// Add to cart.
		$cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
		
		if ( ! $cart_item_key ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Failed to add product to cart.', 'aqualuxe' ) ) );
		}
		
		// Get cart fragments.
		$fragments = $this->cart_link_fragment( array() );
		
		wp_send_json_success(
			array(
				'message'   => esc_html__( 'Product added to cart.', 'aqualuxe' ),
				'fragments' => $fragments,
			)
		);
	}

	/**
	 * Update Cart AJAX.
	 *
	 * Handle update cart AJAX request.
	 *
	 * @return void
	 */
	public function update_cart_ajax() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-woocommerce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ) );
		}
		
		// Check cart item key.
		if ( ! isset( $_POST['cart_item_key'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid cart item key.', 'aqualuxe' ) ) );
		}
		
		// Get cart item key.
		$cart_item_key = sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) );
		
		// Check quantity.
		if ( ! isset( $_POST['quantity'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid quantity.', 'aqualuxe' ) ) );
		}
		
		// Get quantity.
		$quantity = absint( $_POST['quantity'] );
		
		// Update cart.
		WC()->cart->set_quantity( $cart_item_key, $quantity );
		
		// Get cart fragments.
		$fragments = $this->cart_link_fragment( array() );
		
		wp_send_json_success(
			array(
				'message'   => esc_html__( 'Cart updated.', 'aqualuxe' ),
				'fragments' => $fragments,
			)
		);
	}

	/**
	 * Product Filter.
	 *
	 * Add product filter to shop page.
	 *
	 * @return void
	 */
	public function product_filter() {
		// Check if product filter is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_product_filter', true ) ) {
			return;
		}
		
		// Get filter type.
		$filter_type = get_theme_mod( 'aqualuxe_product_filter_type', 'sidebar' );
		
		// Load filter template.
		get_template_part( 'template-parts/woocommerce/filter', $filter_type );
	}

	/**
	 * Product Badges.
	 *
	 * Add product badges to product.
	 *
	 * @return void
	 */
	public function product_badges() {
		global $product;
		
		// Check if product badges are enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_product_badges', true ) ) {
			return;
		}
		
		// Get badge position.
		$badge_position = get_theme_mod( 'aqualuxe_product_badge_position', 'top-left' );
		
		echo '<div class="product-badges position-' . esc_attr( $badge_position ) . '">';
		
		// Sale badge.
		if ( $product->is_on_sale() && get_theme_mod( 'aqualuxe_enable_sale_badge', true ) ) {
			echo '<span class="badge sale-badge">' . esc_html__( 'Sale', 'aqualuxe' ) . '</span>';
		}
		
		// New badge.
		$days_new = get_theme_mod( 'aqualuxe_product_new_days', 7 );
		$product_date = strtotime( $product->get_date_created() );
		$now = time();
		$days_since = floor( ( $now - $product_date ) / DAY_IN_SECONDS );
		
		if ( $days_since <= $days_new && get_theme_mod( 'aqualuxe_enable_new_badge', true ) ) {
			echo '<span class="badge new-badge">' . esc_html__( 'New', 'aqualuxe' ) . '</span>';
		}
		
		// Featured badge.
		if ( $product->is_featured() && get_theme_mod( 'aqualuxe_enable_featured_badge', true ) ) {
			echo '<span class="badge featured-badge">' . esc_html__( 'Featured', 'aqualuxe' ) . '</span>';
		}
		
		// Out of stock badge.
		if ( ! $product->is_in_stock() && get_theme_mod( 'aqualuxe_enable_out_of_stock_badge', true ) ) {
			echo '<span class="badge out-of-stock-badge">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
		}
		
		// Custom badge.
		$custom_badge = get_post_meta( $product->get_id(), '_aqualuxe_custom_badge', true );
		
		if ( $custom_badge && get_theme_mod( 'aqualuxe_enable_custom_badge', true ) ) {
			echo '<span class="badge custom-badge">' . esc_html( $custom_badge ) . '</span>';
		}
		
		echo '</div>';
	}

	/**
	 * Product Tabs.
	 *
	 * Customize product tabs.
	 *
	 * @param array $tabs Product tabs.
	 * @return array
	 */
	public function product_tabs( $tabs ) {
		// Check if custom tabs are enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_custom_tabs', true ) ) {
			return $tabs;
		}
		
		// Get custom tabs.
		$custom_tabs = get_theme_mod( 'aqualuxe_custom_tabs', array() );
		
		if ( ! empty( $custom_tabs ) ) {
			foreach ( $custom_tabs as $key => $tab ) {
				$tabs[ 'custom_tab_' . $key ] = array(
					'title'    => $tab['title'],
					'priority' => 50 + $key,
					'callback' => function() use ( $tab ) {
						echo wp_kses_post( wpautop( $tab['content'] ) );
					},
				);
			}
		}
		
		return $tabs;
	}

	/**
	 * Recently Viewed Products.
	 *
	 * Add recently viewed products to single product page.
	 *
	 * @return void
	 */
	public function recently_viewed_products() {
		// Check if recently viewed products are enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_recently_viewed', true ) ) {
			return;
		}
		
		// Get current product ID.
		$product_id = get_the_ID();
		
		// Get recently viewed products.
		$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? explode( '|', sanitize_text_field( wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) ) : array();
		
		// Remove current product.
		$viewed_products = array_diff( $viewed_products, array( $product_id ) );
		
		// Check if there are any viewed products.
		if ( empty( $viewed_products ) ) {
			return;
		}
		
		// Get number of products to show.
		$limit = get_theme_mod( 'aqualuxe_recently_viewed_count', 4 );
		
		// Get products.
		$args = array(
			'post_type'      => 'product',
			'post__in'       => $viewed_products,
			'posts_per_page' => $limit,
			'orderby'        => 'post__in',
			'post_status'    => 'publish',
		);
		
		$products = new \WP_Query( $args );
		
		if ( ! $products->have_posts() ) {
			return;
		}
		
		// Get columns.
		$columns = get_theme_mod( 'aqualuxe_recently_viewed_columns', 4 );
		
		// Output products.
		woocommerce_product_loop_start();
		
		while ( $products->have_posts() ) {
			$products->the_post();
			wc_get_template_part( 'content', 'product' );
		}
		
		woocommerce_product_loop_end();
		
		wp_reset_postdata();
	}

	/**
	 * Structured Data.
	 *
	 * Customize structured data for products.
	 *
	 * @param array      $markup Structured data.
	 * @param WC_Product $product Product object.
	 * @return array
	 */
	public function structured_data( $markup, $product ) {
		// Add brand.
		$brands = wp_get_post_terms( $product->get_id(), 'product_brand' );
		
		if ( $brands && ! is_wp_error( $brands ) ) {
			$markup['brand'] = array(
				'@type' => 'Brand',
				'name'  => $brands[0]->name,
			);
		}
		
		// Add SKU.
		$sku = $product->get_sku();
		
		if ( $sku ) {
			$markup['sku'] = $sku;
		}
		
		// Add MPN.
		$mpn = get_post_meta( $product->get_id(), '_aqualuxe_mpn', true );
		
		if ( $mpn ) {
			$markup['mpn'] = $mpn;
		}
		
		// Add GTIN.
		$gtin = get_post_meta( $product->get_id(), '_aqualuxe_gtin', true );
		
		if ( $gtin ) {
			$markup['gtin'] = $gtin;
		}
		
		return $markup;
	}
}