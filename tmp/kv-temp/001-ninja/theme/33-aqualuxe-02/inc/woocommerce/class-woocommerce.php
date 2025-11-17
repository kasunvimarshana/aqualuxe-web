<?php
/**
 * WooCommerce Class
 *
 * @package AquaLuxe
 * @subpackage WooCommerce
 */

namespace AquaLuxe\WooCommerce;

use AquaLuxe\Core\Service;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce Class
 *
 * This class handles WooCommerce integration.
 */
class WooCommerce extends Service {

	/**
	 * Initialize the service
	 *
	 * @return void
	 */
	public function initialize() {
		$this->register_hooks();
	}

	/**
	 * Register hooks
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Setup.
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		
		// Scripts and styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		// Disable default styles.
		add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
		
		// Body classes.
		add_filter( 'body_class', array( $this, 'body_class' ) );
		
		// Related products.
		add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );
		
		// Wrapper.
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		add_action( 'woocommerce_before_main_content', array( $this, 'wrapper_before' ) );
		add_action( 'woocommerce_after_main_content', array( $this, 'wrapper_after' ) );
		
		// Cart fragments.
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_link_fragment' ) );
		
		// Scripts optimization.
		add_filter( 'woocommerce_queued_js', array( $this, 'scripts_optimization' ) );
		
		// Product classes.
		add_filter( 'woocommerce_post_class', array( $this, 'product_classes' ) );
		
		// Loop columns.
		add_filter( 'loop_shop_columns', array( $this, 'loop_columns' ) );
		
		// Products per page.
		add_filter( 'loop_shop_per_page', array( $this, 'products_per_page' ) );
		
		// Breadcrumb.
		add_filter( 'woocommerce_breadcrumb_defaults', array( $this, 'breadcrumb_defaults' ) );
		
		// Button classes.
		add_filter( 'woocommerce_loop_add_to_cart_args', array( $this, 'button_classes' ) );
		
		// Form field classes.
		add_filter( 'woocommerce_form_field_args', array( $this, 'form_field_args' ), 10, 3 );
		
		// Pagination.
		add_filter( 'woocommerce_pagination_args', array( $this, 'pagination_args' ) );
		
		// Product thumbnails.
		add_filter( 'woocommerce_gallery_image_html_attachment_image_params', array( $this, 'product_thumbnails' ) );
		
		// Product images.
		add_filter( 'woocommerce_single_product_image_thumbnail_html', array( $this, 'product_images' ) );
		
		// Product gallery classes.
		add_filter( 'woocommerce_single_product_image_gallery_classes', array( $this, 'product_gallery_classes' ) );
		
		// Product tabs.
		add_filter( 'woocommerce_product_tabs', array( $this, 'product_tabs' ) );
		
		// Product reviews.
		add_filter( 'woocommerce_product_review_comment_form_args', array( $this, 'product_reviews' ) );
		
		// Checkout fields.
		add_filter( 'woocommerce_checkout_fields', array( $this, 'checkout_fields' ) );
		
		// Checkout button.
		add_filter( 'woocommerce_order_button_html', array( $this, 'checkout_button' ) );
		
		// Account navigation.
		add_filter( 'woocommerce_account_menu_items', array( $this, 'account_navigation' ) );
		
		// Account orders.
		add_filter( 'woocommerce_account_orders_columns', array( $this, 'account_orders' ) );
		
		// Account downloads.
		add_filter( 'woocommerce_account_downloads_columns', array( $this, 'account_downloads' ) );
		
		// Account addresses.
		add_filter( 'woocommerce_my_account_my_address_formatted_address', array( $this, 'account_addresses' ) );
		
		// Account payment methods.
		add_filter( 'woocommerce_payment_methods_list', array( $this, 'account_payment_methods' ) );
		
		// Account edit address.
		add_filter( 'woocommerce_address_to_edit', array( $this, 'account_edit_address' ) );
		
		// Account edit account.
		add_filter( 'woocommerce_save_account_details_required_fields', array( $this, 'account_edit_account' ) );
		
		// Account login.
		add_filter( 'woocommerce_login_form_args', array( $this, 'account_login' ) );
		
		// Account register.
		add_filter( 'woocommerce_register_form_args', array( $this, 'account_register' ) );
		
		// Account lost password.
		add_filter( 'woocommerce_lost_password_form_args', array( $this, 'account_lost_password' ) );
		
		// Account reset password.
		add_filter( 'woocommerce_reset_password_form_args', array( $this, 'account_reset_password' ) );
		
		// Account edit address button.
		add_filter( 'woocommerce_my_account_edit_address_button_text', array( $this, 'account_edit_address_button' ) );
		
		// Account edit account button.
		add_filter( 'woocommerce_my_account_edit_account_button_text', array( $this, 'account_edit_account_button' ) );
		
		// Account login button.
		add_filter( 'woocommerce_login_button_text', array( $this, 'account_login_button' ) );
		
		// Account register button.
		add_filter( 'woocommerce_register_button_text', array( $this, 'account_register_button' ) );
		
		// Account lost password button.
		add_filter( 'woocommerce_lost_password_button_text', array( $this, 'account_lost_password_button' ) );
		
		// Account reset password button.
		add_filter( 'woocommerce_reset_password_button_text', array( $this, 'account_reset_password_button' ) );
	}

	/**
	 * WooCommerce setup function
	 *
	 * @return void
	 */
	public function setup() {
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

	/**
	 * WooCommerce specific scripts & stylesheets
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
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

	/**
	 * Add 'woocommerce-active' class to the body tag
	 *
	 * @param array $classes CSS classes applied to the body tag.
	 * @return array
	 */
	public function body_class( $classes ) {
		$classes[] = 'woocommerce-active';

		return $classes;
	}

	/**
	 * Related Products Args
	 *
	 * @param array $args Related products args.
	 * @return array
	 */
	public function related_products_args( $args ) {
		$defaults = array(
			'posts_per_page' => 3,
			'columns'        => 3,
		);

		$args = wp_parse_args( $defaults, $args );

		return $args;
	}

	/**
	 * Before Content
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	public function wrapper_before() {
		?>
		<main id="primary" class="site-main">
		<?php
	}

	/**
	 * After Content
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	public function wrapper_after() {
		?>
		</main><!-- #main -->
		<?php
	}

	/**
	 * Cart Fragments
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

		return $fragments;
	}

	/**
	 * Cart Link
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	public function cart_link() {
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

	/**
	 * Display Header Cart
	 *
	 * @return void
	 */
	public function header_cart() {
		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
		<div class="site-header-cart">
			<div class="<?php echo esc_attr( $class ); ?>">
				<?php $this->cart_link(); ?>
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

	/**
	 * Optimize WooCommerce scripts
	 *
	 * @param string $scripts Scripts to load.
	 * @return string
	 */
	public function scripts_optimization( $scripts ) {
		// Remove WooCommerce scripts from non-WooCommerce pages.
		if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
			return '';
		}

		return $scripts;
	}

	/**
	 * Add custom classes to product loops
	 *
	 * @param array $classes Product classes.
	 * @return array
	 */
	public function product_classes( $classes ) {
		$classes[] = 'product-card';
		$classes[] = 'transition-all';
		$classes[] = 'duration-300';
		$classes[] = 'hover:shadow-medium';

		return $classes;
	}

	/**
	 * Modify the number of products per row
	 *
	 * @param int $columns Number of columns.
	 * @return int
	 */
	public function loop_columns( $columns ) {
		return $this->get_theme_option( 'aqualuxe_products_per_row', 3 );
	}

	/**
	 * Modify the number of products per page
	 *
	 * @param int $products Number of products.
	 * @return int
	 */
	public function products_per_page( $products ) {
		return $this->get_theme_option( 'aqualuxe_products_per_page', 12 );
	}

	/**
	 * Modify the breadcrumb separator
	 *
	 * @param array $defaults Breadcrumb defaults.
	 * @return array
	 */
	public function breadcrumb_defaults( $defaults ) {
		$defaults['delimiter'] = '<span class="breadcrumb-separator">/</span>';
		$defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumbs', 'aqualuxe' ) . '">';
		$defaults['wrap_after'] = '</nav>';

		return $defaults;
	}

	/**
	 * Add custom classes to WooCommerce buttons
	 *
	 * @param array $args Button arguments.
	 * @return array
	 */
	public function button_classes( $args ) {
		$args['class'] = isset( $args['class'] ) ? $args['class'] . ' btn-primary' : 'btn-primary';

		return $args;
	}

	/**
	 * Add custom classes to WooCommerce forms
	 *
	 * @param array  $args Form arguments.
	 * @param string $key Field key.
	 * @param mixed  $value Field value.
	 * @return array
	 */
	public function form_field_args( $args, $key, $value ) {
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

	/**
	 * Add custom classes to WooCommerce pagination
	 *
	 * @param array $args Pagination arguments.
	 * @return array
	 */
	public function pagination_args( $args ) {
		$args['prev_text'] = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>' . esc_html__( 'Previous', 'aqualuxe' );
		$args['next_text'] = esc_html__( 'Next', 'aqualuxe' ) . '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>';
		$args['class'] = 'woocommerce-pagination flex justify-center';

		return $args;
	}

	/**
	 * Add custom classes to WooCommerce product thumbnails
	 *
	 * @param array $atts Thumbnail attributes.
	 * @return array
	 */
	public function product_thumbnails( $atts ) {
		$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' rounded-lg' : 'rounded-lg';

		return $atts;
	}

	/**
	 * Add custom classes to WooCommerce product images
	 *
	 * @param string $html Image HTML.
	 * @return string
	 */
	public function product_images( $html ) {
		return str_replace( 'attachment-', 'rounded-lg attachment-', $html );
	}

	/**
	 * Add custom classes to WooCommerce product gallery
	 *
	 * @param array $classes Gallery classes.
	 * @return array
	 */
	public function product_gallery_classes( $classes ) {
		$classes[] = 'rounded-lg';
		$classes[] = 'overflow-hidden';

		return $classes;
	}

	/**
	 * Add custom classes to WooCommerce product tabs
	 *
	 * @param array $tabs Product tabs.
	 * @return array
	 */
	public function product_tabs( $tabs ) {
		return $tabs;
	}

	/**
	 * Add custom classes to WooCommerce product reviews
	 *
	 * @param array $comment_form Comment form arguments.
	 * @return array
	 */
	public function product_reviews( $comment_form ) {
		$comment_form['class_submit'] = 'btn-primary';

		return $comment_form;
	}

	/**
	 * Add custom classes to WooCommerce checkout fields
	 *
	 * @param array $fields Checkout fields.
	 * @return array
	 */
	public function checkout_fields( $fields ) {
		return $fields;
	}

	/**
	 * Add custom classes to WooCommerce checkout button
	 *
	 * @param string $button Checkout button.
	 * @return string
	 */
	public function checkout_button( $button ) {
		return str_replace( 'button alt', 'button alt btn-primary', $button );
	}

	/**
	 * Add custom classes to WooCommerce account navigation
	 *
	 * @param array $items Navigation items.
	 * @return array
	 */
	public function account_navigation( $items ) {
		return $items;
	}

	/**
	 * Add custom classes to WooCommerce account orders
	 *
	 * @param array $columns Order columns.
	 * @return array
	 */
	public function account_orders( $columns ) {
		return $columns;
	}

	/**
	 * Add custom classes to WooCommerce account downloads
	 *
	 * @param array $columns Download columns.
	 * @return array
	 */
	public function account_downloads( $columns ) {
		return $columns;
	}

	/**
	 * Add custom classes to WooCommerce account addresses
	 *
	 * @param array $address Address fields.
	 * @return array
	 */
	public function account_addresses( $address ) {
		return $address;
	}

	/**
	 * Add custom classes to WooCommerce account payment methods
	 *
	 * @param array $methods Payment methods.
	 * @return array
	 */
	public function account_payment_methods( $methods ) {
		return $methods;
	}

	/**
	 * Add custom classes to WooCommerce account edit address
	 *
	 * @param array $fields Address fields.
	 * @return array
	 */
	public function account_edit_address( $fields ) {
		return $fields;
	}

	/**
	 * Add custom classes to WooCommerce account edit account
	 *
	 * @param array $fields Account fields.
	 * @return array
	 */
	public function account_edit_account( $fields ) {
		return $fields;
	}

	/**
	 * Add custom classes to WooCommerce account login
	 *
	 * @param array $args Login form arguments.
	 * @return array
	 */
	public function account_login( $args ) {
		$args['form_class'] = 'woocommerce-form woocommerce-form-login login';

		return $args;
	}

	/**
	 * Add custom classes to WooCommerce account register
	 *
	 * @param array $args Register form arguments.
	 * @return array
	 */
	public function account_register( $args ) {
		$args['form_class'] = 'woocommerce-form woocommerce-form-register register';

		return $args;
	}

	/**
	 * Add custom classes to WooCommerce account lost password
	 *
	 * @param array $args Lost password form arguments.
	 * @return array
	 */
	public function account_lost_password( $args ) {
		$args['form_class'] = 'woocommerce-form woocommerce-form-lost-password lost_reset_password';

		return $args;
	}

	/**
	 * Add custom classes to WooCommerce account reset password
	 *
	 * @param array $args Reset password form arguments.
	 * @return array
	 */
	public function account_reset_password( $args ) {
		$args['form_class'] = 'woocommerce-form woocommerce-form-reset-password reset-password';

		return $args;
	}

	/**
	 * Add custom classes to WooCommerce account edit address button
	 *
	 * @param string $button Edit address button.
	 * @return string
	 */
	public function account_edit_address_button( $button ) {
		return str_replace( 'button', 'button btn-primary', $button );
	}

	/**
	 * Add custom classes to WooCommerce account edit account button
	 *
	 * @param string $button Edit account button.
	 * @return string
	 */
	public function account_edit_account_button( $button ) {
		return str_replace( 'button', 'button btn-primary', $button );
	}

	/**
	 * Add custom classes to WooCommerce account login button
	 *
	 * @param string $button Login button.
	 * @return string
	 */
	public function account_login_button( $button ) {
		return str_replace( 'button', 'button btn-primary', $button );
	}

	/**
	 * Add custom classes to WooCommerce account register button
	 *
	 * @param string $button Register button.
	 * @return string
	 */
	public function account_register_button( $button ) {
		return str_replace( 'button', 'button btn-primary', $button );
	}

	/**
	 * Add custom classes to WooCommerce account lost password button
	 *
	 * @param string $button Lost password button.
	 * @return string
	 */
	public function account_lost_password_button( $button ) {
		return str_replace( 'button', 'button btn-primary', $button );
	}

	/**
	 * Add custom classes to WooCommerce account reset password button
	 *
	 * @param string $button Reset password button.
	 * @return string
	 */
	public function account_reset_password_button( $button ) {
		return str_replace( 'button', 'button btn-primary', $button );
	}
}