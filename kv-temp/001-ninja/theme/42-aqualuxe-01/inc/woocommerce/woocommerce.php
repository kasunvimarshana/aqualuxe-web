<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
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
	wp_enqueue_style( 'aqualuxe-woocommerce-style', get_template_directory_uri() . '/assets/dist/css/woocommerce.css', array(), AQUALUXE_VERSION );

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
 * @param  array $classes CSS classes applied to the body tag.
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
		'posts_per_page' => 4,
		'columns'        => 4,
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
		<main id="primary" class="site-main py-12">
			<div class="container mx-auto px-4">
				<?php
				// Breadcrumbs
				if ( function_exists( 'yoast_breadcrumb' ) ) {
					yoast_breadcrumb( '<div class="breadcrumbs text-sm text-gray-600 mb-6">', '</div>' );
				} else {
					aqualuxe_breadcrumbs();
				}
				?>

				<div class="content-area flex flex-wrap">
					<?php
					// Get sidebar position
					$sidebar_position = is_product() ? get_theme_mod( 'aqualuxe_product_sidebar', 'none' ) : get_theme_mod( 'aqualuxe_shop_sidebar', 'right' );

					// Left sidebar
					if ( $sidebar_position === 'left' ) {
						?>
						<div class="sidebar w-full lg:w-1/4 lg:pr-8 mb-8 lg:mb-0">
							<?php do_action( 'aqualuxe_before_shop_sidebar' ); ?>
							<?php dynamic_sidebar( 'shop-sidebar' ); ?>
							<?php do_action( 'aqualuxe_after_shop_sidebar' ); ?>
						</div>
						<?php
					}
					?>

					<div class="primary-content w-full <?php echo $sidebar_position !== 'none' ? 'lg:w-3/4' : ''; ?> <?php echo $sidebar_position === 'left' ? 'lg:pl-8' : ( $sidebar_position === 'right' ? 'lg:pr-8' : '' ); ?>">
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
					</div><!-- .primary-content -->

					<?php
					// Get sidebar position
					$sidebar_position = is_product() ? get_theme_mod( 'aqualuxe_product_sidebar', 'none' ) : get_theme_mod( 'aqualuxe_shop_sidebar', 'right' );

					// Right sidebar
					if ( $sidebar_position === 'right' ) {
						?>
						<div class="sidebar w-full lg:w-1/4 lg:pl-8 mt-8 lg:mt-0">
							<?php do_action( 'aqualuxe_before_shop_sidebar' ); ?>
							<?php dynamic_sidebar( 'shop-sidebar' ); ?>
							<?php do_action( 'aqualuxe_after_shop_sidebar' ); ?>
						</div>
						<?php
					}
					?>
				</div><!-- .content-area -->
			</div><!-- .container -->
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

		ob_start();
		aqualuxe_woocommerce_cart_count();
		$fragments['.cart-count'] = ob_get_clean();

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
			<i class="fas fa-shopping-bag text-xl"></i>
			<?php aqualuxe_woocommerce_cart_count(); ?>
		</a>
		<?php
	}
}

if ( ! function_exists( 'aqualuxe_woocommerce_cart_count' ) ) {
	/**
	 * Cart Count.
	 *
	 * Displayed the number of items in the cart.
	 *
	 * @return void
	 */
	function aqualuxe_woocommerce_cart_count() {
		?>
		<span class="cart-count inline-flex items-center justify-center w-5 h-5 text-xs bg-primary-600 text-white rounded-full -mt-2 -ml-1">
			<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
		</span>
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
		<div class="header-cart relative <?php echo esc_attr( $class ); ?>">
			<?php aqualuxe_woocommerce_cart_link(); ?>
			<div class="mini-cart absolute right-0 top-full mt-2 w-80 bg-white shadow-lg rounded-md p-4 hidden z-50">
				<div class="widget_shopping_cart_content">
					<?php woocommerce_mini_cart(); ?>
				</div>
			</div>
		</div>
		<?php
	}
}

/**
 * Customize WooCommerce product columns
 */
function aqualuxe_woocommerce_loop_columns() {
	return get_theme_mod( 'aqualuxe_product_columns', 4 );
}
add_filter( 'loop_shop_columns', 'aqualuxe_woocommerce_loop_columns' );

/**
 * Customize WooCommerce products per page
 */
function aqualuxe_woocommerce_products_per_page() {
	return get_theme_mod( 'aqualuxe_products_per_page', 12 );
}
add_filter( 'loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page' );

/**
 * Change sale flash text
 */
function aqualuxe_woocommerce_sale_flash( $text, $post, $product ) {
	$sale_text = get_theme_mod( 'aqualuxe_sale_badge_text', esc_html__( 'Sale!', 'aqualuxe' ) );
	return '<span class="onsale">' . esc_html( $sale_text ) . '</span>';
}
add_filter( 'woocommerce_sale_flash', 'aqualuxe_woocommerce_sale_flash', 10, 3 );

/**
 * Add sale badge class based on customizer setting
 */
function aqualuxe_woocommerce_sale_badge_class( $classes ) {
	$badge_style = get_theme_mod( 'aqualuxe_sale_badge_style', 'circle' );
	$classes[] = 'sale-badge-' . $badge_style;
	return $classes;
}
add_filter( 'woocommerce_sale_flash_classes', 'aqualuxe_woocommerce_sale_badge_class' );

/**
 * Add quick view button to product loops
 */
function aqualuxe_woocommerce_quick_view_button() {
	if ( get_theme_mod( 'aqualuxe_quick_view', true ) ) {
		global $product;
		echo '<a href="#" class="quick-view-button button" data-product-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__( 'Quick View', 'aqualuxe' ) . '</a>';
	}
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_quick_view_button', 15 );

/**
 * Quick view AJAX handler
 */
function aqualuxe_quick_view_ajax() {
	if ( ! isset( $_POST['product_id'] ) || ! isset( $_POST['nonce'] ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid request', 'aqualuxe' ) ) );
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_nonce' ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Security check failed', 'aqualuxe' ) ) );
	}

	$product_id = absint( $_POST['product_id'] );
	$product = wc_get_product( $product_id );

	if ( ! $product ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Product not found', 'aqualuxe' ) ) );
	}

	ob_start();
	?>
	<div class="quick-view-content">
		<div class="quick-view-images">
			<?php
			$attachment_ids = $product->get_gallery_image_ids();
			$image_id = $product->get_image_id();
			
			if ( $image_id ) {
				echo wp_get_attachment_image( $image_id, 'woocommerce_single', false, array( 'class' => 'quick-view-main-image' ) );
			}
			
			if ( $attachment_ids && $image_id ) {
				echo '<div class="quick-view-thumbnails">';
				foreach ( $attachment_ids as $attachment_id ) {
					echo wp_get_attachment_image( $attachment_id, 'woocommerce_thumbnail', false, array( 'class' => 'quick-view-thumbnail' ) );
				}
				echo '</div>';
			}
			?>
		</div>
		<div class="quick-view-summary">
			<h2 class="product_title entry-title"><?php echo esc_html( $product->get_name() ); ?></h2>
			
			<div class="woocommerce-product-rating">
				<?php if ( $product->get_average_rating() ) : ?>
					<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
					<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>#reviews" class="woocommerce-review-link" rel="nofollow">
						(<?php printf( _n( '%s customer review', '%s customer reviews', $product->get_review_count(), 'aqualuxe' ), esc_html( $product->get_review_count() ) ); ?>)
					</a>
				<?php endif; ?>
			</div>
			
			<div class="price"><?php echo $product->get_price_html(); ?></div>
			
			<div class="woocommerce-product-details__short-description">
				<?php echo apply_filters( 'woocommerce_short_description', $product->get_short_description() ); ?>
			</div>
			
			<?php if ( $product->is_in_stock() ) : ?>
				<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
					<?php if ( $product->is_type( 'variable' ) ) : ?>
						<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="button view-product-details">
							<?php esc_html_e( 'View Product Details', 'aqualuxe' ); ?>
						</a>
					<?php else : ?>
						<?php
						woocommerce_quantity_input(
							array(
								'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
								'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
								'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(),
							)
						);
						?>
						<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt">
							<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
						</button>
					<?php endif; ?>
				</form>
			<?php else : ?>
				<div class="stock out-of-stock">
					<?php esc_html_e( 'Out of stock', 'aqualuxe' ); ?>
				</div>
				<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="button view-product-details">
					<?php esc_html_e( 'View Product Details', 'aqualuxe' ); ?>
				</a>
			<?php endif; ?>
			
			<div class="product_meta">
				<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
					<span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'aqualuxe' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'aqualuxe' ); ?></span></span>
				<?php endif; ?>
				
				<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'aqualuxe' ) . ' ', '</span>' ); ?>
				
				<?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'aqualuxe' ) . ' ', '</span>' ); ?>
			</div>
		</div>
	</div>
	<?php
	$html = ob_get_clean();

	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax' );

/**
 * Add quick view modal to footer
 */
function aqualuxe_quick_view_modal() {
	if ( get_theme_mod( 'aqualuxe_quick_view', true ) ) {
		?>
		<div id="quick-view-modal" class="modal hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
			<div class="modal-container bg-white w-full max-w-4xl mx-auto rounded-lg shadow-lg overflow-hidden">
				<div class="modal-header flex justify-between items-center p-4 border-b">
					<h3 class="modal-title text-xl font-bold"><?php esc_html_e( 'Quick View', 'aqualuxe' ); ?></h3>
					<button class="modal-close text-gray-500 hover:text-gray-700 focus:outline-none">
						<i class="fas fa-times text-xl"></i>
					</button>
				</div>
				<div class="modal-body p-6">
					<div class="modal-loader flex items-center justify-center py-12">
						<div class="spinner-border text-primary" role="status">
							<span class="sr-only"><?php esc_html_e( 'Loading...', 'aqualuxe' ); ?></span>
						</div>
					</div>
					<div class="modal-content hidden"></div>
				</div>
			</div>
		</div>
		<?php
	}
}
add_action( 'wp_footer', 'aqualuxe_quick_view_modal' );

/**
 * Add sticky add to cart
 */
function aqualuxe_sticky_add_to_cart() {
	if ( ! is_product() || ! get_theme_mod( 'aqualuxe_sticky_add_to_cart', true ) ) {
		return;
	}

	global $product;
	?>
	<div class="sticky-add-to-cart fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 transform translate-y-full transition-transform duration-300 z-40">
		<div class="container mx-auto">
			<div class="flex flex-wrap items-center justify-between">
				<div class="sticky-add-to-cart__content flex items-center">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="sticky-add-to-cart__thumbnail mr-4">
							<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-12 h-12 object-cover rounded' ) ); ?>
						</div>
					<?php endif; ?>
					<div>
						<h4 class="sticky-add-to-cart__title text-lg font-bold"><?php the_title(); ?></h4>
						<span class="sticky-add-to-cart__price"><?php echo $product->get_price_html(); ?></span>
					</div>
				</div>
				<div class="sticky-add-to-cart__action">
					<?php if ( $product->is_type( 'variable' ) ) : ?>
						<a href="#product-details" class="button select-options">
							<?php esc_html_e( 'Select Options', 'aqualuxe' ); ?>
						</a>
					<?php elseif ( $product->is_in_stock() ) : ?>
						<form class="cart" method="post" enctype='multipart/form-data'>
							<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" />
							<button type="submit" class="single_add_to_cart_button button alt">
								<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
							</button>
						</form>
					<?php else : ?>
						<div class="stock out-of-stock">
							<?php esc_html_e( 'Out of stock', 'aqualuxe' ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'aqualuxe_sticky_add_to_cart' );

/**
 * Customize WooCommerce checkout fields
 */
function aqualuxe_woocommerce_checkout_fields( $fields ) {
	// Add placeholder and class to billing fields
	foreach ( $fields['billing'] as $key => $field ) {
		$fields['billing'][$key]['placeholder'] = $field['label'];
		$fields['billing'][$key]['class'][] = 'form-row-wide';
	}

	// Add placeholder and class to shipping fields
	foreach ( $fields['shipping'] as $key => $field ) {
		$fields['shipping'][$key]['placeholder'] = $field['label'];
		$fields['shipping'][$key]['class'][] = 'form-row-wide';
	}

	// Add placeholder and class to order fields
	foreach ( $fields['order'] as $key => $field ) {
		$fields['order'][$key]['placeholder'] = $field['label'];
		$fields['order'][$key]['class'][] = 'form-row-wide';
	}

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'aqualuxe_woocommerce_checkout_fields' );

/**
 * Add quantity buttons to quantity inputs
 */
function aqualuxe_woocommerce_quantity_input_template( $html, $args ) {
	$buttons = '<div class="quantity-buttons">';
	$buttons .= '<button type="button" class="quantity-button minus">-</button>';
	$buttons .= '<button type="button" class="quantity-button plus">+</button>';
	$buttons .= '</div>';

	return str_replace( '</div>', $buttons . '</div>', $html );
}
add_filter( 'woocommerce_quantity_input_html', 'aqualuxe_woocommerce_quantity_input_template', 10, 2 );

/**
 * Add wishlist button to product loops
 */
function aqualuxe_woocommerce_wishlist_button() {
	if ( function_exists( 'YITH_WCWL' ) ) {
		echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
	}
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_wishlist_button', 20 );

/**
 * Add compare button to product loops
 */
function aqualuxe_woocommerce_compare_button() {
	if ( function_exists( 'yith_woocompare_constructor' ) ) {
		echo do_shortcode( '[yith_compare_button]' );
	}
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_compare_button', 25 );

/**
 * Add product categories to product loops
 */
function aqualuxe_woocommerce_product_categories() {
	global $product;
	echo '<div class="product-categories">' . wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">', '</span>' ) . '</div>';
}
add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_product_categories', 5 );

/**
 * Add product rating to product loops
 */
function aqualuxe_woocommerce_product_rating() {
	global $product;
	if ( $product->get_average_rating() > 0 ) {
		echo wc_get_rating_html( $product->get_average_rating() );
	} else {
		echo '<div class="star-rating"></div>';
	}
}
add_action( 'woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_product_rating', 5 );

/**
 * Add product stock status to product loops
 */
function aqualuxe_woocommerce_product_stock_status() {
	global $product;
	if ( ! $product->is_in_stock() ) {
		echo '<div class="product-stock-status out-of-stock">' . esc_html__( 'Out of stock', 'aqualuxe' ) . '</div>';
	} elseif ( $product->is_on_backorder() ) {
		echo '<div class="product-stock-status on-backorder">' . esc_html__( 'On backorder', 'aqualuxe' ) . '</div>';
	} elseif ( $product->get_stock_quantity() && $product->get_stock_quantity() <= get_option( 'woocommerce_notify_low_stock_amount' ) ) {
		echo '<div class="product-stock-status low-stock">' . esc_html__( 'Low stock', 'aqualuxe' ) . '</div>';
	}
}
add_action( 'woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_product_stock_status', 10 );

/**
 * Add product countdown to product loops
 */
function aqualuxe_woocommerce_product_countdown() {
	global $product;
	if ( $product->is_on_sale() ) {
		$sale_price_dates_to = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );
		if ( $sale_price_dates_to ) {
			$now = time();
			if ( $sale_price_dates_to > $now ) {
				$diff = $sale_price_dates_to - $now;
				$days = floor( $diff / ( 60 * 60 * 24 ) );
				$hours = floor( ( $diff - $days * 60 * 60 * 24 ) / ( 60 * 60 ) );
				$minutes = floor( ( $diff - $days * 60 * 60 * 24 - $hours * 60 * 60 ) / 60 );
				$seconds = $diff - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60;
				?>
				<div class="product-countdown" data-time="<?php echo esc_attr( $sale_price_dates_to ); ?>">
					<div class="countdown-text"><?php esc_html_e( 'Sale ends in:', 'aqualuxe' ); ?></div>
					<div class="countdown-timer">
						<div class="countdown-item">
							<span class="countdown-value days"><?php echo esc_html( $days ); ?></span>
							<span class="countdown-label"><?php esc_html_e( 'Days', 'aqualuxe' ); ?></span>
						</div>
						<div class="countdown-item">
							<span class="countdown-value hours"><?php echo esc_html( $hours ); ?></span>
							<span class="countdown-label"><?php esc_html_e( 'Hours', 'aqualuxe' ); ?></span>
						</div>
						<div class="countdown-item">
							<span class="countdown-value minutes"><?php echo esc_html( $minutes ); ?></span>
							<span class="countdown-label"><?php esc_html_e( 'Minutes', 'aqualuxe' ); ?></span>
						</div>
						<div class="countdown-item">
							<span class="countdown-value seconds"><?php echo esc_html( $seconds ); ?></span>
							<span class="countdown-label"><?php esc_html_e( 'Seconds', 'aqualuxe' ); ?></span>
						</div>
					</div>
				</div>
				<?php
			}
		}
	}
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_product_countdown', 30 );

/**
 * Add product badges to product loops
 */
function aqualuxe_woocommerce_product_badges() {
	global $product;
	echo '<div class="product-badges">';
	
	// Sale badge is already added by WooCommerce
	
	// New badge
	$newness_days = 30;
	$created = strtotime( $product->get_date_created() );
	if ( ( time() - ( 60 * 60 * 24 * $newness_days ) ) < $created ) {
		echo '<span class="badge badge-new">' . esc_html__( 'New', 'aqualuxe' ) . '</span>';
	}
	
	// Featured badge
	if ( $product->is_featured() ) {
		echo '<span class="badge badge-featured">' . esc_html__( 'Featured', 'aqualuxe' ) . '</span>';
	}
	
	// Hot badge
	if ( $product->get_total_sales() > 10 ) {
		echo '<span class="badge badge-hot">' . esc_html__( 'Hot', 'aqualuxe' ) . '</span>';
	}
	
	echo '</div>';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_product_badges', 10 );

/**
 * Add social sharing buttons to single product
 */
function aqualuxe_woocommerce_share() {
	global $product;
	
	$share_url = urlencode( get_permalink() );
	$share_title = urlencode( get_the_title() );
	$share_image = wp_get_attachment_url( get_post_thumbnail_id( $product->get_id() ) );
	
	if ( $share_image ) {
		$share_image = urlencode( $share_image );
	}
	
	?>
	<div class="product-share mt-6 pt-6 border-t border-gray-200">
		<h4 class="share-title text-lg font-bold mb-3"><?php esc_html_e( 'Share This Product', 'aqualuxe' ); ?></h4>
		<div class="share-buttons flex space-x-2">
			<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer" class="share-button share-facebook bg-blue-600 hover:bg-blue-700 text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors">
				<i class="fab fa-facebook-f"></i>
			</a>
			<a href="https://twitter.com/intent/tweet?text=<?php echo $share_title; ?>&url=<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer" class="share-button share-twitter bg-blue-400 hover:bg-blue-500 text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors">
				<i class="fab fa-twitter"></i>
			</a>
			<a href="https://pinterest.com/pin/create/button/?url=<?php echo $share_url; ?>&media=<?php echo $share_image; ?>&description=<?php echo $share_title; ?>" target="_blank" rel="noopener noreferrer" class="share-button share-pinterest bg-red-600 hover:bg-red-700 text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors">
				<i class="fab fa-pinterest-p"></i>
			</a>
			<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $share_url; ?>&title=<?php echo $share_title; ?>" target="_blank" rel="noopener noreferrer" class="share-button share-linkedin bg-blue-800 hover:bg-blue-900 text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors">
				<i class="fab fa-linkedin-in"></i>
			</a>
			<a href="mailto:?subject=<?php echo $share_title; ?>&body=<?php echo $share_url; ?>" class="share-button share-email bg-gray-600 hover:bg-gray-700 text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors">
				<i class="fas fa-envelope"></i>
			</a>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_share', 'aqualuxe_woocommerce_share' );

/**
 * Add product meta info to single product
 */
function aqualuxe_woocommerce_product_meta_info() {
	global $product;
	?>
	<div class="product-meta-info mt-6 pt-6 border-t border-gray-200">
		<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
			<?php if ( $product->get_sku() ) : ?>
				<div class="product-meta-item">
					<div class="meta-icon text-primary-600 mb-2">
						<i class="fas fa-barcode text-2xl"></i>
					</div>
					<div class="meta-title font-bold"><?php esc_html_e( 'SKU', 'aqualuxe' ); ?></div>
					<div class="meta-value"><?php echo esc_html( $product->get_sku() ); ?></div>
				</div>
			<?php endif; ?>
			
			<?php if ( $product->get_weight() ) : ?>
				<div class="product-meta-item">
					<div class="meta-icon text-primary-600 mb-2">
						<i class="fas fa-weight text-2xl"></i>
					</div>
					<div class="meta-title font-bold"><?php esc_html_e( 'Weight', 'aqualuxe' ); ?></div>
					<div class="meta-value"><?php echo esc_html( $product->get_weight() ); ?> <?php echo esc_html( get_option( 'woocommerce_weight_unit' ) ); ?></div>
				</div>
			<?php endif; ?>
			
			<?php if ( $product->get_dimensions() !== '' ) : ?>
				<div class="product-meta-item">
					<div class="meta-icon text-primary-600 mb-2">
						<i class="fas fa-ruler-combined text-2xl"></i>
					</div>
					<div class="meta-title font-bold"><?php esc_html_e( 'Dimensions', 'aqualuxe' ); ?></div>
					<div class="meta-value"><?php echo esc_html( wc_format_dimensions( $product->get_dimensions( false ) ) ); ?></div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_meta_info', 40 );

/**
 * Add size guide to single product
 */
function aqualuxe_woocommerce_size_guide() {
	global $product;
	
	// Only show for products with attributes
	if ( ! $product->has_attributes() ) {
		return;
	}
	
	?>
	<div class="size-guide-wrapper mt-4">
		<button type="button" class="size-guide-button text-primary-600 hover:text-primary-800 transition-colors flex items-center" data-modal="#size-guide-modal">
			<i class="fas fa-ruler-horizontal mr-2"></i>
			<?php esc_html_e( 'Size Guide', 'aqualuxe' ); ?>
		</button>
	</div>
	
	<div id="size-guide-modal" class="modal hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
		<div class="modal-container bg-white w-full max-w-4xl mx-auto rounded-lg shadow-lg overflow-hidden">
			<div class="modal-header flex justify-between items-center p-4 border-b">
				<h3 class="modal-title text-xl font-bold"><?php esc_html_e( 'Size Guide', 'aqualuxe' ); ?></h3>
				<button class="modal-close text-gray-500 hover:text-gray-700 focus:outline-none">
					<i class="fas fa-times text-xl"></i>
				</button>
			</div>
			<div class="modal-body p-6">
				<div class="size-guide-content">
					<?php
					// Get product attributes
					$attributes = $product->get_attributes();
					
					if ( ! empty( $attributes ) ) {
						echo '<div class="size-guide-table-wrapper overflow-x-auto">';
						echo '<table class="size-guide-table w-full border-collapse">';
						
						// Table header
						echo '<thead>';
						echo '<tr>';
						echo '<th class="border p-2 bg-gray-100">' . esc_html__( 'Size', 'aqualuxe' ) . '</th>';
						
						// Add attribute names as column headers
						foreach ( $attributes as $attribute ) {
							if ( $attribute->get_visible() ) {
								echo '<th class="border p-2 bg-gray-100">' . esc_html( wc_attribute_label( $attribute->get_name() ) ) . '</th>';
							}
						}
						
						echo '</tr>';
						echo '</thead>';
						
						// Table body
						echo '<tbody>';
						
						// Get the first attribute values to use as row headers
						$first_attribute = reset( $attributes );
						$first_attribute_values = array();
						
						if ( $first_attribute && $first_attribute->is_taxonomy() ) {
							$terms = wc_get_product_terms( $product->get_id(), $first_attribute->get_name(), array( 'fields' => 'all' ) );
							foreach ( $terms as $term ) {
								$first_attribute_values[] = $term->name;
							}
						} else if ( $first_attribute ) {
							$first_attribute_values = $first_attribute->get_options();
						}
						
						// Create rows
						foreach ( $first_attribute_values as $value ) {
							echo '<tr>';
							echo '<td class="border p-2 font-bold">' . esc_html( $value ) . '</td>';
							
							// Add placeholder cells for other attributes
							foreach ( $attributes as $attribute ) {
								if ( $attribute->get_visible() && $attribute !== $first_attribute ) {
									echo '<td class="border p-2">-</td>';
								}
							}
							
							echo '</tr>';
						}
						
						echo '</tbody>';
						echo '</table>';
						echo '</div>';
						
						// Add size guide notes
						echo '<div class="size-guide-notes mt-6">';
						echo '<h4 class="text-lg font-bold mb-2">' . esc_html__( 'Notes', 'aqualuxe' ) . '</h4>';
						echo '<ul class="list-disc pl-6 space-y-2">';
						echo '<li>' . esc_html__( 'All measurements are in centimeters.', 'aqualuxe' ) . '</li>';
						echo '<li>' . esc_html__( 'Measurements may vary by up to 2cm due to the manufacturing process.', 'aqualuxe' ) . '</li>';
						echo '<li>' . esc_html__( 'If you are between sizes, we recommend choosing the larger size.', 'aqualuxe' ) . '</li>';
						echo '</ul>';
						echo '</div>';
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_before_add_to_cart_button', 'aqualuxe_woocommerce_size_guide', 10 );

/**
 * Add delivery info to single product
 */
function aqualuxe_woocommerce_delivery_info() {
	?>
	<div class="delivery-info mt-6 pt-6 border-t border-gray-200">
		<h4 class="text-lg font-bold mb-3"><?php esc_html_e( 'Shipping & Returns', 'aqualuxe' ); ?></h4>
		<ul class="space-y-2">
			<li class="flex items-start">
				<i class="fas fa-truck text-primary-600 mt-1 mr-2"></i>
				<span><?php esc_html_e( 'Free shipping on orders over $50', 'aqualuxe' ); ?></span>
			</li>
			<li class="flex items-start">
				<i class="fas fa-box text-primary-600 mt-1 mr-2"></i>
				<span><?php esc_html_e( 'Free returns within 30 days', 'aqualuxe' ); ?></span>
			</li>
			<li class="flex items-start">
				<i class="fas fa-shield-alt text-primary-600 mt-1 mr-2"></i>
				<span><?php esc_html_e( '2-year warranty on all products', 'aqualuxe' ); ?></span>
			</li>
		</ul>
	</div>
	<?php
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_delivery_info', 45 );

/**
 * Add trust badges to single product
 */
function aqualuxe_woocommerce_trust_badges() {
	?>
	<div class="trust-badges mt-6 pt-6 border-t border-gray-200">
		<div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
			<div class="trust-badge">
				<div class="badge-icon text-primary-600 mb-2">
					<i class="fas fa-lock text-2xl"></i>
				</div>
				<div class="badge-text text-sm"><?php esc_html_e( 'Secure Payment', 'aqualuxe' ); ?></div>
			</div>
			<div class="trust-badge">
				<div class="badge-icon text-primary-600 mb-2">
					<i class="fas fa-shipping-fast text-2xl"></i>
				</div>
				<div class="badge-text text-sm"><?php esc_html_e( 'Fast Delivery', 'aqualuxe' ); ?></div>
			</div>
			<div class="trust-badge">
				<div class="badge-icon text-primary-600 mb-2">
					<i class="fas fa-exchange-alt text-2xl"></i>
				</div>
				<div class="badge-text text-sm"><?php esc_html_e( 'Easy Returns', 'aqualuxe' ); ?></div>
			</div>
			<div class="trust-badge">
				<div class="badge-icon text-primary-600 mb-2">
					<i class="fas fa-headset text-2xl"></i>
				</div>
				<div class="badge-text text-sm"><?php esc_html_e( '24/7 Support', 'aqualuxe' ); ?></div>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_trust_badges', 50 );

/**
 * Add recently viewed products
 */
function aqualuxe_woocommerce_recently_viewed_products() {
	if ( ! is_product() ) {
		return;
	}

	// Get current product ID
	$current_product_id = get_the_ID();
	
	// Get cookie
	$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) : array();
	$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
	
	// Remove current product
	$viewed_products = array_diff( $viewed_products, array( $current_product_id ) );
	
	if ( empty( $viewed_products ) ) {
		return;
	}
	
	// Show max 4 products
	$viewed_products = array_slice( $viewed_products, 0, 4 );
	
	$args = array(
		'posts_per_page' => 4,
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'post__in'       => $viewed_products,
		'orderby'        => 'post__in',
	);
	
	$products = new WP_Query( $args );
	
	if ( $products->have_posts() ) {
		?>
		<section class="recently-viewed-products py-12 border-t border-gray-200">
			<div class="container mx-auto px-4">
				<h2 class="section-title text-2xl font-bold text-primary-800 mb-6"><?php esc_html_e( 'Recently Viewed Products', 'aqualuxe' ); ?></h2>
				
				<div class="products grid grid-cols-2 md:grid-cols-4 gap-6">
					<?php
					while ( $products->have_posts() ) {
						$products->the_post();
						wc_get_template_part( 'content', 'product' );
					}
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
		<?php
	}
}
add_action( 'woocommerce_after_single_product', 'aqualuxe_woocommerce_recently_viewed_products', 20 );

/**
 * Track recently viewed products
 */
function aqualuxe_track_product_view() {
	if ( ! is_singular( 'product' ) ) {
		return;
	}

	global $post;

	if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) {
		$viewed_products = array();
	} else {
		$viewed_products = (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) );
	}

	// Remove current product from the array to prevent duplicates
	$viewed_products = array_diff( $viewed_products, array( $post->ID ) );

	// Add current product to the start of the array
	array_unshift( $viewed_products, $post->ID );

	// Limit to 15 products
	if ( count( $viewed_products ) > 15 ) {
		$viewed_products = array_slice( $viewed_products, 0, 15 );
	}

	// Store in cookie
	wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
}
add_action( 'template_redirect', 'aqualuxe_track_product_view', 20 );

/**
 * Add product brand to single product
 */
function aqualuxe_woocommerce_product_brand() {
	global $product;
	
	// Check if product brand taxonomy exists
	if ( taxonomy_exists( 'product_brand' ) ) {
		$brands = get_the_terms( $product->get_id(), 'product_brand' );
		
		if ( $brands && ! is_wp_error( $brands ) ) {
			echo '<div class="product-brand mb-2">';
			
			$brand_links = array();
			foreach ( $brands as $brand ) {
				$brand_links[] = '<a href="' . esc_url( get_term_link( $brand ) ) . '" class="text-primary-600 hover:text-primary-800 transition-colors">' . esc_html( $brand->name ) . '</a>';
			}
			
			echo esc_html__( 'Brand:', 'aqualuxe' ) . ' ' . implode( ', ', $brand_links );
			echo '</div>';
		}
	}
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_brand', 6 );

/**
 * Add estimated delivery date to single product
 */
function aqualuxe_woocommerce_estimated_delivery() {
	global $product;
	
	if ( $product->is_in_stock() ) {
		$min_days = 3;
		$max_days = 7;
		
		$min_date = date_i18n( get_option( 'date_format' ), strtotime( "+{$min_days} days" ) );
		$max_date = date_i18n( get_option( 'date_format' ), strtotime( "+{$max_days} days" ) );
		
		echo '<div class="estimated-delivery mt-4 text-sm">';
		echo '<i class="fas fa-calendar-alt text-primary-600 mr-1"></i> ';
		printf(
			/* translators: %1$s: minimum delivery date, %2$s: maximum delivery date */
			esc_html__( 'Estimated delivery: %1$s - %2$s', 'aqualuxe' ),
			'<strong>' . $min_date . '</strong>',
			'<strong>' . $max_date . '</strong>'
		);
		echo '</div>';
	}
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_estimated_delivery', 30 );

/**
 * Add product features to single product tabs
 */
function aqualuxe_woocommerce_product_features_tab( $tabs ) {
	global $product;
	
	// Add features tab if product has features
	$features = get_post_meta( $product->get_id(), '_product_features', true );
	
	if ( $features ) {
		$tabs['features'] = array(
			'title'    => esc_html__( 'Features', 'aqualuxe' ),
			'priority' => 15,
			'callback' => 'aqualuxe_woocommerce_product_features_tab_content',
		);
	}
	
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'aqualuxe_woocommerce_product_features_tab' );

/**
 * Product features tab content
 */
function aqualuxe_woocommerce_product_features_tab_content() {
	global $product;
	
	$features = get_post_meta( $product->get_id(), '_product_features', true );
	
	if ( $features ) {
		echo '<div class="product-features">';
		echo wp_kses_post( wpautop( $features ) );
		echo '</div>';
	}
}

/**
 * Add custom product data tabs
 */
function aqualuxe_woocommerce_custom_product_data_tabs( $tabs ) {
	global $product;
	
	// Specifications tab
	$specifications = get_post_meta( $product->get_id(), '_product_specifications', true );
	
	if ( $specifications ) {
		$tabs['specifications'] = array(
			'title'    => esc_html__( 'Specifications', 'aqualuxe' ),
			'priority' => 20,
			'callback' => 'aqualuxe_woocommerce_product_specifications_tab_content',
		);
	}
	
	// Shipping tab
	$tabs['shipping'] = array(
		'title'    => esc_html__( 'Shipping & Returns', 'aqualuxe' ),
		'priority' => 30,
		'callback' => 'aqualuxe_woocommerce_product_shipping_tab_content',
	);
	
	// FAQ tab
	$faq = get_post_meta( $product->get_id(), '_product_faq', true );
	
	if ( $faq ) {
		$tabs['faq'] = array(
			'title'    => esc_html__( 'FAQ', 'aqualuxe' ),
			'priority' => 40,
			'callback' => 'aqualuxe_woocommerce_product_faq_tab_content',
		);
	}
	
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'aqualuxe_woocommerce_custom_product_data_tabs' );

/**
 * Product specifications tab content
 */
function aqualuxe_woocommerce_product_specifications_tab_content() {
	global $product;
	
	$specifications = get_post_meta( $product->get_id(), '_product_specifications', true );
	
	if ( $specifications ) {
		echo '<div class="product-specifications">';
		echo wp_kses_post( wpautop( $specifications ) );
		echo '</div>';
	}
}

/**
 * Product shipping tab content
 */
function aqualuxe_woocommerce_product_shipping_tab_content() {
	global $product;
	
	$shipping_info = get_post_meta( $product->get_id(), '_product_shipping_info', true );
	
	if ( $shipping_info ) {
		echo '<div class="product-shipping-info">';
		echo wp_kses_post( wpautop( $shipping_info ) );
		echo '</div>';
	} else {
		?>
		<div class="product-shipping-info">
			<h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Shipping Information', 'aqualuxe' ); ?></h3>
			<p><?php esc_html_e( 'We offer the following shipping options:', 'aqualuxe' ); ?></p>
			
			<table class="shipping-table w-full border-collapse mt-4 mb-6">
				<thead>
					<tr>
						<th class="border p-2 bg-gray-100 text-left"><?php esc_html_e( 'Shipping Method', 'aqualuxe' ); ?></th>
						<th class="border p-2 bg-gray-100 text-left"><?php esc_html_e( 'Delivery Time', 'aqualuxe' ); ?></th>
						<th class="border p-2 bg-gray-100 text-left"><?php esc_html_e( 'Cost', 'aqualuxe' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="border p-2"><?php esc_html_e( 'Standard Shipping', 'aqualuxe' ); ?></td>
						<td class="border p-2"><?php esc_html_e( '3-7 business days', 'aqualuxe' ); ?></td>
						<td class="border p-2"><?php esc_html_e( '$5.99 (Free on orders over $50)', 'aqualuxe' ); ?></td>
					</tr>
					<tr>
						<td class="border p-2"><?php esc_html_e( 'Express Shipping', 'aqualuxe' ); ?></td>
						<td class="border p-2"><?php esc_html_e( '1-3 business days', 'aqualuxe' ); ?></td>
						<td class="border p-2"><?php esc_html_e( '$12.99', 'aqualuxe' ); ?></td>
					</tr>
					<tr>
						<td class="border p-2"><?php esc_html_e( 'Next Day Delivery', 'aqualuxe' ); ?></td>
						<td class="border p-2"><?php esc_html_e( 'Next business day', 'aqualuxe' ); ?></td>
						<td class="border p-2"><?php esc_html_e( '$19.99', 'aqualuxe' ); ?></td>
					</tr>
				</tbody>
			</table>
			
			<h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Return Policy', 'aqualuxe' ); ?></h3>
			<p><?php esc_html_e( 'We offer a 30-day return policy for all products. To be eligible for a return, your item must be unused and in the same condition that you received it. It must also be in the original packaging.', 'aqualuxe' ); ?></p>
			
			<p class="mt-4"><?php esc_html_e( 'To initiate a return, please contact our customer service team at returns@aqualuxe.com with your order number and reason for return.', 'aqualuxe' ); ?></p>
			
			<h4 class="text-lg font-bold mt-6 mb-2"><?php esc_html_e( 'Refunds', 'aqualuxe' ); ?></h4>
			<p><?php esc_html_e( 'Once your return is received and inspected, we will send you an email to notify you that we have received your returned item. We will also notify you of the approval or rejection of your refund.', 'aqualuxe' ); ?></p>
			
			<p class="mt-4"><?php esc_html_e( 'If you are approved, then your refund will be processed, and a credit will automatically be applied to your credit card or original method of payment, within 5-7 business days.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}
}

/**
 * Product FAQ tab content
 */
function aqualuxe_woocommerce_product_faq_tab_content() {
	global $product;
	
	$faq = get_post_meta( $product->get_id(), '_product_faq', true );
	
	if ( $faq ) {
		echo '<div class="product-faq">';
		echo wp_kses_post( wpautop( $faq ) );
		echo '</div>';
	}
}

/**
 * Add product inquiry form to single product
 */
function aqualuxe_woocommerce_product_inquiry_form() {
	global $product;
	?>
	<div class="product-inquiry mt-8 pt-8 border-t border-gray-200">
		<h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Product Inquiry', 'aqualuxe' ); ?></h3>
		
		<form id="product-inquiry-form" class="inquiry-form">
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $product->get_id() ); ?>">
			<input type="hidden" name="product_name" value="<?php echo esc_attr( $product->get_name() ); ?>">
			
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
				<div class="form-group">
					<label for="inquiry-name" class="block text-gray-700 font-medium mb-2"><?php esc_html_e( 'Name', 'aqualuxe' ); ?> <span class="required">*</span></label>
					<input type="text" id="inquiry-name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" required>
				</div>
				
				<div class="form-group">
					<label for="inquiry-email" class="block text-gray-700 font-medium mb-2"><?php esc_html_e( 'Email', 'aqualuxe' ); ?> <span class="required">*</span></label>
					<input type="email" id="inquiry-email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" required>
				</div>
			</div>
			
			<div class="form-group mb-4">
				<label for="inquiry-subject" class="block text-gray-700 font-medium mb-2"><?php esc_html_e( 'Subject', 'aqualuxe' ); ?> <span class="required">*</span></label>
				<input type="text" id="inquiry-subject" name="subject" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" value="<?php echo esc_attr( sprintf( esc_html__( 'Inquiry about %s', 'aqualuxe' ), $product->get_name() ) ); ?>" required>
			</div>
			
			<div class="form-group mb-4">
				<label for="inquiry-message" class="block text-gray-700 font-medium mb-2"><?php esc_html_e( 'Message', 'aqualuxe' ); ?> <span class="required">*</span></label>
				<textarea id="inquiry-message" name="message" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" required></textarea>
			</div>
			
			<div class="form-group mb-4">
				<div class="flex items-center">
					<input type="checkbox" id="inquiry-terms" name="terms" class="mr-2" required>
					<label for="inquiry-terms" class="text-gray-700">
						<?php
						printf(
							/* translators: %1$s: terms URL, %2$s: privacy policy URL */
							esc_html__( 'I have read and agree to the %1$s and %2$s', 'aqualuxe' ),
							'<a href="' . esc_url( get_privacy_policy_url() ) . '" class="text-primary-600 hover:text-primary-800 transition-colors">' . esc_html__( 'Terms & Conditions', 'aqualuxe' ) . '</a>',
							'<a href="' . esc_url( get_privacy_policy_url() ) . '" class="text-primary-600 hover:text-primary-800 transition-colors">' . esc_html__( 'Privacy Policy', 'aqualuxe' ) . '</a>'
						);
						?>
					</label>
				</div>
			</div>
			
			<div class="form-response mb-4"></div>
			
			<button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-6 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-primary-300 focus:ring-offset-2">
				<?php esc_html_e( 'Send Inquiry', 'aqualuxe' ); ?>
			</button>
		</form>
	</div>
	<?php
}
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_inquiry_form', 15 );

/**
 * Product inquiry AJAX handler
 */
function aqualuxe_product_inquiry_ajax() {
	if ( ! isset( $_POST['name'] ) || ! isset( $_POST['email'] ) || ! isset( $_POST['message'] ) || ! isset( $_POST['product_id'] ) || ! isset( $_POST['nonce'] ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid request', 'aqualuxe' ) ) );
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_nonce' ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Security check failed', 'aqualuxe' ) ) );
	}

	$name = sanitize_text_field( wp_unslash( $_POST['name'] ) );
	$email = sanitize_email( wp_unslash( $_POST['email'] ) );
	$subject = sanitize_text_field( wp_unslash( $_POST['subject'] ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ) );
	$product_id = absint( $_POST['product_id'] );
	$product_name = sanitize_text_field( wp_unslash( $_POST['product_name'] ) );

	// Validate email
	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please enter a valid email address', 'aqualuxe' ) ) );
	}

	// Get admin email
	$admin_email = get_option( 'admin_email' );

	// Email subject
	$email_subject = sprintf( esc_html__( 'Product Inquiry: %s', 'aqualuxe' ), $product_name );

	// Email message
	$email_message = sprintf( esc_html__( 'Name: %s', 'aqualuxe' ), $name ) . "\r\n\r\n";
	$email_message .= sprintf( esc_html__( 'Email: %s', 'aqualuxe' ), $email ) . "\r\n\r\n";
	$email_message .= sprintf( esc_html__( 'Product: %s (ID: %d)', 'aqualuxe' ), $product_name, $product_id ) . "\r\n\r\n";
	$email_message .= sprintf( esc_html__( 'Subject: %s', 'aqualuxe' ), $subject ) . "\r\n\r\n";
	$email_message .= sprintf( esc_html__( 'Message: %s', 'aqualuxe' ), $message ) . "\r\n\r\n";
	$email_message .= sprintf( esc_html__( 'Sent from: %s', 'aqualuxe' ), home_url() );

	// Email headers
	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'From: ' . $name . ' <' . $email . '>',
		'Reply-To: ' . $email,
	);

	// Send email
	$sent = wp_mail( $admin_email, $email_subject, $email_message, $headers );

	if ( $sent ) {
		wp_send_json_success( array( 'message' => esc_html__( 'Your inquiry has been sent successfully. We will get back to you soon.', 'aqualuxe' ) ) );
	} else {
		wp_send_json_error( array( 'message' => esc_html__( 'Failed to send your inquiry. Please try again later.', 'aqualuxe' ) ) );
	}
}
add_action( 'wp_ajax_aqualuxe_product_inquiry', 'aqualuxe_product_inquiry_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_product_inquiry', 'aqualuxe_product_inquiry_ajax' );