<?php
/**
 * AquaLuxe WooCommerce Integration
 *
 * This class handles the theme's integration with WooCommerce
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AquaLuxe_WooCommerce class
 */
class AquaLuxe_WooCommerce {

	/**
	 * Instance of this class
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Get instance of this class
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		// Only proceed if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Register hooks.
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_fragments' ) );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_image_wrapper_open' ), 5 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_image_wrapper_close' ), 15 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_actions_wrapper_open' ), 10 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_actions_wrapper_close' ), 14 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'quick_view_button' ), 12 );
		add_action( 'wp_footer', array( $this, 'quick_view_modal' ) );
		add_action( 'wp_ajax_aqualuxe_quick_view', array( $this, 'quick_view_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', array( $this, 'quick_view_ajax' ) );
		add_filter( 'woocommerce_checkout_fields', array( $this, 'optimize_checkout_fields' ) );
		add_filter( 'woocommerce_product_loop_title_classes', array( $this, 'product_title_classes' ) );
		add_filter( 'woocommerce_product_loop_start', array( $this, 'product_loop_start' ) );
		add_filter( 'woocommerce_product_loop_end', array( $this, 'product_loop_end' ) );
		add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );
		add_filter( 'woocommerce_pagination_args', array( $this, 'pagination_args' ) );
		add_filter( 'woocommerce_breadcrumb_defaults', array( $this, 'breadcrumb_defaults' ) );
		add_filter( 'woocommerce_product_tabs', array( $this, 'product_tabs' ) );
		add_filter( 'woocommerce_cross_sells_columns', array( $this, 'cross_sells_columns' ) );
		add_filter( 'woocommerce_cross_sells_total', array( $this, 'cross_sells_total' ) );
		add_filter( 'woocommerce_upsell_display_args', array( $this, 'upsell_display_args' ) );
		add_filter( 'woocommerce_product_description_heading', '__return_false' );
		add_filter( 'woocommerce_product_additional_information_heading', '__return_false' );
		add_action( 'woocommerce_before_main_content', array( $this, 'wrapper_start' ), 10 );
		add_action( 'woocommerce_after_main_content', array( $this, 'wrapper_end' ), 10 );
		add_action( 'woocommerce_sidebar', array( $this, 'sidebar' ), 10 );
	}

	/**
	 * Setup WooCommerce support
	 */
	public function setup() {
		// Add WooCommerce support.
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		// Remove default WooCommerce styles.
		add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
	}

	/**
	 * Enqueue scripts and styles
	 */
	public function enqueue_scripts() {
		// Only enqueue on WooCommerce pages.
		if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
			return;
		}

		$theme_version = wp_get_theme()->get( 'Version' );
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Enqueue WooCommerce styles.
		wp_enqueue_style(
			'aqualuxe-woocommerce',
			get_template_directory_uri() . '/dist/css/woocommerce' . $suffix . '.css',
			array(),
			$theme_version
		);

		// Enqueue WooCommerce scripts.
		wp_enqueue_script(
			'aqualuxe-woocommerce',
			get_template_directory_uri() . '/dist/js/woocommerce' . $suffix . '.js',
			array( 'jquery' ),
			$theme_version,
			true
		);

		// Localize script.
		wp_localize_script(
			'aqualuxe-woocommerce',
			'aqualuxeWooCommerce',
			array(
				'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( 'aqualuxe-woocommerce' ),
				'addToCartText' => esc_html__( 'Add to cart', 'aqualuxe' ),
				'viewCartText'  => esc_html__( 'View cart', 'aqualuxe' ),
				'i18n'          => array(
					'quickView'   => esc_html__( 'Quick View', 'aqualuxe' ),
					'addToCart'   => esc_html__( 'Add to cart', 'aqualuxe' ),
					'viewCart'    => esc_html__( 'View cart', 'aqualuxe' ),
					'adding'      => esc_html__( 'Adding...', 'aqualuxe' ),
					'added'       => esc_html__( 'Added!', 'aqualuxe' ),
					'loadMore'    => esc_html__( 'Load more', 'aqualuxe' ),
					'loading'     => esc_html__( 'Loading...', 'aqualuxe' ),
					'noMoreItems' => esc_html__( 'No more items', 'aqualuxe' ),
				),
			)
		);
	}

	/**
	 * Add mini cart fragments
	 *
	 * @param array $fragments Cart fragments.
	 * @return array Modified cart fragments.
	 */
	public function cart_fragments( $fragments ) {
		$fragments['.aqualuxe-mini-cart-count'] = '<span class="aqualuxe-mini-cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
		$fragments['.aqualuxe-mini-cart-total'] = '<span class="aqualuxe-mini-cart-total">' . WC()->cart->get_cart_total() . '</span>';
		$fragments['.aqualuxe-mini-cart-items'] = $this->get_mini_cart_items();
		
		return $fragments;
	}

	/**
	 * Get mini cart items
	 *
	 * @return string Mini cart items HTML.
	 */
	public function get_mini_cart_items() {
		ob_start();
		
		if ( ! WC()->cart->is_empty() ) :
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
					$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
					$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<div class="aqualuxe-mini-cart-item">
						<div class="aqualuxe-mini-cart-item-image">
							<?php if ( ! $_product->is_visible() ) : ?>
								<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php else : ?>
								<a href="<?php echo esc_url( $product_permalink ); ?>">
									<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</a>
							<?php endif; ?>
						</div>
						<div class="aqualuxe-mini-cart-item-details">
							<h4 class="aqualuxe-mini-cart-item-title">
								<?php if ( ! $_product->is_visible() ) : ?>
									<?php echo wp_kses_post( $product_name ); ?>
								<?php else : ?>
									<a href="<?php echo esc_url( $product_permalink ); ?>">
										<?php echo wp_kses_post( $product_name ); ?>
									</a>
								<?php endif; ?>
							</h4>
							<div class="aqualuxe-mini-cart-item-price">
								<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						</div>
						<div class="aqualuxe-mini-cart-item-remove">
							<?php
							echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								'woocommerce_cart_item_remove_link',
								sprintf(
									'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									esc_attr__( 'Remove this item', 'aqualuxe' ),
									esc_attr( $product_id ),
									esc_attr( $cart_item_key ),
									esc_attr( $_product->get_sku() )
								),
								$cart_item_key
							);
							?>
						</div>
					</div>
					<?php
				endif;
			endforeach;
		else :
			?>
			<div class="aqualuxe-mini-cart-empty">
				<p><?php esc_html_e( 'No products in the cart.', 'aqualuxe' ); ?></p>
			</div>
			<?php
		endif;
		
		return ob_get_clean();
	}

	/**
	 * Product image wrapper open
	 */
	public function product_image_wrapper_open() {
		echo '<div class="aqualuxe-product-image-wrapper">';
	}

	/**
	 * Product image wrapper close
	 */
	public function product_image_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Product actions wrapper open
	 */
	public function product_actions_wrapper_open() {
		echo '<div class="aqualuxe-product-actions">';
	}

	/**
	 * Product actions wrapper close
	 */
	public function product_actions_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Quick view button
	 */
	public function quick_view_button() {
		global $product;
		
		echo '<a href="#" class="aqualuxe-quick-view" data-product-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__( 'Quick View', 'aqualuxe' ) . '</a>';
	}

	/**
	 * Quick view modal
	 */
	public function quick_view_modal() {
		?>
		<div id="aqualuxe-quick-view-modal" class="aqualuxe-modal">
			<div class="aqualuxe-modal-overlay"></div>
			<div class="aqualuxe-modal-container">
				<div class="aqualuxe-modal-content">
					<span class="aqualuxe-modal-close">&times;</span>
					<div class="aqualuxe-modal-body">
						<div class="aqualuxe-quick-view-content"></div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Quick view AJAX handler
	 */
	public function quick_view_ajax() {
		check_ajax_referer( 'aqualuxe-woocommerce', 'nonce' );
		
		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		
		if ( ! $product_id ) {
			wp_send_json_error();
		}
		
		$product = wc_get_product( $product_id );
		
		if ( ! $product ) {
			wp_send_json_error();
		}
		
		ob_start();
		?>
		<div class="aqualuxe-quick-view-product">
			<div class="aqualuxe-quick-view-product-image">
				<?php echo $product->get_image( 'medium_large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="aqualuxe-quick-view-product-summary">
				<h2 class="aqualuxe-quick-view-product-title"><?php echo esc_html( $product->get_name() ); ?></h2>
				<div class="aqualuxe-quick-view-product-price">
					<?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="aqualuxe-quick-view-product-rating">
					<?php if ( $product->get_average_rating() ) : ?>
						<?php echo wc_get_rating_html( $product->get_average_rating() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php if ( $product->get_review_count() ) : ?>
							<span class="aqualuxe-quick-view-product-review-count">(<?php echo esc_html( $product->get_review_count() ); ?>)</span>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<div class="aqualuxe-quick-view-product-description">
					<?php echo wp_kses_post( $product->get_short_description() ); ?>
				</div>
				<div class="aqualuxe-quick-view-product-add-to-cart">
					<?php woocommerce_template_single_add_to_cart(); ?>
				</div>
				<div class="aqualuxe-quick-view-product-meta">
					<?php if ( wc_product_sku_enabled() && $product->get_sku() ) : ?>
						<span class="aqualuxe-quick-view-product-sku">
							<?php esc_html_e( 'SKU:', 'aqualuxe' ); ?> <?php echo esc_html( $product->get_sku() ); ?>
						</span>
					<?php endif; ?>
					<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="aqualuxe-quick-view-product-categories">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'aqualuxe' ) . ' ', '</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="aqualuxe-quick-view-product-actions">
					<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="aqualuxe-quick-view-product-view-details">
						<?php esc_html_e( 'View Details', 'aqualuxe' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
		$output = ob_get_clean();
		
		wp_send_json_success( array( 'html' => $output ) );
	}

	/**
	 * Optimize checkout fields
	 *
	 * @param array $fields Checkout fields.
	 * @return array Modified checkout fields.
	 */
	public function optimize_checkout_fields( $fields ) {
		// Make company field optional.
		if ( isset( $fields['billing']['billing_company'] ) ) {
			$fields['billing']['billing_company']['required'] = false;
			$fields['billing']['billing_company']['priority'] = 25;
		}
		
		if ( isset( $fields['shipping']['shipping_company'] ) ) {
			$fields['shipping']['shipping_company']['required'] = false;
			$fields['shipping']['shipping_company']['priority'] = 25;
		}
		
		// Add placeholder to all fields.
		foreach ( $fields as $section => $section_fields ) {
			foreach ( $section_fields as $key => $field ) {
				if ( ! isset( $field['placeholder'] ) || empty( $field['placeholder'] ) ) {
					$fields[ $section ][ $key ]['placeholder'] = $field['label'];
				}
			}
		}
		
		return $fields;
	}

	/**
	 * Product title classes
	 *
	 * @param string $classes Product title classes.
	 * @return string Modified product title classes.
	 */
	public function product_title_classes( $classes ) {
		return $classes . ' aqualuxe-product-title';
	}

	/**
	 * Product loop start
	 *
	 * @param string $loop_start Product loop start.
	 * @return string Modified product loop start.
	 */
	public function product_loop_start( $loop_start ) {
		$columns = wc_get_loop_prop( 'columns' );
		$loop_start = '<ul class="products aqualuxe-products columns-' . esc_attr( $columns ) . '">';
		
		return $loop_start;
	}

	/**
	 * Product loop end
	 *
	 * @param string $loop_end Product loop end.
	 * @return string Modified product loop end.
	 */
	public function product_loop_end( $loop_end ) {
		$loop_end = '</ul>';
		
		return $loop_end;
	}

	/**
	 * Related products args
	 *
	 * @param array $args Related products args.
	 * @return array Modified related products args.
	 */
	public function related_products_args( $args ) {
		$args['posts_per_page'] = 4;
		$args['columns'] = 4;
		
		return $args;
	}

	/**
	 * Pagination args
	 *
	 * @param array $args Pagination args.
	 * @return array Modified pagination args.
	 */
	public function pagination_args( $args ) {
		$args['prev_text'] = '&larr;';
		$args['next_text'] = '&rarr;';
		
		return $args;
	}

	/**
	 * Breadcrumb defaults
	 *
	 * @param array $args Breadcrumb args.
	 * @return array Modified breadcrumb args.
	 */
	public function breadcrumb_defaults( $args ) {
		$args['delimiter'] = '<span class="aqualuxe-breadcrumb-delimiter">/</span>';
		$args['wrap_before'] = '<nav class="woocommerce-breadcrumb aqualuxe-breadcrumb">';
		$args['wrap_after'] = '</nav>';
		
		return $args;
	}

	/**
	 * Product tabs
	 *
	 * @param array $tabs Product tabs.
	 * @return array Modified product tabs.
	 */
	public function product_tabs( $tabs ) {
		// Add custom tab.
		$tabs['aqualuxe_custom_tab'] = array(
			'title'    => esc_html__( 'Custom Info', 'aqualuxe' ),
			'priority' => 30,
			'callback' => array( $this, 'custom_tab_content' ),
		);
		
		return $tabs;
	}

	/**
	 * Custom tab content
	 */
	public function custom_tab_content() {
		// Get custom tab content from theme mod.
		$custom_tab_content = get_theme_mod( 'aqualuxe_product_custom_tab_content', '' );
		
		if ( empty( $custom_tab_content ) ) {
			$custom_tab_content = esc_html__( 'This is a custom tab. You can add custom content here from the theme customizer.', 'aqualuxe' );
		}
		
		echo wp_kses_post( wpautop( $custom_tab_content ) );
	}

	/**
	 * Cross sells columns
	 *
	 * @param int $columns Cross sells columns.
	 * @return int Modified cross sells columns.
	 */
	public function cross_sells_columns( $columns ) {
		return 2;
	}

	/**
	 * Cross sells total
	 *
	 * @param int $total Cross sells total.
	 * @return int Modified cross sells total.
	 */
	public function cross_sells_total( $total ) {
		return 2;
	}

	/**
	 * Upsell display args
	 *
	 * @param array $args Upsell display args.
	 * @return array Modified upsell display args.
	 */
	public function upsell_display_args( $args ) {
		$args['columns'] = 4;
		$args['posts_per_page'] = 4;
		
		return $args;
	}

	/**
	 * Wrapper start
	 */
	public function wrapper_start() {
		echo '<div class="aqualuxe-woocommerce-wrapper">';
	}

	/**
	 * Wrapper end
	 */
	public function wrapper_end() {
		echo '</div>';
	}

	/**
	 * Sidebar
	 */
	public function sidebar() {
		// Check if sidebar is enabled.
		$sidebar_enabled = get_theme_mod( 'aqualuxe_woocommerce_sidebar', true );
		
		if ( ! $sidebar_enabled ) {
			return;
		}
		
		// Check if we're on a product page.
		if ( is_product() ) {
			$product_sidebar = get_theme_mod( 'aqualuxe_product_sidebar', false );
			
			if ( ! $product_sidebar ) {
				return;
			}
		}
		
		echo '<div class="aqualuxe-woocommerce-sidebar">';
		dynamic_sidebar( 'woocommerce-sidebar' );
		echo '</div>';
	}
}

// Initialize the class.
AquaLuxe_WooCommerce::get_instance();