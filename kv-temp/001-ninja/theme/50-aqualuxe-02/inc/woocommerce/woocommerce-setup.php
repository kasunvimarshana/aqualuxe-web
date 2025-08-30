<?php
/**
 * WooCommerce setup
 *
 * @package AquaLuxe
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 *
 * @return void
 */
function aqualuxe_woocommerce_setup() {
	// Add theme support for WooCommerce.
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 400,
			'single_image_width'    => 800,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 4,
				'min_columns'     => 1,
				'max_columns'     => 6,
			),
		)
	);

	// Add theme support for WooCommerce product gallery features.
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
	// Enqueue WooCommerce styles.
	wp_enqueue_style( 'aqualuxe-woocommerce', aqualuxe_asset( 'css/woocommerce.css' ), array( 'aqualuxe-style' ), AQUALUXE_VERSION );

	// Add WooCommerce specific JavaScript.
	wp_enqueue_script( 'aqualuxe-woocommerce', aqualuxe_asset( 'js/woocommerce.js' ), array( 'jquery' ), AQUALUXE_VERSION, true );

	// Add WooCommerce data to JavaScript.
	wp_localize_script(
		'aqualuxe-woocommerce',
		'aqualuxeWooCommerce',
		array(
			'ajaxUrl'             => admin_url( 'admin-ajax.php' ),
			'nonce'              => wp_create_nonce( 'aqualuxe-woocommerce' ),
			'isCart'             => is_cart(),
			'isCheckout'         => is_checkout(),
			'isAccount'          => is_account_page(),
			'isProduct'          => is_product(),
			'isShop'             => is_shop(),
			'cartUrl'            => wc_get_cart_url(),
			'checkoutUrl'        => wc_get_checkout_url(),
			'quickViewEnabled'   => get_theme_mod( 'aqualuxe_quick_view_enable', true ),
			'wishlistEnabled'    => get_theme_mod( 'aqualuxe_wishlist_enable', true ),
			'strings'            => array(
				'addToCart'      => esc_html__( 'Add to cart', 'aqualuxe' ),
				'addingToCart'   => esc_html__( 'Adding...', 'aqualuxe' ),
				'addedToCart'    => esc_html__( 'Added to cart', 'aqualuxe' ),
				'viewCart'       => esc_html__( 'View cart', 'aqualuxe' ),
				'errorMessage'   => esc_html__( 'Something went wrong. Please try again.', 'aqualuxe' ),
				'quickView'      => esc_html__( 'Quick View', 'aqualuxe' ),
				'addToWishlist'  => esc_html__( 'Add to wishlist', 'aqualuxe' ),
				'removeFromWishlist' => esc_html__( 'Remove from wishlist', 'aqualuxe' ),
				'addedToWishlist'    => esc_html__( 'Added to wishlist', 'aqualuxe' ),
				'removedFromWishlist' => esc_html__( 'Removed from wishlist', 'aqualuxe' ),
			),
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
		'posts_per_page' => get_theme_mod( 'aqualuxe_related_products_count', 4 ),
		'columns'        => get_theme_mod( 'aqualuxe_related_products_columns', 4 ),
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

/**
 * Add custom WooCommerce wrapper.
 */
function aqualuxe_woocommerce_wrapper_before() {
	?>
	<main id="primary" class="site-main">
		<div class="container">
	<?php
}
add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before' );

/**
 * Add custom WooCommerce wrapper end.
 */
function aqualuxe_woocommerce_wrapper_after() {
	?>
		</div><!-- .container -->
	</main><!-- #main -->
	<?php
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

/**
 * Cart Fragments.
 *
 * Ensure cart contents update when products are added to the cart via AJAX.
 *
 * @param array $fragments Fragments to refresh via AJAX.
 * @return array Fragments to refresh via AJAX.
 */
function aqualuxe_woocommerce_cart_fragments( $fragments ) {
	ob_start();
	aqualuxe_woocommerce_cart_count();
	$fragments['.cart-count'] = ob_get_clean();

	ob_start();
	aqualuxe_woocommerce_cart_dropdown();
	$fragments['.cart-dropdown'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_fragments' );

/**
 * Cart Count.
 */
function aqualuxe_woocommerce_cart_count() {
	$cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	?>
	<span class="cart-count"><?php echo esc_html( $cart_count ); ?></span>
	<?php
}

/**
 * Cart Dropdown.
 */
function aqualuxe_woocommerce_cart_dropdown() {
	$cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	$cart_total = WC()->cart ? WC()->cart->get_cart_total() : wc_price( 0 );
	$cart_url = wc_get_cart_url();
	$checkout_url = wc_get_checkout_url();
	?>
	<div class="cart-dropdown">
		<div class="cart-dropdown-inner">
			<?php if ( $cart_count > 0 ) : ?>
				<div class="cart-items">
					<?php
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
							?>
							<div class="cart-item">
								<div class="cart-item-image">
									<?php
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

									if ( ! $product_permalink ) {
										echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									} else {
										printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									}
									?>
								</div>
								
								<div class="cart-item-details">
									<div class="cart-item-title">
										<?php
										if ( ! $product_permalink ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
										} else {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
										}
										?>
									</div>
									
									<div class="cart-item-quantity-price">
										<span class="cart-item-quantity">
											<?php echo esc_html( $cart_item['quantity'] ); ?> &times;
										</span>
										<span class="cart-item-price">
											<?php
											echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											?>
										</span>
									</div>
								</div>
								
								<div class="cart-item-remove">
									<?php
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">
												<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
													<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
												</svg>
											</a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_html__( 'Remove this item', 'aqualuxe' ),
											esc_attr( $product_id ),
											esc_attr( $_product->get_sku() )
										),
										$cart_item_key
									);
									?>
								</div>
							</div>
							<?php
						}
					}
					?>
				</div>
				
				<div class="cart-subtotal">
					<span class="subtotal-label"><?php esc_html_e( 'Subtotal:', 'aqualuxe' ); ?></span>
					<span class="subtotal-value"><?php echo $cart_total; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</div>
				
				<div class="cart-actions">
					<a href="<?php echo esc_url( $cart_url ); ?>" class="btn btn-outline-primary"><?php esc_html_e( 'View Cart', 'aqualuxe' ); ?></a>
					<a href="<?php echo esc_url( $checkout_url ); ?>" class="btn btn-primary"><?php esc_html_e( 'Checkout', 'aqualuxe' ); ?></a>
				</div>
			<?php else : ?>
				<div class="cart-empty">
					<div class="cart-empty-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="48" height="48">
							<path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" />
						</svg>
					</div>
					<p><?php esc_html_e( 'Your cart is currently empty.', 'aqualuxe' ); ?></p>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Start Shopping', 'aqualuxe' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Header Cart.
 */
function aqualuxe_woocommerce_header_cart() {
	if ( ! get_theme_mod( 'aqualuxe_cart_icon_enable', true ) ) {
		return;
	}

	$cart_url = wc_get_cart_url();
	?>
	<div class="header-cart">
		<a href="<?php echo esc_url( $cart_url ); ?>" class="cart-icon-link" aria-label="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
				<path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" />
			</svg>
			<?php aqualuxe_woocommerce_cart_count(); ?>
		</a>
		
		<?php if ( get_theme_mod( 'aqualuxe_cart_dropdown_enable', true ) ) : ?>
			<?php aqualuxe_woocommerce_cart_dropdown(); ?>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Set the number of products per page.
 *
 * @return int
 */
function aqualuxe_woocommerce_products_per_page() {
	return get_theme_mod( 'aqualuxe_products_per_page', 12 );
}
add_filter( 'loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page' );

/**
 * Set the number of columns in the product grid.
 *
 * @return int
 */
function aqualuxe_woocommerce_loop_columns() {
	return get_theme_mod( 'aqualuxe_product_columns', 4 );
}
add_filter( 'loop_shop_columns', 'aqualuxe_woocommerce_loop_columns' );

/**
 * Add quick view button to product loops.
 */
function aqualuxe_woocommerce_quick_view_button() {
	if ( ! get_theme_mod( 'aqualuxe_quick_view_enable', true ) ) {
		return;
	}

	global $product;
	?>
	<button class="quick-view-button" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php esc_attr_e( 'Quick view', 'aqualuxe' ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
			<path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
			<path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd" />
		</svg>
		<?php esc_html_e( 'Quick View', 'aqualuxe' ); ?>
	</button>
	<?php
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_quick_view_button', 15 );

/**
 * Add wishlist button to product loops.
 */
function aqualuxe_woocommerce_wishlist_button() {
	if ( ! get_theme_mod( 'aqualuxe_wishlist_enable', true ) ) {
		return;
	}

	global $product;
	?>
	<button class="wishlist-toggle" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php esc_attr_e( 'Add to wishlist', 'aqualuxe' ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
			<path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
		</svg>
	</button>
	<?php
}
add_action( 'woocommerce_before_shop_loop_item', 'aqualuxe_woocommerce_wishlist_button', 15 );

/**
 * Add quick view modal to footer.
 */
function aqualuxe_woocommerce_quick_view_modal() {
	if ( ! get_theme_mod( 'aqualuxe_quick_view_enable', true ) ) {
		return;
	}
	?>
	<div id="quick-view-modal" class="quick-view-modal">
		<div class="quick-view-overlay"></div>
		<div class="quick-view-content">
			<button class="quick-view-close" aria-label="<?php esc_attr_e( 'Close quick view', 'aqualuxe' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
					<path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" />
				</svg>
			</button>
			<div class="quick-view-product"></div>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'aqualuxe_woocommerce_quick_view_modal' );

/**
 * AJAX quick view.
 */
function aqualuxe_woocommerce_ajax_quick_view() {
	check_ajax_referer( 'aqualuxe-woocommerce', 'nonce' );

	if ( ! isset( $_POST['product_id'] ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid product ID', 'aqualuxe' ) ) );
	}

	$product_id = absint( $_POST['product_id'] );
	$product = wc_get_product( $product_id );

	if ( ! $product ) {
		wp_send_json_error( array( 'message' => __( 'Product not found', 'aqualuxe' ) ) );
	}

	ob_start();
	?>
	<div class="quick-view-product">
		<div class="quick-view-images">
			<?php echo $product->get_image( 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		
		<div class="quick-view-summary">
			<h2 class="product_title"><?php echo esc_html( $product->get_name() ); ?></h2>
			
			<div class="price">
				<?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			
			<div class="woocommerce-product-details__short-description">
				<?php echo wp_kses_post( $product->get_short_description() ); ?>
			</div>
			
			<?php if ( $product->is_in_stock() ) : ?>
				<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype="multipart/form-data">
					<?php if ( $product->is_type( 'simple' ) ) : ?>
						<div class="quantity">
							<input type="number" name="quantity" value="1" min="1" max="<?php echo esc_attr( $product->get_stock_quantity() ? $product->get_stock_quantity() : '' ); ?>" step="1" />
						</div>
						
						<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="button add_to_cart_button"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
					<?php else : ?>
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="button"><?php esc_html_e( 'View Product', 'aqualuxe' ); ?></a>
					<?php endif; ?>
				</form>
			<?php else : ?>
				<p class="stock out-of-stock"><?php esc_html_e( 'Out of stock', 'aqualuxe' ); ?></p>
			<?php endif; ?>
			
			<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="view-full-details"><?php esc_html_e( 'View Full Details', 'aqualuxe' ); ?></a>
		</div>
	</div>
	<?php
	$html = ob_get_clean();

	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_woocommerce_ajax_quick_view' );
add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_woocommerce_ajax_quick_view' );

/**
 * AJAX wishlist.
 */
function aqualuxe_woocommerce_ajax_wishlist() {
	check_ajax_referer( 'aqualuxe-woocommerce', 'nonce' );

	if ( ! isset( $_POST['product_id'] ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid product ID', 'aqualuxe' ) ) );
	}

	$product_id = absint( $_POST['product_id'] );
	$action = isset( $_POST['action_type'] ) ? sanitize_text_field( wp_unslash( $_POST['action_type'] ) ) : 'add';

	// Get current wishlist.
	$wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : array();

	if ( ! is_array( $wishlist ) ) {
		$wishlist = array();
	}

	if ( 'add' === $action ) {
		// Add product to wishlist.
		if ( ! in_array( $product_id, $wishlist, true ) ) {
			$wishlist[] = $product_id;
		}

		$message = __( 'Product added to wishlist', 'aqualuxe' );
	} else {
		// Remove product from wishlist.
		$key = array_search( $product_id, $wishlist, true );
		if ( false !== $key ) {
			unset( $wishlist[ $key ] );
			$wishlist = array_values( $wishlist );
		}

		$message = __( 'Product removed from wishlist', 'aqualuxe' );
	}

	// Save wishlist.
	setcookie( 'aqualuxe_wishlist', wp_json_encode( $wishlist ), time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );

	wp_send_json_success( array( 'message' => $message, 'wishlist' => $wishlist ) );
}
add_action( 'wp_ajax_aqualuxe_wishlist', 'aqualuxe_woocommerce_ajax_wishlist' );
add_action( 'wp_ajax_nopriv_aqualuxe_wishlist', 'aqualuxe_woocommerce_ajax_wishlist' );

/**
 * Change number of products that are displayed per page (shop page).
 *
 * @param array $args Shop page args.
 * @return array
 */
function aqualuxe_woocommerce_shop_per_page( $args ) {
	$args['posts_per_page'] = get_theme_mod( 'aqualuxe_products_per_page', 12 );

	return $args;
}
add_filter( 'woocommerce_product_query_args', 'aqualuxe_woocommerce_shop_per_page' );

/**
 * Change number of related products.
 *
 * @param array $args Related products args.
 * @return array
 */
function aqualuxe_woocommerce_related_products_args( $args ) {
	$args['posts_per_page'] = get_theme_mod( 'aqualuxe_related_products_count', 4 );
	$args['columns'] = get_theme_mod( 'aqualuxe_related_products_columns', 4 );

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args' );

/**
 * Change number of upsells.
 *
 * @param array $args Upsell args.
 * @return array
 */
function aqualuxe_woocommerce_upsell_display_args( $args ) {
	$args['posts_per_page'] = get_theme_mod( 'aqualuxe_related_products_count', 4 );
	$args['columns'] = get_theme_mod( 'aqualuxe_related_products_columns', 4 );

	return $args;
}
add_filter( 'woocommerce_upsell_display_args', 'aqualuxe_woocommerce_upsell_display_args' );

/**
 * Change number of cross sells.
 *
 * @param array $args Cross sells args.
 * @return array
 */
function aqualuxe_woocommerce_cross_sells_args( $args ) {
	$args['posts_per_page'] = get_theme_mod( 'aqualuxe_related_products_count', 4 );
	$args['columns'] = get_theme_mod( 'aqualuxe_related_products_columns', 4 );

	return $args;
}
add_filter( 'woocommerce_cross_sells_args', 'aqualuxe_woocommerce_cross_sells_args' );

/**
 * Change number of products displayed per row.
 *
 * @return int
 */
function aqualuxe_woocommerce_loop_columns() {
	return get_theme_mod( 'aqualuxe_product_columns', 4 );
}
add_filter( 'loop_shop_columns', 'aqualuxe_woocommerce_loop_columns' );

/**
 * Add wrapper to product loop.
 */
function aqualuxe_woocommerce_before_shop_loop() {
	echo '<div class="products-wrapper">';
}
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_woocommerce_before_shop_loop', 40 );

/**
 * Close wrapper after product loop.
 */
function aqualuxe_woocommerce_after_shop_loop() {
	echo '</div>';
}
add_action( 'woocommerce_after_shop_loop', 'aqualuxe_woocommerce_after_shop_loop', 5 );

/**
 * Add wrapper to product loop item.
 */
function aqualuxe_woocommerce_before_shop_loop_item() {
	echo '<div class="product-inner">';
}
add_action( 'woocommerce_before_shop_loop_item', 'aqualuxe_woocommerce_before_shop_loop_item', 5 );

/**
 * Close wrapper after product loop item.
 */
function aqualuxe_woocommerce_after_shop_loop_item() {
	echo '</div>';
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_after_shop_loop_item', 20 );

/**
 * Add wrapper to product thumbnail.
 */
function aqualuxe_woocommerce_before_shop_loop_item_title() {
	echo '<div class="product-image-wrapper">';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_before_shop_loop_item_title', 5 );

/**
 * Close wrapper after product thumbnail.
 */
function aqualuxe_woocommerce_shop_loop_item_title() {
	echo '</div>';
}
add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_shop_loop_item_title', 5 );

/**
 * Add wrapper to product title and price.
 */
function aqualuxe_woocommerce_shop_loop_item_title_wrapper() {
	echo '<div class="product-details">';
}
add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_shop_loop_item_title_wrapper', 7 );

/**
 * Close wrapper after product title and price.
 */
function aqualuxe_woocommerce_after_shop_loop_item_title() {
	echo '</div>';
}
add_action( 'woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_after_shop_loop_item_title', 12 );

/**
 * Add wrapper to product actions.
 */
function aqualuxe_woocommerce_after_shop_loop_item_actions() {
	echo '<div class="product-actions">';
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_after_shop_loop_item_actions', 9 );

/**
 * Close wrapper after product actions.
 */
function aqualuxe_woocommerce_after_shop_loop_item_actions_end() {
	echo '</div>';
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_after_shop_loop_item_actions_end', 19 );

/**
 * Add social sharing to product page.
 */
function aqualuxe_woocommerce_share() {
	if ( ! get_theme_mod( 'aqualuxe_social_sharing', true ) ) {
		return;
	}

	if ( function_exists( 'aqualuxe_social_sharing' ) ) {
		echo '<div class="product-share">';
		echo '<h4>' . esc_html__( 'Share:', 'aqualuxe' ) . '</h4>';
		aqualuxe_social_sharing();
		echo '</div>';
	}
}
add_action( 'woocommerce_share', 'aqualuxe_woocommerce_share' );

/**
 * Add product meta to single product page.
 */
function aqualuxe_woocommerce_product_meta() {
	global $product;

	echo '<div class="product-meta">';

	// SKU.
	if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) {
		echo '<span class="sku_wrapper">' . esc_html__( 'SKU:', 'aqualuxe' ) . ' <span class="sku">' . ( $product->get_sku() ? $product->get_sku() : esc_html__( 'N/A', 'aqualuxe' ) ) . '</span></span>';
	}

	// Categories.
	echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'aqualuxe' ) . ' ', '</span>' );

	// Tags.
	echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'aqualuxe' ) . ' ', '</span>' );

	echo '</div>';
}
add_action( 'woocommerce_product_meta_end', 'aqualuxe_woocommerce_product_meta' );

/**
 * Add product tabs to single product page.
 *
 * @param array $tabs Product tabs.
 * @return array
 */
function aqualuxe_woocommerce_product_tabs( $tabs ) {
	// Add custom tab.
	$tabs['shipping'] = array(
		'title'    => __( 'Shipping & Returns', 'aqualuxe' ),
		'priority' => 30,
		'callback' => 'aqualuxe_woocommerce_shipping_tab',
	);

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs' );

/**
 * Shipping tab content.
 */
function aqualuxe_woocommerce_shipping_tab() {
	// Get shipping content from theme mod or use default.
	$shipping_content = get_theme_mod( 'aqualuxe_shipping_content', '' );

	if ( empty( $shipping_content ) ) {
		$shipping_content = '<h4>' . __( 'Shipping Information', 'aqualuxe' ) . '</h4>
		<p>' . __( 'We ship worldwide with special care for live aquatic species. Shipping times and costs vary depending on your location and the type of products ordered.', 'aqualuxe' ) . '</p>
		<ul>
			<li>' . __( 'Domestic shipping: 1-3 business days', 'aqualuxe' ) . '</li>
			<li>' . __( 'International shipping: 3-7 business days', 'aqualuxe' ) . '</li>
		</ul>
		<h4>' . __( 'Returns & Exchanges', 'aqualuxe' ) . '</h4>
		<p>' . __( 'We want you to be completely satisfied with your purchase. If you are not satisfied, please contact us within 14 days of receiving your order.', 'aqualuxe' ) . '</p>
		<ul>
			<li>' . __( 'Non-living products: 30-day return policy', 'aqualuxe' ) . '</li>
			<li>' . __( 'Living organisms: 7-day guarantee (DOA policy applies)', 'aqualuxe' ) . '</li>
		</ul>';
	}

	echo wp_kses_post( wpautop( $shipping_content ) );
}

/**
 * Add product features to single product page.
 */
function aqualuxe_woocommerce_product_features() {
	global $product;

	// Get product features from product meta or use default.
	$product_features = get_post_meta( $product->get_id(), '_product_features', true );

	if ( empty( $product_features ) ) {
		$product_features = array(
			array(
				'icon'  => 'shipping',
				'title' => __( 'Free Shipping', 'aqualuxe' ),
				'text'  => __( 'On orders over $100', 'aqualuxe' ),
			),
			array(
				'icon'  => 'guarantee',
				'title' => __( 'Quality Guarantee', 'aqualuxe' ),
				'text'  => __( '100% satisfaction', 'aqualuxe' ),
			),
			array(
				'icon'  => 'support',
				'title' => __( 'Expert Support', 'aqualuxe' ),
				'text'  => __( '24/7 customer service', 'aqualuxe' ),
			),
			array(
				'icon'  => 'secure',
				'title' => __( 'Secure Payment', 'aqualuxe' ),
				'text'  => __( 'SSL encrypted checkout', 'aqualuxe' ),
			),
		);
	}

	if ( ! empty( $product_features ) ) {
		echo '<div class="product-features">';
		
		foreach ( $product_features as $feature ) {
			echo '<div class="product-feature">';
			
			if ( ! empty( $feature['icon'] ) ) {
				echo '<div class="feature-icon">';
				// Add icon based on feature type.
				switch ( $feature['icon'] ) {
					case 'shipping':
						echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 116 0h3a.75.75 0 00.75-.75V15z" /><path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z" /><path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" /></svg>';
						break;
					case 'guarantee':
						echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg>';
						break;
					case 'support':
						echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path fill-rule="evenodd" d="M4.5 9.75a6 6 0 0111.573-2.226 3.75 3.75 0 014.133 4.303A4.5 4.5 0 0118 20.25H6.75a5.25 5.25 0 01-2.23-10.004 6.072 6.072 0 01-.02-.496z" clip-rule="evenodd" /></svg>';
						break;
					case 'secure':
						echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" /></svg>';
						break;
					default:
						echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm11.378-3.917c-.89-.777-2.366-.777-3.255 0a.75.75 0 01-.988-1.129c1.454-1.272 3.776-1.272 5.23 0 1.513 1.324 1.513 3.518 0 4.842a3.75 3.75 0 01-.837.552c-.676.328-1.028.774-1.028 1.152v.75a.75.75 0 01-1.5 0v-.75c0-1.279 1.06-2.107 1.875-2.502.182-.088.351-.199.503-.331.83-.727.83-1.857 0-2.584zM12 18a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" /></svg>';
						break;
				}
				echo '</div>';
			}
			
			if ( ! empty( $feature['title'] ) ) {
				echo '<h4 class="feature-title">' . esc_html( $feature['title'] ) . '</h4>';
			}
			
			if ( ! empty( $feature['text'] ) ) {
				echo '<p class="feature-text">' . esc_html( $feature['text'] ) . '</p>';
			}
			
			echo '</div>';
		}
		
		echo '</div>';
	}
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_features', 25 );

/**
 * Add product countdown to single product page.
 */
function aqualuxe_woocommerce_product_countdown() {
	global $product;

	// Check if product is on sale.
	if ( ! $product->is_on_sale() ) {
		return;
	}

	// Get sale end date.
	$sale_end_date = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );

	// If no sale end date, return.
	if ( empty( $sale_end_date ) ) {
		return;
	}

	// Format date.
	$sale_end_date = date( 'Y-m-d H:i:s', $sale_end_date );
	?>
	<div class="product-countdown" data-end-date="<?php echo esc_attr( $sale_end_date ); ?>">
		<div class="countdown-label"><?php esc_html_e( 'Sale Ends In:', 'aqualuxe' ); ?></div>
		<div class="countdown-timer">
			<div class="countdown-item">
				<span class="countdown-days">00</span>
				<span class="countdown-label"><?php esc_html_e( 'Days', 'aqualuxe' ); ?></span>
			</div>
			<div class="countdown-item">
				<span class="countdown-hours">00</span>
				<span class="countdown-label"><?php esc_html_e( 'Hours', 'aqualuxe' ); ?></span>
			</div>
			<div class="countdown-item">
				<span class="countdown-minutes">00</span>
				<span class="countdown-label"><?php esc_html_e( 'Minutes', 'aqualuxe' ); ?></span>
			</div>
			<div class="countdown-item">
				<span class="countdown-seconds">00</span>
				<span class="countdown-label"><?php esc_html_e( 'Seconds', 'aqualuxe' ); ?></span>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_countdown', 15 );

/**
 * Add product trust badges to single product page.
 */
function aqualuxe_woocommerce_product_trust_badges() {
	?>
	<div class="product-trust-badges">
		<div class="trust-badge">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/trust-badge-1.png' ); ?>" alt="<?php esc_attr_e( 'Secure Payment', 'aqualuxe' ); ?>">
		</div>
		<div class="trust-badge">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/trust-badge-2.png' ); ?>" alt="<?php esc_attr_e( 'Money Back Guarantee', 'aqualuxe' ); ?>">
		</div>
		<div class="trust-badge">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/trust-badge-3.png' ); ?>" alt="<?php esc_attr_e( 'Free Shipping', 'aqualuxe' ); ?>">
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_trust_badges', 40 );

/**
 * Add product recently viewed section.
 */
function aqualuxe_woocommerce_recently_viewed_products() {
	if ( ! is_singular( 'product' ) ) {
		return;
	}

	// Get recently viewed products.
	$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) : array();
	$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

	// Remove current product.
	$current_product_id = get_the_ID();
	$viewed_products = array_diff( $viewed_products, array( $current_product_id ) );

	// Show only if we have recently viewed products.
	if ( empty( $viewed_products ) ) {
		return;
	}

	// Limit to 4 products.
	$viewed_products = array_slice( $viewed_products, 0, 4 );

	// Get products.
	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => 4,
		'post__in'       => $viewed_products,
		'orderby'        => 'post__in',
	);

	$products = new WP_Query( $args );

	if ( $products->have_posts() ) {
		?>
		<section class="recently-viewed-products">
			<h2><?php esc_html_e( 'Recently Viewed Products', 'aqualuxe' ); ?></h2>
			<div class="products columns-4">
				<?php
				while ( $products->have_posts() ) {
					$products->the_post();
					wc_get_template_part( 'content', 'product' );
				}
				?>
			</div>
		</section>
		<?php
	}

	wp_reset_postdata();
}
add_action( 'woocommerce_after_single_product', 'aqualuxe_woocommerce_recently_viewed_products' );

/**
 * Track product views.
 */
function aqualuxe_woocommerce_track_product_view() {
	if ( ! is_singular( 'product' ) ) {
		return;
	}

	global $post;

	if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) {
		$viewed_products = array();
	} else {
		$viewed_products = (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) );
	}

	// Remove current product.
	$viewed_products = array_diff( $viewed_products, array( $post->ID ) );

	// Add current product.
	$viewed_products[] = $post->ID;

	// Limit to 15 products.
	if ( count( $viewed_products ) > 15 ) {
		array_shift( $viewed_products );
	}

	// Store for session only.
	wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
}
add_action( 'template_redirect', 'aqualuxe_woocommerce_track_product_view', 20 );

/**
 * Add product brand to single product page.
 */
function aqualuxe_woocommerce_product_brand() {
	global $product;

	// Get product brand.
	$brands = get_the_terms( $product->get_id(), 'product_brand' );

	if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) {
		echo '<div class="product-brand">';
		echo '<span class="brand-label">' . esc_html__( 'Brand:', 'aqualuxe' ) . '</span>';
		
		$brand_links = array();
		foreach ( $brands as $brand ) {
			$brand_links[] = '<a href="' . esc_url( get_term_link( $brand ) ) . '">' . esc_html( $brand->name ) . '</a>';
		}
		
		echo implode( ', ', $brand_links );
		echo '</div>';
	}
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_brand', 7 );

/**
 * Add product stock status to single product page.
 */
function aqualuxe_woocommerce_product_stock_status() {
	global $product;

	// Get stock status.
	$stock_status = $product->get_stock_status();
	$stock_quantity = $product->get_stock_quantity();

	echo '<div class="product-stock-status">';
	
	if ( 'instock' === $stock_status ) {
		echo '<span class="in-stock">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg>';
		
		if ( $stock_quantity ) {
			/* translators: %d: stock quantity */
			echo esc_html( sprintf( __( 'In Stock (%d available)', 'aqualuxe' ), $stock_quantity ) );
		} else {
			echo esc_html__( 'In Stock', 'aqualuxe' );
		}
		
		echo '</span>';
	} elseif ( 'outofstock' === $stock_status ) {
		echo '<span class="out-of-stock">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd" /></svg>';
		echo esc_html__( 'Out of Stock', 'aqualuxe' );
		echo '</span>';
	} elseif ( 'onbackorder' === $stock_status ) {
		echo '<span class="on-backorder">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" /></svg>';
		echo esc_html__( 'On Backorder', 'aqualuxe' );
		echo '</span>';
	}
	
	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_stock_status', 10 );

/**
 * Add product delivery information to single product page.
 */
function aqualuxe_woocommerce_product_delivery() {
	global $product;

	// Get delivery information from product meta or use default.
	$delivery_info = get_post_meta( $product->get_id(), '_delivery_info', true );

	if ( empty( $delivery_info ) ) {
		$delivery_info = __( 'Free shipping on orders over $100. Estimated delivery: 3-5 business days.', 'aqualuxe' );
	}

	echo '<div class="product-delivery">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 116 0h3a.75.75 0 00.75-.75V15z" /><path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z" /><path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" /></svg>';
	echo '<span>' . esc_html( $delivery_info ) . '</span>';
	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_delivery', 30 );

/**
 * Add size guide to single product page.
 */
function aqualuxe_woocommerce_size_guide() {
	global $product;

	// Check if product has attributes.
	if ( ! $product->has_attributes() ) {
		return;
	}

	// Get size guide from product meta or use default.
	$size_guide = get_post_meta( $product->get_id(), '_size_guide', true );

	if ( empty( $size_guide ) ) {
		return;
	}

	echo '<div class="product-size-guide">';
	echo '<a href="#" class="size-guide-toggle">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M11.47 4.72a.75.75 0 011.06 0l3.75 3.75a.75.75 0 01-1.06 1.06L12 6.31 8.78 9.53a.75.75 0 01-1.06-1.06l3.75-3.75zm-3.75 9.75a.75.75 0 011.06 0L12 17.69l3.22-3.22a.75.75 0 111.06 1.06l-3.75 3.75a.75.75 0 01-1.06 0l-3.75-3.75a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>';
	echo esc_html__( 'Size Guide', 'aqualuxe' );
	echo '</a>';
	echo '<div class="size-guide-content hidden">';
	echo wp_kses_post( wpautop( $size_guide ) );
	echo '</div>';
	echo '</div>';
}
add_action( 'woocommerce_before_add_to_cart_form', 'aqualuxe_woocommerce_size_guide' );

/**
 * Add estimated delivery date to single product page.
 */
function aqualuxe_woocommerce_estimated_delivery() {
	global $product;

	// Get estimated delivery from product meta or use default.
	$estimated_delivery = get_post_meta( $product->get_id(), '_estimated_delivery', true );

	if ( empty( $estimated_delivery ) ) {
		// Calculate estimated delivery date (3-5 business days from now).
		$min_days = 3;
		$max_days = 5;
		
		$min_date = strtotime( '+' . $min_days . ' weekday' );
		$max_date = strtotime( '+' . $max_days . ' weekday' );
		
		$min_date_formatted = date_i18n( get_option( 'date_format' ), $min_date );
		$max_date_formatted = date_i18n( get_option( 'date_format' ), $max_date );
		
		$estimated_delivery = sprintf(
			/* translators: %1$s: minimum delivery date, %2$s: maximum delivery date */
			__( 'Estimated delivery: %1$s - %2$s', 'aqualuxe' ),
			$min_date_formatted,
			$max_date_formatted
		);
	}

	echo '<div class="product-estimated-delivery">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" /></svg>';
	echo '<span>' . esc_html( $estimated_delivery ) . '</span>';
	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_estimated_delivery', 35 );

/**
 * Add product inquiry form to single product page.
 */
function aqualuxe_woocommerce_product_inquiry() {
	global $product;

	// Get inquiry form shortcode from theme mod or use default.
	$inquiry_form_shortcode = get_theme_mod( 'aqualuxe_product_inquiry_form', '' );

	if ( empty( $inquiry_form_shortcode ) ) {
		return;
	}

	echo '<div class="product-inquiry">';
	echo '<a href="#" class="inquiry-toggle">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M4.848 2.771A49.144 49.144 0 0112 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 01-3.476.383.39.39 0 00-.297.17l-2.755 4.133a.75.75 0 01-1.248 0l-2.755-4.133a.39.39 0 00-.297-.17 48.9 48.9 0 01-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.68 3.348-3.97zM6.75 8.25a.75.75 0 01.75-.75h9a.75.75 0 010 1.5h-9a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5H12a.75.75 0 000-1.5H7.5z" clip-rule="evenodd" /></svg>';
	echo esc_html__( 'Ask a Question', 'aqualuxe' );
	echo '</a>';
	echo '<div class="inquiry-form hidden">';
	echo do_shortcode( $inquiry_form_shortcode );
	echo '</div>';
	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_inquiry', 45 );

/**
 * Add product specifications to single product page.
 */
function aqualuxe_woocommerce_product_specifications() {
	global $product;

	// Get product specifications from product meta or use default.
	$product_specifications = get_post_meta( $product->get_id(), '_product_specifications', true );

	if ( empty( $product_specifications ) ) {
		return;
	}

	echo '<div class="product-specifications">';
	echo '<h3>' . esc_html__( 'Specifications', 'aqualuxe' ) . '</h3>';
	echo '<table class="specifications-table">';
	
	foreach ( $product_specifications as $spec ) {
		echo '<tr>';
		echo '<th>' . esc_html( $spec['label'] ) . '</th>';
		echo '<td>' . esc_html( $spec['value'] ) . '</td>';
		echo '</tr>';
	}
	
	echo '</table>';
	echo '</div>';
}
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_specifications', 15 );

/**
 * Add product care guide to single product page.
 */
function aqualuxe_woocommerce_product_care_guide() {
	global $product;

	// Get product care guide from product meta or use default.
	$product_care_guide = get_post_meta( $product->get_id(), '_product_care_guide', true );

	if ( empty( $product_care_guide ) ) {
		return;
	}

	echo '<div class="product-care-guide">';
	echo '<h3>' . esc_html__( 'Care Guide', 'aqualuxe' ) . '</h3>';
	echo wp_kses_post( wpautop( $product_care_guide ) );
	echo '</div>';
}
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_care_guide', 20 );

/**
 * Add product videos to single product page.
 */
function aqualuxe_woocommerce_product_videos() {
	global $product;

	// Get product videos from product meta or use default.
	$product_videos = get_post_meta( $product->get_id(), '_product_videos', true );

	if ( empty( $product_videos ) ) {
		return;
	}

	echo '<div class="product-videos">';
	echo '<h3>' . esc_html__( 'Product Videos', 'aqualuxe' ) . '</h3>';
	echo '<div class="videos-grid">';
	
	foreach ( $product_videos as $video ) {
		echo '<div class="video-item">';
		echo wp_oembed_get( $video['url'] );
		
		if ( ! empty( $video['title'] ) ) {
			echo '<h4 class="video-title">' . esc_html( $video['title'] ) . '</h4>';
		}
		
		echo '</div>';
	}
	
	echo '</div>';
	echo '</div>';
}
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_videos', 25 );

/**
 * Add product FAQ to single product page.
 */
function aqualuxe_woocommerce_product_faq() {
	global $product;

	// Get product FAQ from product meta or use default.
	$product_faq = get_post_meta( $product->get_id(), '_product_faq', true );

	if ( empty( $product_faq ) ) {
		return;
	}

	echo '<div class="product-faq">';
	echo '<h3>' . esc_html__( 'Frequently Asked Questions', 'aqualuxe' ) . '</h3>';
	echo '<div class="faq-items">';
	
	foreach ( $product_faq as $faq ) {
		echo '<div class="faq-item">';
		echo '<div class="faq-question">';
		echo '<h4>' . esc_html( $faq['question'] ) . '</h4>';
		echo '<span class="faq-toggle"></span>';
		echo '</div>';
		echo '<div class="faq-answer">';
		echo wp_kses_post( wpautop( $faq['answer'] ) );
		echo '</div>';
		echo '</div>';
	}
	
	echo '</div>';
	echo '</div>';
}
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_faq', 30 );

/**
 * Add product reviews summary to single product page.
 */
function aqualuxe_woocommerce_product_reviews_summary() {
	global $product;

	// Check if product has reviews.
	if ( ! $product->get_review_count() ) {
		return;
	}

	// Get review count and average rating.
	$review_count = $product->get_review_count();
	$average_rating = $product->get_average_rating();
	$rating_counts = $product->get_rating_counts();

	echo '<div class="product-reviews-summary">';
	echo '<div class="reviews-average">';
	echo '<div class="average-rating">' . esc_html( $average_rating ) . '</div>';
	echo wc_get_rating_html( $average_rating );
	/* translators: %d: review count */
	echo '<div class="review-count">' . sprintf( _n( '%d review', '%d reviews', $review_count, 'aqualuxe' ), $review_count ) . '</div>';
	echo '</div>';
	
	echo '<div class="reviews-bars">';
	
	for ( $i = 5; $i >= 1; $i-- ) {
		$count = isset( $rating_counts[ $i ] ) ? $rating_counts[ $i ] : 0;
		$percentage = $review_count ? ( $count / $review_count ) * 100 : 0;
		
		echo '<div class="review-bar">';
		echo '<div class="bar-label">' . esc_html( $i ) . ' ' . esc_html__( 'star', 'aqualuxe' ) . '</div>';
		echo '<div class="bar-fill">';
		echo '<div class="bar-fill-inner" style="width: ' . esc_attr( $percentage ) . '%;"></div>';
		echo '</div>';
		echo '<div class="bar-count">' . esc_html( $count ) . '</div>';
		echo '</div>';
	}
	
	echo '</div>';
	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_reviews_summary', 11 );

/**
 * Add product review form toggle to single product page.
 */
function aqualuxe_woocommerce_review_form_toggle() {
	echo '<div class="review-form-toggle">';
	echo '<a href="#review-form" class="btn btn-primary">' . esc_html__( 'Write a Review', 'aqualuxe' ) . '</a>';
	echo '</div>';
}
add_action( 'woocommerce_review_meta', 'aqualuxe_woocommerce_review_form_toggle' );

/**
 * Add product review form anchor to single product page.
 */
function aqualuxe_woocommerce_review_form_anchor() {
	echo '<div id="review-form"></div>';
}
add_action( 'woocommerce_review_before_comment_form', 'aqualuxe_woocommerce_review_form_anchor' );

/**
 * Add product review form title to single product page.
 */
function aqualuxe_woocommerce_review_form_title() {
	echo '<h3 class="review-form-title">' . esc_html__( 'Write a Review', 'aqualuxe' ) . '</h3>';
}
add_action( 'woocommerce_review_before_comment_form', 'aqualuxe_woocommerce_review_form_title', 15 );

/**
 * Add product review form guidelines to single product page.
 */
function aqualuxe_woocommerce_review_form_guidelines() {
	echo '<div class="review-form-guidelines">';
	echo '<p>' . esc_html__( 'Your email address will not be published. Required fields are marked *', 'aqualuxe' ) . '</p>';
	echo '<p>' . esc_html__( 'Please share your experience with this product. What did you like or dislike about it?', 'aqualuxe' ) . '</p>';
	echo '</div>';
}
add_action( 'woocommerce_review_before_comment_form', 'aqualuxe_woocommerce_review_form_guidelines', 20 );

/**
 * Add product review form rating description to single product page.
 */
function aqualuxe_woocommerce_review_form_rating_description() {
	echo '<div class="review-form-rating-description">';
	echo '<p>' . esc_html__( '1 star = Very Dissatisfied, 5 stars = Very Satisfied', 'aqualuxe' ) . '</p>';
	echo '</div>';
}
add_action( 'woocommerce_review_before_comment_form', 'aqualuxe_woocommerce_review_form_rating_description', 25 );

/**
 * Add product review form submit button text to single product page.
 *
 * @param array $comment_form Comment form args.
 * @return array
 */
function aqualuxe_woocommerce_review_form_submit_button( $comment_form ) {
	$comment_form['submit_button'] = '<button name="%1$s" id="%2$s" class="%3$s" type="submit">%4$s</button>';
	$comment_form['submit_field'] = '<div class="form-submit">%1$s %2$s</div>';
	$comment_form['class_submit'] = 'submit btn btn-primary';
	$comment_form['label_submit'] = __( 'Submit Review', 'aqualuxe' );

	return $comment_form;
}
add_filter( 'woocommerce_product_review_comment_form_args', 'aqualuxe_woocommerce_review_form_submit_button' );

/**
 * Add product review form fields to single product page.
 *
 * @param array $comment_form Comment form args.
 * @return array
 */
function aqualuxe_woocommerce_review_form_fields( $comment_form ) {
	$comment_form['comment_field'] = '';

	$comment_form['comment_field'] .= '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your Rating', 'aqualuxe' ) . '<span class="required">*</span></label><select name="rating" id="rating" required>
		<option value="">' . esc_html__( 'Rate&hellip;', 'aqualuxe' ) . '</option>
		<option value="5">' . esc_html__( 'Perfect', 'aqualuxe' ) . '</option>
		<option value="4">' . esc_html__( 'Good', 'aqualuxe' ) . '</option>
		<option value="3">' . esc_html__( 'Average', 'aqualuxe' ) . '</option>
		<option value="2">' . esc_html__( 'Not that bad', 'aqualuxe' ) . '</option>
		<option value="1">' . esc_html__( 'Very poor', 'aqualuxe' ) . '</option>
	</select></div>';

	$comment_form['comment_field'] .= '<div class="comment-form-comment"><label for="comment">' . esc_html__( 'Your Review', 'aqualuxe' ) . '<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></div>';

	return $comment_form;
}
add_filter( 'woocommerce_product_review_comment_form_args', 'aqualuxe_woocommerce_review_form_fields' );

/**
 * Add product review form title to single product page.
 *
 * @param string $title Review title.
 * @return string
 */
function aqualuxe_woocommerce_review_title( $title ) {
	return '<h4 class="woocommerce-review__title">' . esc_html( $title ) . '</h4>';
}
add_filter( 'woocommerce_review_title', 'aqualuxe_woocommerce_review_title' );

/**
 * Add product review form author to single product page.
 *
 * @param string $author Review author.
 * @return string
 */
function aqualuxe_woocommerce_review_author( $author ) {
	return '<span class="woocommerce-review__author">' . esc_html( $author ) . '</span>';
}
add_filter( 'woocommerce_review_author', 'aqualuxe_woocommerce_review_author' );

/**
 * Add product review form date to single product page.
 *
 * @param string $date Review date.
 * @return string
 */
function aqualuxe_woocommerce_review_date( $date ) {
	return '<time class="woocommerce-review__published-date" datetime="' . esc_attr( get_comment_date( 'c' ) ) . '">' . esc_html( $date ) . '</time>';
}
add_filter( 'woocommerce_review_date', 'aqualuxe_woocommerce_review_date' );

/**
 * Add product review form rating to single product page.
 *
 * @param string $rating Review rating.
 * @return string
 */
function aqualuxe_woocommerce_review_rating( $rating ) {
	return '<div class="woocommerce-review__rating">' . $rating . '</div>';
}
add_filter( 'woocommerce_review_rating', 'aqualuxe_woocommerce_review_rating' );

/**
 * Add product review form text to single product page.
 *
 * @param string $text Review text.
 * @return string
 */
function aqualuxe_woocommerce_review_text( $text ) {
	return '<div class="woocommerce-review__text">' . $text . '</div>';
}
add_filter( 'woocommerce_review_text', 'aqualuxe_woocommerce_review_text' );

/**
 * Add product review form comment reply link to single product page.
 *
 * @param string $link Review comment reply link.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_reply_link( $link ) {
	return '<div class="woocommerce-review__reply-link">' . $link . '</div>';
}
add_filter( 'woocommerce_review_comment_reply_link', 'aqualuxe_woocommerce_review_comment_reply_link' );

/**
 * Add product review form comment edit link to single product page.
 *
 * @param string $link Review comment edit link.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_edit_link( $link ) {
	return '<div class="woocommerce-review__edit-link">' . $link . '</div>';
}
add_filter( 'woocommerce_review_comment_edit_link', 'aqualuxe_woocommerce_review_comment_edit_link' );

/**
 * Add product review form comment delete link to single product page.
 *
 * @param string $link Review comment delete link.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_delete_link( $link ) {
	return '<div class="woocommerce-review__delete-link">' . $link . '</div>';
}
add_filter( 'woocommerce_review_comment_delete_link', 'aqualuxe_woocommerce_review_comment_delete_link' );

/**
 * Add product review form comment approve link to single product page.
 *
 * @param string $link Review comment approve link.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_approve_link( $link ) {
	return '<div class="woocommerce-review__approve-link">' . $link . '</div>';
}
add_filter( 'woocommerce_review_comment_approve_link', 'aqualuxe_woocommerce_review_comment_approve_link' );

/**
 * Add product review form comment spam link to single product page.
 *
 * @param string $link Review comment spam link.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_spam_link( $link ) {
	return '<div class="woocommerce-review__spam-link">' . $link . '</div>';
}
add_filter( 'woocommerce_review_comment_spam_link', 'aqualuxe_woocommerce_review_comment_spam_link' );

/**
 * Add product review form comment trash link to single product page.
 *
 * @param string $link Review comment trash link.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_trash_link( $link ) {
	return '<div class="woocommerce-review__trash-link">' . $link . '</div>';
}
add_filter( 'woocommerce_review_comment_trash_link', 'aqualuxe_woocommerce_review_comment_trash_link' );

/**
 * Add product review form comment permalink to single product page.
 *
 * @param string $link Review comment permalink.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_permalink( $link ) {
	return '<div class="woocommerce-review__permalink">' . $link . '</div>';
}
add_filter( 'woocommerce_review_comment_permalink', 'aqualuxe_woocommerce_review_comment_permalink' );

/**
 * Add product review form comment author url to single product page.
 *
 * @param string $url Review comment author url.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_author_url( $url ) {
	return '<div class="woocommerce-review__author-url">' . $url . '</div>';
}
add_filter( 'woocommerce_review_comment_author_url', 'aqualuxe_woocommerce_review_comment_author_url' );

/**
 * Add product review form comment author email to single product page.
 *
 * @param string $email Review comment author email.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_author_email( $email ) {
	return '<div class="woocommerce-review__author-email">' . $email . '</div>';
}
add_filter( 'woocommerce_review_comment_author_email', 'aqualuxe_woocommerce_review_comment_author_email' );

/**
 * Add product review form comment author IP to single product page.
 *
 * @param string $ip Review comment author IP.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_author_IP( $ip ) {
	return '<div class="woocommerce-review__author-ip">' . $ip . '</div>';
}
add_filter( 'woocommerce_review_comment_author_IP', 'aqualuxe_woocommerce_review_comment_author_IP' );

/**
 * Add product review form comment author to single product page.
 *
 * @param string $author Review comment author.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_author( $author ) {
	return '<div class="woocommerce-review__comment-author">' . $author . '</div>';
}
add_filter( 'woocommerce_review_comment_author', 'aqualuxe_woocommerce_review_comment_author' );

/**
 * Add product review form comment date to single product page.
 *
 * @param string $date Review comment date.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_date( $date ) {
	return '<div class="woocommerce-review__comment-date">' . $date . '</div>';
}
add_filter( 'woocommerce_review_comment_date', 'aqualuxe_woocommerce_review_comment_date' );

/**
 * Add product review form comment time to single product page.
 *
 * @param string $time Review comment time.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_time( $time ) {
	return '<div class="woocommerce-review__comment-time">' . $time . '</div>';
}
add_filter( 'woocommerce_review_comment_time', 'aqualuxe_woocommerce_review_comment_time' );

/**
 * Add product review form comment text to single product page.
 *
 * @param string $text Review comment text.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_text( $text ) {
	return '<div class="woocommerce-review__comment-text">' . $text . '</div>';
}
add_filter( 'woocommerce_review_comment_text', 'aqualuxe_woocommerce_review_comment_text' );

/**
 * Add product review form comment rating to single product page.
 *
 * @param string $rating Review comment rating.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_rating( $rating ) {
	return '<div class="woocommerce-review__comment-rating">' . $rating . '</div>';
}
add_filter( 'woocommerce_review_comment_rating', 'aqualuxe_woocommerce_review_comment_rating' );

/**
 * Add product review form comment reply to single product page.
 *
 * @param string $reply Review comment reply.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_reply( $reply ) {
	return '<div class="woocommerce-review__comment-reply">' . $reply . '</div>';
}
add_filter( 'woocommerce_review_comment_reply', 'aqualuxe_woocommerce_review_comment_reply' );

/**
 * Add product review form comment edit to single product page.
 *
 * @param string $edit Review comment edit.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_edit( $edit ) {
	return '<div class="woocommerce-review__comment-edit">' . $edit . '</div>';
}
add_filter( 'woocommerce_review_comment_edit', 'aqualuxe_woocommerce_review_comment_edit' );

/**
 * Add product review form comment delete to single product page.
 *
 * @param string $delete Review comment delete.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_delete( $delete ) {
	return '<div class="woocommerce-review__comment-delete">' . $delete . '</div>';
}
add_filter( 'woocommerce_review_comment_delete', 'aqualuxe_woocommerce_review_comment_delete' );

/**
 * Add product review form comment approve to single product page.
 *
 * @param string $approve Review comment approve.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_approve( $approve ) {
	return '<div class="woocommerce-review__comment-approve">' . $approve . '</div>';
}
add_filter( 'woocommerce_review_comment_approve', 'aqualuxe_woocommerce_review_comment_approve' );

/**
 * Add product review form comment spam to single product page.
 *
 * @param string $spam Review comment spam.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_spam( $spam ) {
	return '<div class="woocommerce-review__comment-spam">' . $spam . '</div>';
}
add_filter( 'woocommerce_review_comment_spam', 'aqualuxe_woocommerce_review_comment_spam' );

/**
 * Add product review form comment trash to single product page.
 *
 * @param string $trash Review comment trash.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_trash( $trash ) {
	return '<div class="woocommerce-review__comment-trash">' . $trash . '</div>';
}
add_filter( 'woocommerce_review_comment_trash', 'aqualuxe_woocommerce_review_comment_trash' );

/**
 * Add product review form comment permalink to single product page.
 *
 * @param string $permalink Review comment permalink.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_permalink( $permalink ) {
	return '<div class="woocommerce-review__comment-permalink">' . $permalink . '</div>';
}
add_filter( 'woocommerce_review_comment_permalink', 'aqualuxe_woocommerce_review_comment_permalink' );

/**
 * Add product review form comment author url to single product page.
 *
 * @param string $author_url Review comment author url.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_author_url( $author_url ) {
	return '<div class="woocommerce-review__comment-author-url">' . $author_url . '</div>';
}
add_filter( 'woocommerce_review_comment_author_url', 'aqualuxe_woocommerce_review_comment_author_url' );

/**
 * Add product review form comment author email to single product page.
 *
 * @param string $author_email Review comment author email.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_author_email( $author_email ) {
	return '<div class="woocommerce-review__comment-author-email">' . $author_email . '</div>';
}
add_filter( 'woocommerce_review_comment_author_email', 'aqualuxe_woocommerce_review_comment_author_email' );

/**
 * Add product review form comment author IP to single product page.
 *
 * @param string $author_ip Review comment author IP.
 * @return string
 */
function aqualuxe_woocommerce_review_comment_author_ip( $author_ip ) {
	return '<div class="woocommerce-review__comment-author-ip">' . $author_ip . '</div>';
}
add_filter( 'woocommerce_review_comment_author_ip', 'aqualuxe_woocommerce_review_comment_author_ip' );