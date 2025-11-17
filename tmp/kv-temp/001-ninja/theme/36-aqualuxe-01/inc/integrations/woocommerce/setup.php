<?php
/**
 * WooCommerce setup functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if WooCommerce is active.
 *
 * @return bool True if WooCommerce is active, false otherwise.
 */
function aqualuxe_is_woocommerce_active() {
	return class_exists( 'WooCommerce' );
}

/**
 * Set up WooCommerce support.
 */
function aqualuxe_woocommerce_setup() {
	// Add theme support for WooCommerce.
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Remove default WooCommerce styles.
	add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

	// Add custom WooCommerce styles.
	add_action( 'wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts' );

	// Add custom WooCommerce body classes.
	add_filter( 'body_class', 'aqualuxe_woocommerce_body_classes' );

	// Add custom WooCommerce wrapper.
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before', 10 );
	add_action( 'woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after', 10 );

	// Add custom WooCommerce sidebar.
	add_action( 'woocommerce_sidebar', 'aqualuxe_woocommerce_sidebar', 10 );

	// Add custom WooCommerce breadcrumbs.
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_breadcrumb', 20 );

	// Add custom WooCommerce pagination.
	add_filter( 'woocommerce_pagination_args', 'aqualuxe_woocommerce_pagination_args' );

	// Add custom WooCommerce product columns.
	add_filter( 'loop_shop_columns', 'aqualuxe_woocommerce_loop_columns' );

	// Add custom WooCommerce products per page.
	add_filter( 'loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page' );

	// Add custom WooCommerce related products args.
	add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args' );

	// Add custom WooCommerce upsell products args.
	add_filter( 'woocommerce_upsell_display_args', 'aqualuxe_woocommerce_upsell_products_args' );

	// Add custom WooCommerce cross-sell products args.
	add_filter( 'woocommerce_cross_sells_columns', 'aqualuxe_woocommerce_cross_sells_columns' );
	add_filter( 'woocommerce_cross_sells_total', 'aqualuxe_woocommerce_cross_sells_total' );

	// Add custom WooCommerce product thumbnails.
	add_filter( 'woocommerce_product_thumbnails_columns', 'aqualuxe_woocommerce_thumbnail_columns' );

	// Add custom WooCommerce product gallery thumbnail size.
	add_filter( 'woocommerce_gallery_thumbnail_size', 'aqualuxe_woocommerce_gallery_thumbnail_size' );

	// Add custom WooCommerce product image size.
	add_filter( 'woocommerce_gallery_image_size', 'aqualuxe_woocommerce_gallery_image_size' );

	// Add custom WooCommerce product image width.
	add_filter( 'woocommerce_get_image_size_gallery_thumbnail', 'aqualuxe_woocommerce_gallery_thumbnail_image_size' );
	add_filter( 'woocommerce_get_image_size_single', 'aqualuxe_woocommerce_single_image_size' );
	add_filter( 'woocommerce_get_image_size_thumbnail', 'aqualuxe_woocommerce_thumbnail_image_size' );

	// Add custom WooCommerce product tabs.
	add_filter( 'woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs' );

	// Add custom WooCommerce product reviews.
	add_filter( 'woocommerce_product_review_comment_form_args', 'aqualuxe_woocommerce_product_review_comment_form_args' );

	// Add custom WooCommerce product sale flash.
	add_filter( 'woocommerce_sale_flash', 'aqualuxe_woocommerce_sale_flash', 10, 3 );

	// Add custom WooCommerce product price.
	add_filter( 'woocommerce_get_price_html', 'aqualuxe_woocommerce_price_html', 10, 2 );

	// Add custom WooCommerce product stock.
	add_filter( 'woocommerce_get_availability', 'aqualuxe_woocommerce_get_availability', 10, 2 );

	// Add custom WooCommerce product rating.
	add_filter( 'woocommerce_product_get_rating_html', 'aqualuxe_woocommerce_product_get_rating_html', 10, 3 );

	// Add custom WooCommerce product categories.
	add_filter( 'woocommerce_product_categories_widget_args', 'aqualuxe_woocommerce_product_categories_widget_args' );

	// Add custom WooCommerce product tags.
	add_filter( 'woocommerce_product_tag_cloud_widget_args', 'aqualuxe_woocommerce_product_tag_cloud_widget_args' );

	// Add custom WooCommerce product search.
	add_filter( 'get_product_search_form', 'aqualuxe_woocommerce_product_search_form' );

	// Add custom WooCommerce cart.
	add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_link_fragment' );
	add_action( 'woocommerce_add_to_cart', 'aqualuxe_woocommerce_add_to_cart', 10, 6 );
	add_action( 'wp_ajax_aqualuxe_woocommerce_add_to_cart_ajax', 'aqualuxe_woocommerce_add_to_cart_ajax' );
	add_action( 'wp_ajax_nopriv_aqualuxe_woocommerce_add_to_cart_ajax', 'aqualuxe_woocommerce_add_to_cart_ajax' );

	// Add custom WooCommerce checkout.
	add_filter( 'woocommerce_checkout_fields', 'aqualuxe_woocommerce_checkout_fields' );
	add_filter( 'woocommerce_default_address_fields', 'aqualuxe_woocommerce_default_address_fields' );
	add_filter( 'woocommerce_billing_fields', 'aqualuxe_woocommerce_billing_fields' );
	add_filter( 'woocommerce_shipping_fields', 'aqualuxe_woocommerce_shipping_fields' );
	add_filter( 'woocommerce_form_field_args', 'aqualuxe_woocommerce_form_field_args', 10, 3 );
	add_filter( 'woocommerce_checkout_coupon_message', 'aqualuxe_woocommerce_checkout_coupon_message' );
	add_filter( 'woocommerce_order_button_html', 'aqualuxe_woocommerce_order_button_html' );
	add_filter( 'woocommerce_checkout_terms_and_conditions_page_id', 'aqualuxe_woocommerce_checkout_terms_and_conditions_page_id' );

	// Add custom WooCommerce account.
	add_filter( 'woocommerce_my_account_my_orders_columns', 'aqualuxe_woocommerce_my_account_my_orders_columns' );
	add_filter( 'woocommerce_my_account_my_orders_actions', 'aqualuxe_woocommerce_my_account_my_orders_actions', 10, 2 );
	add_filter( 'woocommerce_account_menu_items', 'aqualuxe_woocommerce_account_menu_items' );
	add_filter( 'woocommerce_account_menu_item_classes', 'aqualuxe_woocommerce_account_menu_item_classes', 10, 2 );
	add_filter( 'woocommerce_login_form_args', 'aqualuxe_woocommerce_login_form_args' );
	add_filter( 'woocommerce_register_form_args', 'aqualuxe_woocommerce_register_form_args' );
	add_filter( 'woocommerce_lostpassword_form_args', 'aqualuxe_woocommerce_lostpassword_form_args' );
	add_filter( 'woocommerce_resetpassword_form_args', 'aqualuxe_woocommerce_resetpassword_form_args' );

	// Add custom WooCommerce emails.
	add_filter( 'woocommerce_email_styles', 'aqualuxe_woocommerce_email_styles' );
	add_filter( 'woocommerce_email_footer_text', 'aqualuxe_woocommerce_email_footer_text' );
	add_filter( 'woocommerce_email_heading_customer_new_account', 'aqualuxe_woocommerce_email_heading_customer_new_account' );
	add_filter( 'woocommerce_email_heading_customer_reset_password', 'aqualuxe_woocommerce_email_heading_customer_reset_password' );
	add_filter( 'woocommerce_email_heading_customer_processing_order', 'aqualuxe_woocommerce_email_heading_customer_processing_order' );
	add_filter( 'woocommerce_email_heading_customer_completed_order', 'aqualuxe_woocommerce_email_heading_customer_completed_order' );
	add_filter( 'woocommerce_email_heading_customer_refunded_order', 'aqualuxe_woocommerce_email_heading_customer_refunded_order' );
	add_filter( 'woocommerce_email_heading_customer_on_hold_order', 'aqualuxe_woocommerce_email_heading_customer_on_hold_order' );
	add_filter( 'woocommerce_email_heading_customer_invoice', 'aqualuxe_woocommerce_email_heading_customer_invoice' );
	add_filter( 'woocommerce_email_heading_failed_order', 'aqualuxe_woocommerce_email_heading_failed_order' );
	add_filter( 'woocommerce_email_heading_cancelled_order', 'aqualuxe_woocommerce_email_heading_cancelled_order' );
	add_filter( 'woocommerce_email_heading_new_order', 'aqualuxe_woocommerce_email_heading_new_order' );
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );

/**
 * Enqueue WooCommerce scripts and styles.
 */
function aqualuxe_woocommerce_scripts() {
	wp_enqueue_style( 'aqualuxe-woocommerce-style', get_template_directory_uri() . '/assets/dist/css/woocommerce.css', array(), AQUALUXE_VERSION );
	wp_enqueue_script( 'aqualuxe-woocommerce-script', get_template_directory_uri() . '/assets/dist/js/woocommerce.js', array( 'jquery' ), AQUALUXE_VERSION, true );
}

/**
 * Add WooCommerce body classes.
 *
 * @param array $classes Body classes.
 * @return array Modified body classes.
 */
function aqualuxe_woocommerce_body_classes( $classes ) {
	if ( is_woocommerce() ) {
		$classes[] = 'woocommerce-page';
	}

	if ( is_shop() ) {
		$classes[] = 'woocommerce-shop';
	}

	if ( is_product() ) {
		$classes[] = 'woocommerce-product';
	}

	if ( is_product_category() ) {
		$classes[] = 'woocommerce-product-category';
	}

	if ( is_product_tag() ) {
		$classes[] = 'woocommerce-product-tag';
	}

	if ( is_cart() ) {
		$classes[] = 'woocommerce-cart';
	}

	if ( is_checkout() ) {
		$classes[] = 'woocommerce-checkout';
	}

	if ( is_account_page() ) {
		$classes[] = 'woocommerce-account';
	}

	return $classes;
}

/**
 * Add AJAX add to cart functionality.
 */
function aqualuxe_woocommerce_add_to_cart_ajax() {
	// Check nonce.
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_woocommerce_add_to_cart_nonce' ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ) );
	}

	// Check product ID.
	if ( ! isset( $_POST['product_id'] ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID.', 'aqualuxe' ) ) );
	}

	// Get product ID.
	$product_id = absint( $_POST['product_id'] );

	// Get product.
	$product = wc_get_product( $product_id );

	// Check if product exists.
	if ( ! $product ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Product not found.', 'aqualuxe' ) ) );
	}

	// Get product name.
	$product_name = $product->get_name();

	// Get quantity.
	$quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;

	// Get variation ID.
	$variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;

	// Get variation.
	$variation = array();
	if ( isset( $_POST['variation'] ) && is_array( $_POST['variation'] ) ) {
		foreach ( $_POST['variation'] as $key => $value ) {
			$variation[ sanitize_text_field( wp_unslash( $key ) ) ] = sanitize_text_field( wp_unslash( $value ) );
		}
	}

	// Add to cart.
	$cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );

	// Check if added to cart.
	if ( ! $cart_item_key ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Failed to add product to cart.', 'aqualuxe' ) ) );
	}

	// Get cart URL.
	$cart_url = wc_get_cart_url();

	// Get cart total.
	$cart_total = WC()->cart->get_cart_total();

	// Get cart count.
	$cart_count = WC()->cart->get_cart_contents_count();

	// Get cart contents.
	$cart_contents = WC()->cart->get_cart();

	// Send success response.
	wp_send_json_success(
		array(
			/* translators: %s: product name */
			'message'      => sprintf( esc_html__( '%s has been added to your cart.', 'aqualuxe' ), $product_name ),
			'cartUrl'      => $cart_url,
			'cartTotal'    => $cart_total,
			'cartCount'    => $cart_count,
			'cartContents' => $cart_contents,
		)
	);
}

/**
 * Add to cart event.
 *
 * @param string $cart_item_key Cart item key.
 * @param int    $product_id Product ID.
 * @param int    $quantity Quantity.
 * @param int    $variation_id Variation ID.
 * @param array  $variation Variation.
 * @param array  $cart_item_data Cart item data.
 */
function aqualuxe_woocommerce_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
	// Do something when a product is added to the cart.
}

/**
 * Add WooCommerce cart link fragment.
 *
 * @param array $fragments Fragments to refresh via AJAX.
 * @return array Modified fragments.
 */
function aqualuxe_woocommerce_cart_link_fragment( $fragments ) {
	ob_start();
	aqualuxe_woocommerce_cart_link();
	$fragments['a.cart-contents'] = ob_get_clean();

	return $fragments;
}

/**
 * Display WooCommerce cart link.
 */
function aqualuxe_woocommerce_cart_link() {
	?>
	<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
		<span class="cart-contents-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
	</a>
	<?php
}

/**
 * Display WooCommerce header cart.
 */
function aqualuxe_woocommerce_header_cart() {
	if ( is_cart() ) {
		$class = 'current-menu-item';
	} else {
		$class = '';
	}
	?>
	<ul id="site-header-cart" class="site-header-cart">
		<li class="<?php echo esc_attr( $class ); ?>">
			<?php aqualuxe_woocommerce_cart_link(); ?>
		</li>
		<li>
			<?php
			$instance = array(
				'title' => '',
			);

			the_widget( 'WC_Widget_Cart', $instance );
			?>
		</li>
	</ul>
	<?php
}

/**
 * Add WooCommerce wishlist functionality.
 */
function aqualuxe_woocommerce_wishlist() {
	// Check if user is logged in.
	if ( ! is_user_logged_in() ) {
		return;
	}

	// Get user ID.
	$user_id = get_current_user_id();

	// Get wishlist.
	$wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );

	// Check if wishlist exists.
	if ( ! $wishlist ) {
		$wishlist = array();
	}

	// Return wishlist.
	return $wishlist;
}

/**
 * Add WooCommerce wishlist button.
 */
function aqualuxe_woocommerce_wishlist_button() {
	// Check if user is logged in.
	if ( ! is_user_logged_in() ) {
		return;
	}

	// Get product ID.
	$product_id = get_the_ID();

	// Get user ID.
	$user_id = get_current_user_id();

	// Get wishlist.
	$wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );

	// Check if wishlist exists.
	if ( ! $wishlist ) {
		$wishlist = array();
	}

	// Check if product is in wishlist.
	$in_wishlist = in_array( $product_id, $wishlist );

	// Get product.
	$product = wc_get_product( $product_id );

	// Check if product exists.
	if ( ! $product ) {
		return;
	}

	// Get nonce.
	$nonce = wp_create_nonce( 'aqualuxe_woocommerce_wishlist_nonce' );

	// Display wishlist button.
	?>
	<button class="wishlist-button<?php echo $in_wishlist ? ' in-wishlist' : ''; ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>">
		<span class="wishlist-button-text"><?php echo $in_wishlist ? esc_html__( 'Remove from Wishlist', 'aqualuxe' ) : esc_html__( 'Add to Wishlist', 'aqualuxe' ); ?></span>
	</button>
	<?php
}

/**
 * Add AJAX wishlist functionality.
 */
function aqualuxe_woocommerce_wishlist_ajax() {
	// Check nonce.
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_woocommerce_wishlist_nonce' ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ) );
	}

	// Check if user is logged in.
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => esc_html__( 'You must be logged in to add products to your wishlist.', 'aqualuxe' ) ) );
	}

	// Check product ID.
	if ( ! isset( $_POST['product_id'] ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID.', 'aqualuxe' ) ) );
	}

	// Get product ID.
	$product_id = absint( $_POST['product_id'] );

	// Get product.
	$product = wc_get_product( $product_id );

	// Check if product exists.
	if ( ! $product ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Product not found.', 'aqualuxe' ) ) );
	}

	// Get user ID.
	$user_id = get_current_user_id();

	// Get wishlist.
	$wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );

	// Check if wishlist exists.
	if ( ! $wishlist ) {
		$wishlist = array();
	}

	// Check if product is in wishlist.
	$in_wishlist = in_array( $product_id, $wishlist );

	// Add or remove product from wishlist.
	if ( $in_wishlist ) {
		// Remove product from wishlist.
		$wishlist = array_diff( $wishlist, array( $product_id ) );

		// Update wishlist.
		update_user_meta( $user_id, 'aqualuxe_wishlist', $wishlist );

		// Send success response.
		wp_send_json_success(
			array(
				/* translators: %s: product name */
				'message'  => sprintf( esc_html__( '%s has been removed from your wishlist.', 'aqualuxe' ), $product->get_name() ),
				'wishlist' => $wishlist,
			)
		);
	} else {
		// Add product to wishlist.
		$wishlist[] = $product_id;

		// Update wishlist.
		update_user_meta( $user_id, 'aqualuxe_wishlist', $wishlist );

		// Send success response.
		wp_send_json_success(
			array(
				/* translators: %s: product name */
				'message'  => sprintf( esc_html__( '%s has been added to your wishlist.', 'aqualuxe' ), $product->get_name() ),
				'wishlist' => $wishlist,
			)
		);
	}
}
add_action( 'wp_ajax_aqualuxe_woocommerce_wishlist_ajax', 'aqualuxe_woocommerce_wishlist_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_woocommerce_wishlist_ajax', 'aqualuxe_woocommerce_wishlist_ajax' );

/**
 * Display WooCommerce wrapper before.
 */
function aqualuxe_woocommerce_wrapper_before() {
	?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
	<?php
}

/**
 * Display WooCommerce wrapper after.
 */
function aqualuxe_woocommerce_wrapper_after() {
	?>
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php
}

/**
 * Display WooCommerce sidebar.
 */
function aqualuxe_woocommerce_sidebar() {
	get_sidebar( 'shop' );
}

/**
 * Display WooCommerce breadcrumb.
 */
function aqualuxe_woocommerce_breadcrumb() {
	woocommerce_breadcrumb(
		array(
			'delimiter'   => '<span class="breadcrumb-separator">/</span>',
			'wrap_before' => '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">',
			'wrap_after'  => '</nav>',
			'before'      => '',
			'after'       => '',
			'home'        => _x( 'Home', 'breadcrumb', 'aqualuxe' ),
		)
	);
}

/**
 * Modify WooCommerce pagination args.
 *
 * @param array $args Pagination args.
 * @return array Modified pagination args.
 */
function aqualuxe_woocommerce_pagination_args( $args ) {
	$args['prev_text'] = esc_html__( 'Previous', 'aqualuxe' );
	$args['next_text'] = esc_html__( 'Next', 'aqualuxe' );
	$args['end_size']  = 3;
	$args['mid_size']  = 3;

	return $args;
}

/**
 * Modify WooCommerce loop columns.
 *
 * @return int Number of columns.
 */
function aqualuxe_woocommerce_loop_columns() {
	return 3;
}

/**
 * Modify WooCommerce products per page.
 *
 * @return int Number of products per page.
 */
function aqualuxe_woocommerce_products_per_page() {
	return 12;
}

/**
 * Modify WooCommerce related products args.
 *
 * @param array $args Related products args.
 * @return array Modified related products args.
 */
function aqualuxe_woocommerce_related_products_args( $args ) {
	$args['posts_per_page'] = 3;
	$args['columns']        = 3;

	return $args;
}

/**
 * Modify WooCommerce upsell products args.
 *
 * @param array $args Upsell products args.
 * @return array Modified upsell products args.
 */
function aqualuxe_woocommerce_upsell_products_args( $args ) {
	$args['posts_per_page'] = 3;
	$args['columns']        = 3;

	return $args;
}

/**
 * Modify WooCommerce cross-sell columns.
 *
 * @return int Number of columns.
 */
function aqualuxe_woocommerce_cross_sells_columns() {
	return 2;
}

/**
 * Modify WooCommerce cross-sell total.
 *
 * @return int Number of cross-sells.
 */
function aqualuxe_woocommerce_cross_sells_total() {
	return 2;
}

/**
 * Modify WooCommerce thumbnail columns.
 *
 * @return int Number of columns.
 */
function aqualuxe_woocommerce_thumbnail_columns() {
	return 4;
}

/**
 * Modify WooCommerce gallery thumbnail size.
 *
 * @return string Thumbnail size.
 */
function aqualuxe_woocommerce_gallery_thumbnail_size() {
	return 'woocommerce_thumbnail';
}

/**
 * Modify WooCommerce gallery image size.
 *
 * @return string Image size.
 */
function aqualuxe_woocommerce_gallery_image_size() {
	return 'woocommerce_single';
}

/**
 * Modify WooCommerce gallery thumbnail image size.
 *
 * @param array $size Image size.
 * @return array Modified image size.
 */
function aqualuxe_woocommerce_gallery_thumbnail_image_size( $size ) {
	$size['width']  = 100;
	$size['height'] = 100;
	$size['crop']   = 1;

	return $size;
}

/**
 * Modify WooCommerce single image size.
 *
 * @param array $size Image size.
 * @return array Modified image size.
 */
function aqualuxe_woocommerce_single_image_size( $size ) {
	$size['width']  = 600;
	$size['height'] = 600;
	$size['crop']   = 1;

	return $size;
}

/**
 * Modify WooCommerce thumbnail image size.
 *
 * @param array $size Image size.
 * @return array Modified image size.
 */
function aqualuxe_woocommerce_thumbnail_image_size( $size ) {
	$size['width']  = 300;
	$size['height'] = 300;
	$size['crop']   = 1;

	return $size;
}

/**
 * Modify WooCommerce product tabs.
 *
 * @param array $tabs Product tabs.
 * @return array Modified product tabs.
 */
function aqualuxe_woocommerce_product_tabs( $tabs ) {
	return $tabs;
}

/**
 * Modify WooCommerce product review comment form args.
 *
 * @param array $args Comment form args.
 * @return array Modified comment form args.
 */
function aqualuxe_woocommerce_product_review_comment_form_args( $args ) {
	return $args;
}

/**
 * Modify WooCommerce sale flash.
 *
 * @param string $html Sale flash HTML.
 * @param WP_Post $post Post object.
 * @param WC_Product $product Product object.
 * @return string Modified sale flash HTML.
 */
function aqualuxe_woocommerce_sale_flash( $html, $post, $product ) {
	return $html;
}

/**
 * Modify WooCommerce price HTML.
 *
 * @param string $price Price HTML.
 * @param WC_Product $product Product object.
 * @return string Modified price HTML.
 */
function aqualuxe_woocommerce_price_html( $price, $product ) {
	return $price;
}

/**
 * Modify WooCommerce availability.
 *
 * @param array $availability Availability.
 * @param WC_Product $product Product object.
 * @return array Modified availability.
 */
function aqualuxe_woocommerce_get_availability( $availability, $product ) {
	return $availability;
}

/**
 * Modify WooCommerce product rating HTML.
 *
 * @param string $html Rating HTML.
 * @param float $rating Rating.
 * @param int $count Count.
 * @return string Modified rating HTML.
 */
function aqualuxe_woocommerce_product_get_rating_html( $html, $rating, $count ) {
	return $html;
}

/**
 * Modify WooCommerce product categories widget args.
 *
 * @param array $args Widget args.
 * @return array Modified widget args.
 */
function aqualuxe_woocommerce_product_categories_widget_args( $args ) {
	return $args;
}

/**
 * Modify WooCommerce product tag cloud widget args.
 *
 * @param array $args Widget args.
 * @return array Modified widget args.
 */
function aqualuxe_woocommerce_product_tag_cloud_widget_args( $args ) {
	return $args;
}

/**
 * Modify WooCommerce product search form.
 *
 * @param string $form Search form HTML.
 * @return string Modified search form HTML.
 */
function aqualuxe_woocommerce_product_search_form( $form ) {
	return $form;
}

/**
 * Modify WooCommerce checkout fields.
 *
 * @param array $fields Checkout fields.
 * @return array Modified checkout fields.
 */
function aqualuxe_woocommerce_checkout_fields( $fields ) {
	return $fields;
}

/**
 * Modify WooCommerce default address fields.
 *
 * @param array $fields Address fields.
 * @return array Modified address fields.
 */
function aqualuxe_woocommerce_default_address_fields( $fields ) {
	return $fields;
}

/**
 * Modify WooCommerce billing fields.
 *
 * @param array $fields Billing fields.
 * @return array Modified billing fields.
 */
function aqualuxe_woocommerce_billing_fields( $fields ) {
	return $fields;
}

/**
 * Modify WooCommerce shipping fields.
 *
 * @param array $fields Shipping fields.
 * @return array Modified shipping fields.
 */
function aqualuxe_woocommerce_shipping_fields( $fields ) {
	return $fields;
}

/**
 * Modify WooCommerce form field args.
 *
 * @param array $args Form field args.
 * @param string $key Field key.
 * @param string $value Field value.
 * @return array Modified form field args.
 */
function aqualuxe_woocommerce_form_field_args( $args, $key, $value ) {
	return $args;
}

/**
 * Modify WooCommerce checkout coupon message.
 *
 * @param string $message Coupon message.
 * @return string Modified coupon message.
 */
function aqualuxe_woocommerce_checkout_coupon_message( $message ) {
	return $message;
}

/**
 * Modify WooCommerce order button HTML.
 *
 * @param string $html Order button HTML.
 * @return string Modified order button HTML.
 */
function aqualuxe_woocommerce_order_button_html( $html ) {
	return $html;
}

/**
 * Modify WooCommerce checkout terms and conditions page ID.
 *
 * @param int $page_id Page ID.
 * @return int Modified page ID.
 */
function aqualuxe_woocommerce_checkout_terms_and_conditions_page_id( $page_id ) {
	return $page_id;
}

/**
 * Modify WooCommerce my account my orders columns.
 *
 * @param array $columns Order columns.
 * @return array Modified order columns.
 */
function aqualuxe_woocommerce_my_account_my_orders_columns( $columns ) {
	return $columns;
}

/**
 * Modify WooCommerce my account my orders actions.
 *
 * @param array $actions Order actions.
 * @param WC_Order $order Order object.
 * @return array Modified order actions.
 */
function aqualuxe_woocommerce_my_account_my_orders_actions( $actions, $order ) {
	return $actions;
}

/**
 * Modify WooCommerce account menu items.
 *
 * @param array $items Menu items.
 * @return array Modified menu items.
 */
function aqualuxe_woocommerce_account_menu_items( $items ) {
	return $items;
}

/**
 * Modify WooCommerce account menu item classes.
 *
 * @param array $classes Item classes.
 * @param string $endpoint Endpoint.
 * @return array Modified item classes.
 */
function aqualuxe_woocommerce_account_menu_item_classes( $classes, $endpoint ) {
	return $classes;
}

/**
 * Modify WooCommerce login form args.
 *
 * @param array $args Form args.
 * @return array Modified form args.
 */
function aqualuxe_woocommerce_login_form_args( $args ) {
	return $args;
}

/**
 * Modify WooCommerce register form args.
 *
 * @param array $args Form args.
 * @return array Modified form args.
 */
function aqualuxe_woocommerce_register_form_args( $args ) {
	return $args;
}

/**
 * Modify WooCommerce lost password form args.
 *
 * @param array $args Form args.
 * @return array Modified form args.
 */
function aqualuxe_woocommerce_lostpassword_form_args( $args ) {
	return $args;
}

/**
 * Modify WooCommerce reset password form args.
 *
 * @param array $args Form args.
 * @return array Modified form args.
 */
function aqualuxe_woocommerce_resetpassword_form_args( $args ) {
	return $args;
}

/**
 * Modify WooCommerce email styles.
 *
 * @param string $css Email styles.
 * @return string Modified email styles.
 */
function aqualuxe_woocommerce_email_styles( $css ) {
	return $css;
}

/**
 * Modify WooCommerce email footer text.
 *
 * @param string $text Footer text.
 * @return string Modified footer text.
 */
function aqualuxe_woocommerce_email_footer_text( $text ) {
	return $text;
}

/**
 * Modify WooCommerce email heading customer new account.
 *
 * @param string $heading Email heading.
 * @return string Modified email heading.
 */
function aqualuxe_woocommerce_email_heading_customer_new_account( $heading ) {
	return $heading;
}

/**
 * Modify WooCommerce email heading customer reset password.
 *
 * @param string $heading Email heading.
 * @return string Modified email heading.
 */
function aqualuxe_woocommerce_email_heading_customer_reset_password( $heading ) {
	return $heading;
}

/**
 * Modify WooCommerce email heading customer processing order.
 *
 * @param string $heading Email heading.
 * @return string Modified email heading.
 */
function aqualuxe_woocommerce_email_heading_customer_processing_order( $heading ) {
	return $heading;
}

/**
 * Modify WooCommerce email heading customer completed order.
 *
 * @param string $heading Email heading.
 * @return string Modified email heading.
 */
function aqualuxe_woocommerce_email_heading_customer_completed_order( $heading ) {
	return $heading;
}

/**
 * Modify WooCommerce email heading customer refunded order.
 *
 * @param string $heading Email heading.
 * @return string Modified email heading.
 */
function aqualuxe_woocommerce_email_heading_customer_refunded_order( $heading ) {
	return $heading;
}

/**
 * Modify WooCommerce email heading customer on hold order.
 *
 * @param string $heading Email heading.
 * @return string Modified email heading.
 */
function aqualuxe_woocommerce_email_heading_customer_on_hold_order( $heading ) {
	return $heading;
}

/**
 * Modify WooCommerce email heading customer invoice.
 *
 * @param string $heading Email heading.
 * @return string Modified email heading.
 */
function aqualuxe_woocommerce_email_heading_customer_invoice( $heading ) {
	return $heading;
}

/**
 * Modify WooCommerce email heading failed order.
 *
 * @param string $heading Email heading.
 * @return string Modified email heading.
 */
function aqualuxe_woocommerce_email_heading_failed_order( $heading ) {
	return $heading;
}

/**
 * Modify WooCommerce email heading cancelled order.
 *
 * @param string $heading Email heading.
 * @return string Modified email heading.
 */
function aqualuxe_woocommerce_email_heading_cancelled_order( $heading ) {
	return $heading;
}

/**
 * Modify WooCommerce email heading new order.
 *
 * @param string $heading Email heading.
 * @return string Modified email heading.
 */
function aqualuxe_woocommerce_email_heading_new_order( $heading ) {
	return $heading;
}