<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 *
 * @return void
 */
function aqualuxe_woocommerce_setup() {
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 300,
			'single_image_width'    => 800,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 3,
				'min_columns'     => 1,
				'max_columns'     => 6,
			),
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function aqualuxe_woocommerce_scripts() {
	wp_enqueue_style( 'aqualuxe-woocommerce-style', AQUALUXE_ASSETS_URI . 'css/woocommerce.css', array(), AQUALUXE_VERSION );

	$font_path   = WC()->plugin_url() . '/assets/fonts/';
	$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

	wp_add_inline_style( 'aqualuxe-woocommerce-style', $inline_font );

	// Enqueue WooCommerce scripts.
	wp_enqueue_script( 'aqualuxe-woocommerce', AQUALUXE_ASSETS_URI . 'js/woocommerce.js', array( 'jquery' ), AQUALUXE_VERSION, true );

	// Localize WooCommerce scripts.
	wp_localize_script(
		'aqualuxe-woocommerce',
		'aqualuxeWooCommerce',
		array(
			'ajaxUrl'                => admin_url( 'admin-ajax.php' ),
			'nonce'                  => wp_create_nonce( 'aqualuxe-woocommerce' ),
			'addToCartText'          => esc_html__( 'Add to cart', 'aqualuxe' ),
			'addingToCartText'       => esc_html__( 'Adding...', 'aqualuxe' ),
			'addedToCartText'        => esc_html__( 'Added to cart', 'aqualuxe' ),
			'viewCartText'           => esc_html__( 'View cart', 'aqualuxe' ),
			'i18n_added_to_cart'     => esc_html__( 'Item added to cart.', 'aqualuxe' ),
			'i18n_removed_from_cart' => esc_html__( 'Item removed from cart.', 'aqualuxe' ),
			'i18n_cart_updated'      => esc_html__( 'Cart updated.', 'aqualuxe' ),
			'i18n_cart_error'        => esc_html__( 'Error updating cart.', 'aqualuxe' ),
			'i18n_empty_cart'        => esc_html__( 'Your cart is currently empty.', 'aqualuxe' ),
			'i18n_loading'           => esc_html__( 'Loading...', 'aqualuxe' ),
			'i18n_error'             => esc_html__( 'Error:', 'aqualuxe' ),
			'i18n_success'           => esc_html__( 'Success:', 'aqualuxe' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts' );

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function aqualuxe_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'aqualuxe_woocommerce_active_body_class' );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function aqualuxe_woocommerce_related_products_args( $args ) {
	$defaults = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args' );

/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'aqualuxe_woocommerce_wrapper_before' ) ) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	function aqualuxe_woocommerce_wrapper_before() {
		?>
		<main id="primary" class="site-main">
		<?php
	}
}
add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before' );

if ( ! function_exists( 'aqualuxe_woocommerce_wrapper_after' ) ) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	function aqualuxe_woocommerce_wrapper_after() {
		?>
		</main><!-- #main -->
		<?php
	}
}
add_action( 'woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after' );

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
 * <?php
 * if ( function_exists( 'aqualuxe_woocommerce_header_cart' ) ) {
 * aqualuxe_woocommerce_header_cart();
 * }
 * ?>
 */

if ( ! function_exists( 'aqualuxe_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	function aqualuxe_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		aqualuxe_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'aqualuxe_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	function aqualuxe_woocommerce_cart_link() {
		?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
			<?php
			$item_count_text = sprintf(
				/* translators: number of items in the mini cart. */
				_n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'aqualuxe' ),
				WC()->cart->get_cart_contents_count()
			);
			?>
			<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span> <span class="count"><?php echo esc_html( $item_count_text ); ?></span>
		</a>
		<?php
	}
}

if ( ! function_exists( 'aqualuxe_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
	function aqualuxe_woocommerce_header_cart() {
		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
		<div class="site-header-cart">
			<div class="<?php echo esc_attr( $class ); ?>">
				<?php aqualuxe_woocommerce_cart_link(); ?>
			</div>
			<div class="cart-dropdown">
				<?php
				$instance = array(
					'title' => '',
				);

				the_widget( 'WC_Widget_Cart', $instance );
				?>
			</div>
		</div>
		<?php
	}
}

/**
 * Optimize WooCommerce scripts.
 *
 * @param string $scripts Scripts to load.
 * @return string
 */
function aqualuxe_woocommerce_scripts_optimization( $scripts ) {
	// Remove WooCommerce scripts from non-WooCommerce pages.
	if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
		return '';
	}

	return $scripts;
}
add_filter( 'woocommerce_queued_js', 'aqualuxe_woocommerce_scripts_optimization' );

/**
 * Add custom classes to product loops.
 *
 * @param array $classes Product classes.
 * @return array
 */
function aqualuxe_woocommerce_product_classes( $classes ) {
	$classes[] = 'product-card';
	$classes[] = 'transition-all';
	$classes[] = 'duration-300';
	$classes[] = 'hover:shadow-medium';

	return $classes;
}
add_filter( 'woocommerce_post_class', 'aqualuxe_woocommerce_product_classes' );

/**
 * Modify the number of products per row.
 *
 * @param int $columns Number of columns.
 * @return int
 */
function aqualuxe_woocommerce_loop_columns( $columns ) {
	return get_theme_mod( 'aqualuxe_products_per_row', 3 );
}
add_filter( 'loop_shop_columns', 'aqualuxe_woocommerce_loop_columns' );

/**
 * Modify the number of products per page.
 *
 * @param int $products Number of products.
 * @return int
 */
function aqualuxe_woocommerce_products_per_page( $products ) {
	return get_theme_mod( 'aqualuxe_products_per_page', 12 );
}
add_filter( 'loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page' );

/**
 * Modify the breadcrumb separator.
 *
 * @param array $defaults Breadcrumb defaults.
 * @return array
 */
function aqualuxe_woocommerce_breadcrumb_defaults( $defaults ) {
	$defaults['delimiter'] = '<span class="breadcrumb-separator">/</span>';
	$defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumbs', 'aqualuxe' ) . '">';
	$defaults['wrap_after'] = '</nav>';

	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_defaults' );

/**
 * Add custom classes to WooCommerce buttons.
 *
 * @param array $args Button arguments.
 * @return array
 */
function aqualuxe_woocommerce_button_classes( $args ) {
	$args['class'] = isset( $args['class'] ) ? $args['class'] . ' btn-primary' : 'btn-primary';

	return $args;
}
add_filter( 'woocommerce_loop_add_to_cart_args', 'aqualuxe_woocommerce_button_classes' );

/**
 * Add custom classes to WooCommerce forms.
 *
 * @param array $args Form arguments.
 * @return array
 */
function aqualuxe_woocommerce_form_field_args( $args, $key, $value ) {
	// Add class to input.
	if ( ! empty( $args['input_class'] ) ) {
		$args['input_class'][] = 'form-input';
	} else {
		$args['input_class'] = array( 'form-input' );
	}

	// Add class to label.
	if ( ! empty( $args['label_class'] ) ) {
		$args['label_class'][] = 'form-label';
	} else {
		$args['label_class'] = array( 'form-label' );
	}

	return $args;
}
add_filter( 'woocommerce_form_field_args', 'aqualuxe_woocommerce_form_field_args', 10, 3 );

/**
 * Add custom classes to WooCommerce pagination.
 *
 * @param array $args Pagination arguments.
 * @return array
 */
function aqualuxe_woocommerce_pagination_args( $args ) {
	$args['prev_text'] = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>' . esc_html__( 'Previous', 'aqualuxe' );
	$args['next_text'] = esc_html__( 'Next', 'aqualuxe' ) . '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>';
	$args['class'] = 'woocommerce-pagination flex justify-center';

	return $args;
}
add_filter( 'woocommerce_pagination_args', 'aqualuxe_woocommerce_pagination_args' );

/**
 * Add custom classes to WooCommerce product thumbnails.
 *
 * @param array $atts Thumbnail attributes.
 * @return array
 */
function aqualuxe_woocommerce_product_thumbnails( $atts ) {
	$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' rounded-lg' : 'rounded-lg';

	return $atts;
}
add_filter( 'woocommerce_gallery_image_html_attachment_image_params', 'aqualuxe_woocommerce_product_thumbnails' );

/**
 * Add custom classes to WooCommerce product images.
 *
 * @param array $atts Image attributes.
 * @return array
 */
function aqualuxe_woocommerce_product_images( $atts ) {
	$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' rounded-lg' : 'rounded-lg';

	return $atts;
}
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'aqualuxe_woocommerce_product_images' );

/**
 * Add custom classes to WooCommerce product gallery.
 *
 * @param array $classes Gallery classes.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_classes( $classes ) {
	$classes[] = 'rounded-lg';
	$classes[] = 'overflow-hidden';

	return $classes;
}
add_filter( 'woocommerce_single_product_image_gallery_classes', 'aqualuxe_woocommerce_product_gallery_classes' );

/**
 * Add custom classes to WooCommerce product tabs.
 *
 * @param array $tabs Product tabs.
 * @return array
 */
function aqualuxe_woocommerce_product_tabs( $tabs ) {
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs' );

/**
 * Add custom classes to WooCommerce product reviews.
 *
 * @param array $comment_form Comment form arguments.
 * @return array
 */
function aqualuxe_woocommerce_product_reviews( $comment_form ) {
	$comment_form['class_submit'] = 'btn-primary';

	return $comment_form;
}
add_filter( 'woocommerce_product_review_comment_form_args', 'aqualuxe_woocommerce_product_reviews' );

/**
 * Add custom classes to WooCommerce checkout fields.
 *
 * @param array $fields Checkout fields.
 * @return array
 */
function aqualuxe_woocommerce_checkout_fields( $fields ) {
	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'aqualuxe_woocommerce_checkout_fields' );

/**
 * Add custom classes to WooCommerce checkout button.
 *
 * @param string $button Checkout button.
 * @return string
 */
function aqualuxe_woocommerce_checkout_button( $button ) {
	return str_replace( 'button alt', 'button alt btn-primary', $button );
}
add_filter( 'woocommerce_order_button_html', 'aqualuxe_woocommerce_checkout_button' );

/**
 * Add custom classes to WooCommerce account navigation.
 *
 * @param array $items Navigation items.
 * @return array
 */
function aqualuxe_woocommerce_account_navigation( $items ) {
	return $items;
}
add_filter( 'woocommerce_account_menu_items', 'aqualuxe_woocommerce_account_navigation' );

/**
 * Add custom classes to WooCommerce account orders.
 *
 * @param array $columns Order columns.
 * @return array
 */
function aqualuxe_woocommerce_account_orders( $columns ) {
	return $columns;
}
add_filter( 'woocommerce_account_orders_columns', 'aqualuxe_woocommerce_account_orders' );

/**
 * Add custom classes to WooCommerce account downloads.
 *
 * @param array $columns Download columns.
 * @return array
 */
function aqualuxe_woocommerce_account_downloads( $columns ) {
	return $columns;
}
add_filter( 'woocommerce_account_downloads_columns', 'aqualuxe_woocommerce_account_downloads' );

/**
 * Add custom classes to WooCommerce account addresses.
 *
 * @param array $address Address fields.
 * @return array
 */
function aqualuxe_woocommerce_account_addresses( $address ) {
	return $address;
}
add_filter( 'woocommerce_my_account_my_address_formatted_address', 'aqualuxe_woocommerce_account_addresses' );

/**
 * Add custom classes to WooCommerce account payment methods.
 *
 * @param array $methods Payment methods.
 * @return array
 */
function aqualuxe_woocommerce_account_payment_methods( $methods ) {
	return $methods;
}
add_filter( 'woocommerce_payment_methods_list', 'aqualuxe_woocommerce_account_payment_methods' );

/**
 * Add custom classes to WooCommerce account edit address.
 *
 * @param array $fields Address fields.
 * @return array
 */
function aqualuxe_woocommerce_account_edit_address( $fields ) {
	return $fields;
}
add_filter( 'woocommerce_address_to_edit', 'aqualuxe_woocommerce_account_edit_address' );

/**
 * Add custom classes to WooCommerce account edit account.
 *
 * @param array $fields Account fields.
 * @return array
 */
function aqualuxe_woocommerce_account_edit_account( $fields ) {
	return $fields;
}
add_filter( 'woocommerce_save_account_details_required_fields', 'aqualuxe_woocommerce_account_edit_account' );

/**
 * Add custom classes to WooCommerce account login.
 *
 * @param array $args Login form arguments.
 * @return array
 */
function aqualuxe_woocommerce_account_login( $args ) {
	$args['form_class'] = 'woocommerce-form woocommerce-form-login login';

	return $args;
}
add_filter( 'woocommerce_login_form_args', 'aqualuxe_woocommerce_account_login' );

/**
 * Add custom classes to WooCommerce account register.
 *
 * @param array $args Register form arguments.
 * @return array
 */
function aqualuxe_woocommerce_account_register( $args ) {
	$args['form_class'] = 'woocommerce-form woocommerce-form-register register';

	return $args;
}
add_filter( 'woocommerce_register_form_args', 'aqualuxe_woocommerce_account_register' );

/**
 * Add custom classes to WooCommerce account lost password.
 *
 * @param array $args Lost password form arguments.
 * @return array
 */
function aqualuxe_woocommerce_account_lost_password( $args ) {
	$args['form_class'] = 'woocommerce-form woocommerce-form-lost-password lost_reset_password';

	return $args;
}
add_filter( 'woocommerce_lost_password_form_args', 'aqualuxe_woocommerce_account_lost_password' );

/**
 * Add custom classes to WooCommerce account reset password.
 *
 * @param array $args Reset password form arguments.
 * @return array
 */
function aqualuxe_woocommerce_account_reset_password( $args ) {
	$args['form_class'] = 'woocommerce-form woocommerce-form-reset-password reset-password';

	return $args;
}
add_filter( 'woocommerce_reset_password_form_args', 'aqualuxe_woocommerce_account_reset_password' );

/**
 * Add custom classes to WooCommerce account edit address button.
 *
 * @param string $button Edit address button.
 * @return string
 */
function aqualuxe_woocommerce_account_edit_address_button( $button ) {
	return str_replace( 'button', 'button btn-primary', $button );
}
add_filter( 'woocommerce_my_account_edit_address_button_text', 'aqualuxe_woocommerce_account_edit_address_button' );

/**
 * Add custom classes to WooCommerce account edit account button.
 *
 * @param string $button Edit account button.
 * @return string
 */
function aqualuxe_woocommerce_account_edit_account_button( $button ) {
	return str_replace( 'button', 'button btn-primary', $button );
}
add_filter( 'woocommerce_my_account_edit_account_button_text', 'aqualuxe_woocommerce_account_edit_account_button' );

/**
 * Add custom classes to WooCommerce account login button.
 *
 * @param string $button Login button.
 * @return string
 */
function aqualuxe_woocommerce_account_login_button( $button ) {
	return str_replace( 'button', 'button btn-primary', $button );
}
add_filter( 'woocommerce_login_button_text', 'aqualuxe_woocommerce_account_login_button' );

/**
 * Add custom classes to WooCommerce account register button.
 *
 * @param string $button Register button.
 * @return string
 */
function aqualuxe_woocommerce_account_register_button( $button ) {
	return str_replace( 'button', 'button btn-primary', $button );
}
add_filter( 'woocommerce_register_button_text', 'aqualuxe_woocommerce_account_register_button' );

/**
 * Add custom classes to WooCommerce account lost password button.
 *
 * @param string $button Lost password button.
 * @return string
 */
function aqualuxe_woocommerce_account_lost_password_button( $button ) {
	return str_replace( 'button', 'button btn-primary', $button );
}
add_filter( 'woocommerce_lost_password_button_text', 'aqualuxe_woocommerce_account_lost_password_button' );

/**
 * Add custom classes to WooCommerce account reset password button.
 *
 * @param string $button Reset password button.
 * @return string
 */
function aqualuxe_woocommerce_account_reset_password_button( $button ) {
	return str_replace( 'button', 'button btn-primary', $button );
}
add_filter( 'woocommerce_reset_password_button_text', 'aqualuxe_woocommerce_account_reset_password_button' );

/**
 * Load WooCommerce class files.
 */
require_once AQUALUXE_INC_DIR . 'woocommerce/class-woocommerce.php';
require_once AQUALUXE_INC_DIR . 'woocommerce/class-product.php';
require_once AQUALUXE_INC_DIR . 'woocommerce/class-cart.php';
require_once AQUALUXE_INC_DIR . 'woocommerce/class-checkout.php';
require_once AQUALUXE_INC_DIR . 'woocommerce/class-account.php';
require_once AQUALUXE_INC_DIR . 'woocommerce/class-wishlist.php';
require_once AQUALUXE_INC_DIR . 'woocommerce/class-quick-view.php';