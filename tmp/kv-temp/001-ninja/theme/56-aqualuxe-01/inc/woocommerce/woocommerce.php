<?php
/**
 * WooCommerce compatibility file
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
	// Add theme support for WooCommerce.
	add_theme_support( 'woocommerce' );
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
	// Get the asset manifest.
	$manifest = aqualuxe_get_asset_manifest();

	// Enqueue WooCommerce styles.
	if ( isset( $manifest['css/woocommerce.css'] ) ) {
		wp_enqueue_style(
			'aqualuxe-woocommerce',
			AQUALUXE_ASSETS_URI . $manifest['css/woocommerce.css'],
			[ 'aqualuxe-main' ],
			AQUALUXE_VERSION
		);
	}
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts' );

/**
 * Get the asset manifest.
 *
 * @return array The asset manifest.
 */
function aqualuxe_get_asset_manifest() {
	static $manifest;

	if ( ! $manifest ) {
		$manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';

		if ( file_exists( $manifest_path ) ) {
			$manifest = json_decode( file_get_contents( $manifest_path ), true );
		} else {
			$manifest = [];
		}
	}

	return $manifest;
}

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
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

/**
 * Before Content.
 *
 * Wraps all WooCommerce content in wrappers which match the theme markup.
 *
 * @return void
 */
function aqualuxe_woocommerce_wrapper_before() {
	?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<?php
			/**
			 * Hook: aqualuxe_content_before.
			 */
			do_action( 'aqualuxe_content_before' );
			?>

			<div class="container mx-auto px-4 py-8">
				<div class="flex flex-wrap -mx-4">
					<?php
					// Get the layout.
					$layout = aqualuxe_get_layout();
					$has_sidebar = aqualuxe_has_sidebar();
					$content_class = $has_sidebar ? 'lg:w-2/3' : 'w-full';
					$sidebar_class = 'lg:w-1/3';

					// Adjust classes based on sidebar position.
					if ( $has_sidebar && 'left-sidebar' === $layout ) {
						?>
						<div class="w-full <?php echo esc_attr( $sidebar_class ); ?> px-4 order-2 lg:order-1">
							<?php get_sidebar( 'shop' ); ?>
						</div>
						<div class="w-full <?php echo esc_attr( $content_class ); ?> px-4 order-1 lg:order-2">
						<?php
					} else {
						?>
						<div class="w-full <?php echo esc_attr( $content_class ); ?> px-4">
						<?php
					}
					?>

					<?php
					/**
					 * Hook: aqualuxe_content_top.
					 */
					do_action( 'aqualuxe_content_top' );
					?>
	<?php
}
add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before' );

/**
 * After Content.
 *
 * Closes the wrapping divs.
 *
 * @return void
 */
function aqualuxe_woocommerce_wrapper_after() {
	?>
					<?php
					/**
					 * Hook: aqualuxe_content_bottom.
					 */
					do_action( 'aqualuxe_content_bottom' );
					?>

				</div><!-- .content-column -->

				<?php
				// Add sidebar for right sidebar layout.
				$layout = aqualuxe_get_layout();
				$has_sidebar = aqualuxe_has_sidebar();
				$sidebar_class = 'lg:w-1/3';

				if ( $has_sidebar && 'right-sidebar' === $layout ) :
					?>
					<div class="w-full <?php echo esc_attr( $sidebar_class ); ?> px-4">
						<?php get_sidebar( 'shop' ); ?>
					</div>
					<?php
				endif;
				?>
			</div><!-- .row -->
		</div><!-- .container -->

		<?php
		/**
		 * Hook: aqualuxe_content_after.
		 */
		do_action( 'aqualuxe_content_after' );
		?>

	</main><!-- #main -->
</div><!-- #primary -->
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

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_fragments' );

/**
 * Cart Count.
 */
function aqualuxe_woocommerce_cart_count() {
	?>
	<span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
		<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
	</span>
	<?php
}

/**
 * Change number of products per row.
 *
 * @return int Number of products per row.
 */
function aqualuxe_woocommerce_loop_columns() {
	$columns = get_theme_mod( 'aqualuxe_shop_columns', '4' );
	return intval( $columns );
}
add_filter( 'loop_shop_columns', 'aqualuxe_woocommerce_loop_columns' );

/**
 * Change number of products per page.
 *
 * @param int $products_per_page Number of products per page.
 * @return int Number of products per page.
 */
function aqualuxe_woocommerce_products_per_page( $products_per_page ) {
	$products_per_page = get_theme_mod( 'aqualuxe_products_per_page', 12 );
	return intval( $products_per_page );
}
add_filter( 'loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page' );

/**
 * Change number of related products.
 *
 * @param array $args Related products args.
 * @return array Related products args.
 */
function aqualuxe_woocommerce_related_products_args( $args ) {
	$args['posts_per_page'] = 3;
	$args['columns'] = 3;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args' );

/**
 * Change number of upsell products.
 *
 * @param array $args Upsell products args.
 * @return array Upsell products args.
 */
function aqualuxe_woocommerce_upsell_products_args( $args ) {
	$args['posts_per_page'] = 3;
	$args['columns'] = 3;
	return $args;
}
add_filter( 'woocommerce_upsell_display_args', 'aqualuxe_woocommerce_upsell_products_args' );

/**
 * Change number of cross-sell products.
 *
 * @param array $args Cross-sell products args.
 * @return array Cross-sell products args.
 */
function aqualuxe_woocommerce_cross_sell_products_args( $args ) {
	$columns = get_theme_mod( 'aqualuxe_cross_sells_columns', '2' );
	$args['columns'] = intval( $columns );
	return $args;
}
add_filter( 'woocommerce_cross_sells_columns', 'aqualuxe_woocommerce_cross_sell_products_args' );

/**
 * Add wrapper to product thumbnail.
 */
function aqualuxe_woocommerce_template_loop_product_thumbnail_open() {
	echo '<div class="product-thumbnail relative overflow-hidden rounded-lg mb-4">';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_thumbnail_open', 5 );

/**
 * Close wrapper after product thumbnail.
 */
function aqualuxe_woocommerce_template_loop_product_thumbnail_close() {
	echo '</div>';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_thumbnail_close', 15 );

/**
 * Add quick view button.
 */
function aqualuxe_woocommerce_template_loop_quick_view_button() {
	global $product;
	?>
	<div class="product-actions absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
		<button type="button" class="quick-view-button bg-white dark:bg-dark-700 text-dark-600 dark:text-light-500 hover:text-primary-600 dark:hover:text-primary-500 rounded-full w-10 h-10 flex items-center justify-center shadow-md" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php esc_attr_e( 'Quick view', 'aqualuxe' ); ?>">
			<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
			</svg>
		</button>
	</div>
	<?php
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_quick_view_button', 10 );

/**
 * Add wishlist button.
 */
function aqualuxe_woocommerce_template_loop_wishlist_button() {
	global $product;
	
	// Get the wishlist.
	$wishlist = [];
	if ( is_user_logged_in() ) {
		$wishlist = get_user_meta( get_current_user_id(), 'aqualuxe_wishlist', true );
	} else {
		$wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : [];
	}
	
	// Check if the product is in the wishlist.
	$in_wishlist = is_array( $wishlist ) && in_array( $product->get_id(), $wishlist, true );
	?>
	<button type="button" class="wishlist-toggle bg-white dark:bg-dark-700 text-dark-600 dark:text-light-500 hover:text-primary-600 dark:hover:text-primary-500 rounded-full w-10 h-10 flex items-center justify-center shadow-md ml-2 <?php echo $in_wishlist ? 'in-wishlist' : ''; ?>" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php echo $in_wishlist ? esc_attr__( 'Remove from wishlist', 'aqualuxe' ) : esc_attr__( 'Add to wishlist', 'aqualuxe' ); ?>">
		<svg class="w-5 h-5 <?php echo $in_wishlist ? 'text-primary-600 dark:text-primary-500' : ''; ?>" fill="<?php echo $in_wishlist ? 'currentColor' : 'none'; ?>" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
			<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
		</svg>
	</button>
	<?php
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_wishlist_button', 15 );

/**
 * Add product badges.
 */
function aqualuxe_woocommerce_show_product_badges() {
	// Check if product badges are enabled.
	$show_product_badges = get_theme_mod( 'aqualuxe_show_product_badges', true );
	if ( ! $show_product_badges ) {
		return;
	}

	global $product;

	// Sale badge.
	if ( $product->is_on_sale() ) {
		echo '<span class="product-badge sale-badge bg-accent-600 text-dark-600 text-xs font-medium px-2 py-1 rounded-md absolute top-4 left-4 z-10">' . esc_html__( 'Sale', 'aqualuxe' ) . '</span>';
	}

	// New badge.
	$new_days = 14; // Number of days to consider a product as new.
	$product_date = strtotime( $product->get_date_created() );
	$now = time();
	$days_diff = floor( ( $now - $product_date ) / ( 60 * 60 * 24 ) );

	if ( $days_diff < $new_days ) {
		echo '<span class="product-badge new-badge bg-primary-600 text-white text-xs font-medium px-2 py-1 rounded-md absolute top-4 right-4 z-10">' . esc_html__( 'New', 'aqualuxe' ) . '</span>';
	}

	// Out of stock badge.
	if ( ! $product->is_in_stock() ) {
		echo '<span class="product-badge out-of-stock-badge bg-gray-600 text-white text-xs font-medium px-2 py-1 rounded-md absolute top-4 left-4 z-10">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
	}
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_show_product_badges', 10 );

/**
 * Add wrapper to product content.
 */
function aqualuxe_woocommerce_template_loop_product_content_open() {
	echo '<div class="product-content">';
}
add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_content_open', 5 );

/**
 * Close wrapper after product content.
 */
function aqualuxe_woocommerce_template_loop_product_content_close() {
	echo '</div>';
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_product_content_close', 20 );

/**
 * Add wrapper to product title.
 */
function aqualuxe_woocommerce_template_loop_product_title_open() {
	echo '<h2 class="woocommerce-loop-product__title text-lg font-serif font-bold mb-2">';
}
add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_title_open', 9 );

/**
 * Close wrapper after product title.
 */
function aqualuxe_woocommerce_template_loop_product_title_close() {
	echo '</h2>';
}
add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_title_close', 11 );

/**
 * Add wrapper to product price.
 */
function aqualuxe_woocommerce_template_loop_price_open() {
	echo '<div class="price-wrap mb-4">';
}
add_action( 'woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_price_open', 9 );

/**
 * Close wrapper after product price.
 */
function aqualuxe_woocommerce_template_loop_price_close() {
	echo '</div>';
}
add_action( 'woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_price_close', 11 );

/**
 * Add wrapper to add to cart button.
 */
function aqualuxe_woocommerce_template_loop_add_to_cart_open() {
	echo '<div class="add-to-cart-wrap flex items-center">';
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_add_to_cart_open', 9 );

/**
 * Close wrapper after add to cart button.
 */
function aqualuxe_woocommerce_template_loop_add_to_cart_close() {
	echo '</div>';
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_add_to_cart_close', 16 );

/**
 * Modify add to cart button classes.
 *
 * @param array  $args Button arguments.
 * @param object $product Product object.
 * @return array Button arguments.
 */
function aqualuxe_woocommerce_loop_add_to_cart_args( $args, $product ) {
	$args['class'] = str_replace( 'button', 'button btn btn-primary btn-sm', $args['class'] );
	return $args;
}
add_filter( 'woocommerce_loop_add_to_cart_args', 'aqualuxe_woocommerce_loop_add_to_cart_args', 10, 2 );

/**
 * Modify breadcrumb defaults.
 *
 * @param array $defaults Breadcrumb defaults.
 * @return array Breadcrumb defaults.
 */
function aqualuxe_woocommerce_breadcrumb_defaults( $defaults ) {
	$defaults['delimiter'] = '<span class="breadcrumb-separator mx-2">/</span>';
	$defaults['wrap_before'] = '<div class="breadcrumbs py-4 text-sm text-gray-600 dark:text-gray-400"><div class="container mx-auto px-4"><nav class="woocommerce-breadcrumb">';
	$defaults['wrap_after'] = '</nav></div></div>';
	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_defaults' );

/**
 * Add wrapper to single product summary.
 */
function aqualuxe_woocommerce_before_single_product_summary() {
	echo '<div class="product-summary-wrap">';
}
add_action( 'woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_before_single_product_summary', 5 );

/**
 * Close wrapper after single product summary.
 */
function aqualuxe_woocommerce_after_single_product_summary() {
	echo '</div>';
}
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_after_single_product_summary', 5 );

/**
 * Add wrapper to single product gallery.
 */
function aqualuxe_woocommerce_before_single_product_gallery() {
	echo '<div class="product-gallery-wrap">';
}
add_action( 'woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_before_single_product_gallery', 19 );

/**
 * Close wrapper after single product gallery.
 */
function aqualuxe_woocommerce_after_single_product_gallery() {
	echo '</div>';
}
add_action( 'woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_after_single_product_gallery', 21 );

/**
 * Add wrapper to single product title.
 */
function aqualuxe_woocommerce_template_single_title_open() {
	echo '<div class="product-title-wrap mb-4">';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_title_open', 4 );

/**
 * Close wrapper after single product title.
 */
function aqualuxe_woocommerce_template_single_title_close() {
	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_title_close', 6 );

/**
 * Add wrapper to single product price.
 */
function aqualuxe_woocommerce_template_single_price_open() {
	echo '<div class="product-price-wrap mb-4">';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_price_open', 9 );

/**
 * Close wrapper after single product price.
 */
function aqualuxe_woocommerce_template_single_price_close() {
	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_price_close', 11 );

/**
 * Add wrapper to single product excerpt.
 */
function aqualuxe_woocommerce_template_single_excerpt_open() {
	echo '<div class="product-excerpt-wrap mb-6">';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_excerpt_open', 19 );

/**
 * Close wrapper after single product excerpt.
 */
function aqualuxe_woocommerce_template_single_excerpt_close() {
	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_excerpt_close', 21 );

/**
 * Add wrapper to single product add to cart.
 */
function aqualuxe_woocommerce_template_single_add_to_cart_open() {
	echo '<div class="product-add-to-cart-wrap mb-6">';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_add_to_cart_open', 29 );

/**
 * Close wrapper after single product add to cart.
 */
function aqualuxe_woocommerce_template_single_add_to_cart_close() {
	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_add_to_cart_close', 31 );

/**
 * Add wrapper to single product meta.
 */
function aqualuxe_woocommerce_template_single_meta_open() {
	echo '<div class="product-meta-wrap mb-6">';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_meta_open', 39 );

/**
 * Close wrapper after single product meta.
 */
function aqualuxe_woocommerce_template_single_meta_close() {
	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_meta_close', 41 );

/**
 * Add wrapper to single product sharing.
 */
function aqualuxe_woocommerce_template_single_sharing_open() {
	echo '<div class="product-sharing-wrap">';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_sharing_open', 49 );

/**
 * Close wrapper after single product sharing.
 */
function aqualuxe_woocommerce_template_single_sharing_close() {
	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_sharing_close', 51 );

/**
 * Add wrapper to single product tabs.
 */
function aqualuxe_woocommerce_output_product_data_tabs_open() {
	echo '<div class="product-tabs-wrap mb-12">';
}
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_output_product_data_tabs_open', 9 );

/**
 * Close wrapper after single product tabs.
 */
function aqualuxe_woocommerce_output_product_data_tabs_close() {
	echo '</div>';
}
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_output_product_data_tabs_close', 11 );

/**
 * Add wrapper to related products.
 */
function aqualuxe_woocommerce_output_related_products_open() {
	echo '<div class="related-products-wrap mb-12">';
}
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_output_related_products_open', 19 );

/**
 * Close wrapper after related products.
 */
function aqualuxe_woocommerce_output_related_products_close() {
	echo '</div>';
}
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_output_related_products_close', 21 );

/**
 * Add wrapper to upsell products.
 */
function aqualuxe_woocommerce_output_upsell_products_open() {
	echo '<div class="upsell-products-wrap mb-12">';
}
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_output_upsell_products_open', 14 );

/**
 * Close wrapper after upsell products.
 */
function aqualuxe_woocommerce_output_upsell_products_close() {
	echo '</div>';
}
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_output_upsell_products_close', 16 );

/**
 * Add wrapper to cart table.
 */
function aqualuxe_woocommerce_before_cart_table() {
	echo '<div class="cart-table-wrap mb-6">';
}
add_action( 'woocommerce_before_cart_table', 'aqualuxe_woocommerce_before_cart_table' );

/**
 * Close wrapper after cart table.
 */
function aqualuxe_woocommerce_after_cart_table() {
	echo '</div>';
}
add_action( 'woocommerce_after_cart_table', 'aqualuxe_woocommerce_after_cart_table' );

/**
 * Add wrapper to cart collaterals.
 */
function aqualuxe_woocommerce_before_cart_collaterals() {
	echo '<div class="cart-collaterals-wrap">';
}
add_action( 'woocommerce_before_cart_collaterals', 'aqualuxe_woocommerce_before_cart_collaterals' );

/**
 * Close wrapper after cart collaterals.
 */
function aqualuxe_woocommerce_after_cart_collaterals() {
	echo '</div>';
}
add_action( 'woocommerce_after_cart', 'aqualuxe_woocommerce_after_cart_collaterals' );

/**
 * Add wrapper to cross-sells.
 */
function aqualuxe_woocommerce_before_cross_sells() {
	echo '<div class="cross-sells-wrap mb-6">';
}
add_action( 'woocommerce_before_cart_collaterals', 'aqualuxe_woocommerce_before_cross_sells' );

/**
 * Close wrapper after cross-sells.
 */
function aqualuxe_woocommerce_after_cross_sells() {
	echo '</div>';
}
add_action( 'woocommerce_cart_collaterals', 'aqualuxe_woocommerce_after_cross_sells', 5 );

/**
 * Add wrapper to checkout form.
 */
function aqualuxe_woocommerce_checkout_before_customer_details() {
	echo '<div class="checkout-form-wrap">';
}
add_action( 'woocommerce_checkout_before_customer_details', 'aqualuxe_woocommerce_checkout_before_customer_details' );

/**
 * Close wrapper after checkout form.
 */
function aqualuxe_woocommerce_checkout_after_customer_details() {
	echo '</div>';
}
add_action( 'woocommerce_checkout_after_customer_details', 'aqualuxe_woocommerce_checkout_after_customer_details' );

/**
 * Add wrapper to checkout order review.
 */
function aqualuxe_woocommerce_checkout_before_order_review_heading() {
	echo '<div class="checkout-order-review-wrap">';
}
add_action( 'woocommerce_checkout_before_order_review_heading', 'aqualuxe_woocommerce_checkout_before_order_review_heading' );

/**
 * Close wrapper after checkout order review.
 */
function aqualuxe_woocommerce_checkout_after_order_review() {
	echo '</div>';
}
add_action( 'woocommerce_checkout_after_order_review', 'aqualuxe_woocommerce_checkout_after_order_review' );

/**
 * Add wrapper to account navigation.
 */
function aqualuxe_woocommerce_before_account_navigation() {
	echo '<div class="account-navigation-wrap mb-6">';
}
add_action( 'woocommerce_before_account_navigation', 'aqualuxe_woocommerce_before_account_navigation' );

/**
 * Close wrapper after account navigation.
 */
function aqualuxe_woocommerce_after_account_navigation() {
	echo '</div>';
}
add_action( 'woocommerce_after_account_navigation', 'aqualuxe_woocommerce_after_account_navigation' );

/**
 * Add wrapper to account content.
 */
function aqualuxe_woocommerce_account_content_open() {
	echo '<div class="account-content-wrap">';
}
add_action( 'woocommerce_account_content', 'aqualuxe_woocommerce_account_content_open', 5 );

/**
 * Close wrapper after account content.
 */
function aqualuxe_woocommerce_account_content_close() {
	echo '</div>';
}
add_action( 'woocommerce_account_content', 'aqualuxe_woocommerce_account_content_close', 95 );

/**
 * Add account dashboard welcome message.
 */
function aqualuxe_woocommerce_account_dashboard_welcome() {
	// Check if account dashboard welcome is enabled.
	$show_account_dashboard_welcome = get_theme_mod( 'aqualuxe_show_account_dashboard_welcome', true );
	if ( ! $show_account_dashboard_welcome ) {
		return;
	}

	// Get the welcome text.
	$welcome_text = get_theme_mod( 'aqualuxe_account_dashboard_welcome_text', __( 'Welcome to your account dashboard. Here you can manage your orders, addresses, account details, and more.', 'aqualuxe' ) );
	?>
	<div class="account-dashboard-welcome bg-white dark:bg-dark-700 rounded-lg shadow-soft p-6 mb-6">
		<h2 class="text-xl font-serif font-bold text-dark-600 dark:text-light-500 mb-4">
			<?php
			/* translators: %s: customer username */
			printf( esc_html__( 'Hello %s,', 'aqualuxe' ), esc_html( wp_get_current_user()->display_name ) );
			?>
		</h2>
		<p class="text-gray-600 dark:text-gray-400">
			<?php echo wp_kses_post( $welcome_text ); ?>
		</p>
	</div>
	<?php
}
add_action( 'woocommerce_account_dashboard', 'aqualuxe_woocommerce_account_dashboard_welcome', 5 );

/**
 * Add AJAX add to cart support for single products.
 */
function aqualuxe_ajax_add_to_cart() {
	// Check nonce.
	if ( ! check_ajax_referer( 'aqualuxe-add-to-cart-nonce', 'nonce', false ) ) {
		wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ] );
	}

	// Get the product ID.
	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

	// Check if product ID is valid.
	if ( $product_id <= 0 ) {
		wp_send_json_error( [ 'message' => __( 'Invalid product ID.', 'aqualuxe' ) ] );
	}

	// Get the quantity.
	$quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;

	// Get the variation ID.
	$variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;

	// Get the variation attributes.
	$variation = [];
	foreach ( $_POST as $key => $value ) {
		if ( 0 === strpos( $key, 'attribute_' ) ) {
			$variation[ $key ] = sanitize_text_field( $value );
		}
	}

	// Add the product to the cart.
	$cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );

	// Check if the product was added to the cart.
	if ( $cart_item_key ) {
		// Get the cart fragments.
		ob_start();
		woocommerce_mini_cart();
		$mini_cart = ob_get_clean();

		wp_send_json_success(
			[
				'message'   => __( 'Product added to cart.', 'aqualuxe' ),
				'fragments' => [
					'.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
					'.cart-count'                  => '<span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">' . WC()->cart->get_cart_contents_count() . '</span>',
				],
				'cart_hash' => WC()->cart->get_cart_hash(),
			]
		);
	} else {
		wp_send_json_error( [ 'message' => __( 'Failed to add product to cart.', 'aqualuxe' ) ] );
	}

	wp_die();
}
add_action( 'wp_ajax_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart' );

/**
 * Add AJAX remove from cart support.
 */
function aqualuxe_ajax_remove_from_cart() {
	// Check nonce.
	if ( ! check_ajax_referer( 'aqualuxe-remove-from-cart-nonce', 'nonce', false ) ) {
		wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ] );
	}

	// Get the cart item key.
	$cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( $_POST['cart_item_key'] ) : '';

	// Check if cart item key is valid.
	if ( empty( $cart_item_key ) ) {
		wp_send_json_error( [ 'message' => __( 'Invalid cart item key.', 'aqualuxe' ) ] );
	}

	// Remove the cart item.
	$removed = WC()->cart->remove_cart_item( $cart_item_key );

	// Check if the cart item was removed.
	if ( $removed ) {
		// Get the cart fragments.
		ob_start();
		woocommerce_mini_cart();
		$mini_cart = ob_get_clean();

		wp_send_json_success(
			[
				'message'   => __( 'Product removed from cart.', 'aqualuxe' ),
				'fragments' => [
					'.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
					'.cart-count'                  => '<span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">' . WC()->cart->get_cart_contents_count() . '</span>',
				],
				'cart_hash' => WC()->cart->get_cart_hash(),
			]
		);
	} else {
		wp_send_json_error( [ 'message' => __( 'Failed to remove product from cart.', 'aqualuxe' ) ] );
	}

	wp_die();
}
add_action( 'wp_ajax_aqualuxe_ajax_remove_from_cart', 'aqualuxe_ajax_remove_from_cart' );
add_action( 'wp_ajax_nopriv_aqualuxe_ajax_remove_from_cart', 'aqualuxe_ajax_remove_from_cart' );

/**
 * Add AJAX update cart support.
 */
function aqualuxe_ajax_update_cart() {
	// Check nonce.
	if ( ! check_ajax_referer( 'aqualuxe-update-cart-nonce', 'nonce', false ) ) {
		wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ] );
	}

	// Get the cart item key.
	$cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( $_POST['cart_item_key'] ) : '';

	// Check if cart item key is valid.
	if ( empty( $cart_item_key ) ) {
		wp_send_json_error( [ 'message' => __( 'Invalid cart item key.', 'aqualuxe' ) ] );
	}

	// Get the quantity.
	$quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;

	// Update the cart item.
	$updated = WC()->cart->set_quantity( $cart_item_key, $quantity );

	// Check if the cart item was updated.
	if ( $updated ) {
		// Get the cart fragments.
		ob_start();
		woocommerce_mini_cart();
		$mini_cart = ob_get_clean();

		wp_send_json_success(
			[
				'message'   => __( 'Cart updated.', 'aqualuxe' ),
				'fragments' => [
					'.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
					'.cart-count'                  => '<span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">' . WC()->cart->get_cart_contents_count() . '</span>',
				],
				'cart_hash' => WC()->cart->get_cart_hash(),
			]
		);
	} else {
		wp_send_json_error( [ 'message' => __( 'Failed to update cart.', 'aqualuxe' ) ] );
	}

	wp_die();
}
add_action( 'wp_ajax_aqualuxe_ajax_update_cart', 'aqualuxe_ajax_update_cart' );
add_action( 'wp_ajax_nopriv_aqualuxe_ajax_update_cart', 'aqualuxe_ajax_update_cart' );

/**
 * Add AJAX toggle wishlist support.
 */
function aqualuxe_ajax_toggle_wishlist() {
	// Check nonce.
	if ( ! check_ajax_referer( 'aqualuxe-wishlist-nonce', 'nonce', false ) ) {
		wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ] );
	}

	// Get the product ID.
	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

	// Check if product ID is valid.
	if ( $product_id <= 0 ) {
		wp_send_json_error( [ 'message' => __( 'Invalid product ID.', 'aqualuxe' ) ] );
	}

	// Get the current wishlist.
	$wishlist = [];
	if ( is_user_logged_in() ) {
		$wishlist = get_user_meta( get_current_user_id(), 'aqualuxe_wishlist', true );
	} else {
		$wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : [];
	}

	// Ensure the wishlist is an array.
	if ( ! is_array( $wishlist ) ) {
		$wishlist = [];
	}

	// Check if the product is already in the wishlist.
	$index = array_search( $product_id, $wishlist, true );

	if ( false !== $index ) {
		// Remove the product from the wishlist.
		unset( $wishlist[ $index ] );
		$status = 'removed';
		$message = __( 'Product removed from wishlist.', 'aqualuxe' );
	} else {
		// Add the product to the wishlist.
		$wishlist[] = $product_id;
		$status = 'added';
		$message = __( 'Product added to wishlist.', 'aqualuxe' );
	}

	// Save the wishlist.
	if ( is_user_logged_in() ) {
		update_user_meta( get_current_user_id(), 'aqualuxe_wishlist', $wishlist );
	} else {
		setcookie( 'aqualuxe_wishlist', json_encode( $wishlist ), time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
	}

	wp_send_json_success(
		[
			'status'  => $status,
			'message' => $message,
			'count'   => count( $wishlist ),
		]
	);

	wp_die();
}
add_action( 'wp_ajax_aqualuxe_ajax_toggle_wishlist', 'aqualuxe_ajax_toggle_wishlist' );
add_action( 'wp_ajax_nopriv_aqualuxe_ajax_toggle_wishlist', 'aqualuxe_ajax_toggle_wishlist' );

/**
 * Add AJAX quick view support.
 */
function aqualuxe_ajax_quick_view() {
	// Check nonce.
	if ( ! check_ajax_referer( 'aqualuxe-quick-view-nonce', 'nonce', false ) ) {
		wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ] );
	}

	// Get the product ID.
	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

	// Check if product ID is valid.
	if ( $product_id <= 0 ) {
		wp_send_json_error( [ 'message' => __( 'Invalid product ID.', 'aqualuxe' ) ] );
	}

	// Get the product.
	$product = wc_get_product( $product_id );

	// Check if product exists.
	if ( ! $product ) {
		wp_send_json_error( [ 'message' => __( 'Product not found.', 'aqualuxe' ) ] );
	}

	// Get the quick view template.
	ob_start();
	include get_template_directory() . '/templates/parts/product/quick-view.php';
	$html = ob_get_clean();

	wp_send_json_success( [ 'html' => $html ] );
	wp_die();
}
add_action( 'wp_ajax_aqualuxe_ajax_quick_view', 'aqualuxe_ajax_quick_view' );
add_action( 'wp_ajax_nopriv_aqualuxe_ajax_quick_view', 'aqualuxe_ajax_quick_view' );

/**
 * Add AJAX filter products support.
 */
function aqualuxe_ajax_filter_products() {
	// Check nonce.
	if ( ! check_ajax_referer( 'aqualuxe-filter-nonce', 'nonce', false ) ) {
		wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ] );
	}

	// Get the filter parameters.
	$category = isset( $_POST['category'] ) ? absint( $_POST['category'] ) : 0;
	$tag = isset( $_POST['tag'] ) ? absint( $_POST['tag'] ) : 0;
	$min_price = isset( $_POST['min_price'] ) ? floatval( $_POST['min_price'] ) : 0;
	$max_price = isset( $_POST['max_price'] ) ? floatval( $_POST['max_price'] ) : 0;
	$orderby = isset( $_POST['orderby'] ) ? sanitize_text_field( $_POST['orderby'] ) : 'date';
	$order = isset( $_POST['order'] ) ? sanitize_text_field( $_POST['order'] ) : 'desc';
	$paged = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;
	$posts_per_page = isset( $_POST['posts_per_page'] ) ? absint( $_POST['posts_per_page'] ) : get_option( 'posts_per_page' );

	// Get the attributes.
	$attributes = [];
	foreach ( $_POST as $key => $value ) {
		if ( 0 === strpos( $key, 'attribute_' ) ) {
			$attribute_name = str_replace( 'attribute_', '', $key );
			$attributes[ $attribute_name ] = sanitize_text_field( $value );
		}
	}

	// Build the query arguments.
	$args = [
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'paged'          => $paged,
		'posts_per_page' => $posts_per_page,
		'orderby'        => $orderby,
		'order'          => $order,
	];

	// Add category filter.
	if ( $category > 0 ) {
		$args['tax_query'][] = [
			'taxonomy' => 'product_cat',
			'field'    => 'term_id',
			'terms'    => $category,
		];
	}

	// Add tag filter.
	if ( $tag > 0 ) {
		$args['tax_query'][] = [
			'taxonomy' => 'product_tag',
			'field'    => 'term_id',
			'terms'    => $tag,
		];
	}

	// Add price filter.
	if ( $min_price > 0 || $max_price > 0 ) {
		$args['meta_query'][] = [
			'key'     => '_price',
			'value'   => [ $min_price, $max_price ],
			'type'    => 'NUMERIC',
			'compare' => 'BETWEEN',
		];
	}

	// Add attribute filters.
	if ( ! empty( $attributes ) ) {
		foreach ( $attributes as $attribute_name => $attribute_value ) {
			$args['tax_query'][] = [
				'taxonomy' => 'pa_' . $attribute_name,
				'field'    => 'slug',
				'terms'    => explode( ',', $attribute_value ),
			];
		}
	}

	// Run the query.
	$query = new WP_Query( $args );

	// Check if we have products.
	if ( $query->have_posts() ) {
		ob_start();

		woocommerce_product_loop_start();

		while ( $query->have_posts() ) {
			$query->the_post();
			wc_get_template_part( 'content', 'product' );
		}

		woocommerce_product_loop_end();

		$html = ob_get_clean();

		// Build the filter URL.
		$filter_url = add_query_arg(
			[
				'category'   => $category,
				'tag'        => $tag,
				'min_price'  => $min_price,
				'max_price'  => $max_price,
				'orderby'    => $orderby,
				'order'      => $order,
				'paged'      => $paged,
			],
			get_permalink( wc_get_page_id( 'shop' ) )
		);

		// Add attributes to the filter URL.
		foreach ( $attributes as $attribute_name => $attribute_value ) {
			$filter_url = add_query_arg( 'attribute_' . $attribute_name, $attribute_value, $filter_url );
		}

		wp_send_json_success(
			[
				'html'       => $html,
				'found'      => $query->found_posts,
				'max_pages'  => $query->max_num_pages,
				'next_page'  => $paged + 1,
				'url'        => $filter_url,
				'count'      => sprintf(
					/* translators: %d: total results */
					_n( '%d product found', '%d products found', $query->found_posts, 'aqualuxe' ),
					$query->found_posts
				),
			]
		);
	} else {
		wp_send_json_error(
			[
				'message' => __( 'No products found.', 'aqualuxe' ),
				'html'    => '<div class="woocommerce-info">' . __( 'No products were found matching your selection.', 'aqualuxe' ) . '</div>',
			]
		);
	}

	wp_reset_postdata();
	wp_die();
}
add_action( 'wp_ajax_aqualuxe_ajax_filter_products', 'aqualuxe_ajax_filter_products' );
add_action( 'wp_ajax_nopriv_aqualuxe_ajax_filter_products', 'aqualuxe_ajax_filter_products' );

/**
 * Register WooCommerce REST API endpoints.
 */
function aqualuxe_register_woocommerce_rest_api_endpoints() {
	register_rest_route(
		'aqualuxe/v1',
		'/products/(?P<id>\d+)/quick-view',
		[
			'methods'             => 'GET',
			'callback'            => 'aqualuxe_rest_api_quick_view',
			'permission_callback' => '__return_true',
		]
	);
}
add_action( 'rest_api_init', 'aqualuxe_register_woocommerce_rest_api_endpoints' );

/**
 * REST API quick view callback.
 *
 * @param WP_REST_Request $request The request object.
 * @return WP_REST_Response The response object.
 */
function aqualuxe_rest_api_quick_view( $request ) {
	// Get the product ID.
	$product_id = $request->get_param( 'id' );

	// Get the product.
	$product = wc_get_product( $product_id );

	// Check if product exists.
	if ( ! $product ) {
		return new WP_Error( 'product_not_found', __( 'Product not found.', 'aqualuxe' ), [ 'status' => 404 ] );
	}

	// Get the quick view template.
	ob_start();
	include get_template_directory() . '/templates/parts/product/quick-view.php';
	$html = ob_get_clean();

	return rest_ensure_response( [ 'html' => $html ] );
}

/**
 * Create wishlist page.
 */
function aqualuxe_create_wishlist_page() {
	// Check if wishlist page exists.
	$wishlist_page_id = get_option( 'aqualuxe_wishlist_page_id' );
	$wishlist_page = get_post( $wishlist_page_id );

	if ( ! $wishlist_page ) {
		// Create wishlist page.
		$wishlist_page_id = wp_insert_post(
			[
				'post_title'     => __( 'Wishlist', 'aqualuxe' ),
				'post_content'   => '[aqualuxe_wishlist]',
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
			]
		);

		// Save wishlist page ID.
		update_option( 'aqualuxe_wishlist_page_id', $wishlist_page_id );
	}
}
add_action( 'after_switch_theme', 'aqualuxe_create_wishlist_page' );

/**
 * Register wishlist shortcode.
 */
function aqualuxe_wishlist_shortcode() {
	// Get the wishlist.
	$wishlist = [];
	if ( is_user_logged_in() ) {
		$wishlist = get_user_meta( get_current_user_id(), 'aqualuxe_wishlist', true );
	} else {
		$wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : [];
	}

	// Ensure the wishlist is an array.
	if ( ! is_array( $wishlist ) ) {
		$wishlist = [];
	}

	// Remove duplicates and empty values.
	$wishlist = array_unique( array_filter( $wishlist ) );

	ob_start();

	if ( ! empty( $wishlist ) ) {
		// Get the products.
		$args = [
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'post__in'       => $wishlist,
			'orderby'        => 'post__in',
		];

		$products = new WP_Query( $args );

		if ( $products->have_posts() ) {
			?>
			<div class="wishlist-products">
				<table class="wishlist-table w-full">
					<thead>
						<tr>
							<th class="product-remove">&nbsp;</th>
							<th class="product-thumbnail">&nbsp;</th>
							<th class="product-name"><?php esc_html_e( 'Product', 'aqualuxe' ); ?></th>
							<th class="product-price"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></th>
							<th class="product-stock"><?php esc_html_e( 'Stock', 'aqualuxe' ); ?></th>
							<th class="product-actions">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ( $products->have_posts() ) {
							$products->the_post();
							$product = wc_get_product( get_the_ID() );
							?>
							<tr>
								<td class="product-remove">
									<button type="button" class="wishlist-remove" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
										<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
										</svg>
									</button>
								</td>
								<td class="product-thumbnail">
									<?php
									if ( has_post_thumbnail() ) {
										echo get_the_post_thumbnail( $product->get_id(), 'thumbnail', [ 'class' => 'w-16 h-16 object-cover rounded' ] );
									} else {
										echo wc_placeholder_img( 'thumbnail' );
									}
									?>
								</td>
								<td class="product-name">
									<a href="<?php the_permalink(); ?>" class="text-dark-600 dark:text-light-500 hover:text-primary-600 dark:hover:text-primary-500 font-medium">
										<?php the_title(); ?>
									</a>
								</td>
								<td class="product-price">
									<?php echo $product->get_price_html(); ?>
								</td>
								<td class="product-stock">
									<?php
									if ( $product->is_in_stock() ) {
										echo '<span class="text-green-600">' . esc_html__( 'In Stock', 'aqualuxe' ) . '</span>';
									} else {
										echo '<span class="text-red-600">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
									}
									?>
								</td>
								<td class="product-actions">
									<?php
									if ( $product->is_in_stock() ) {
										echo '<a href="' . esc_url( $product->add_to_cart_url() ) . '" class="btn btn-primary btn-sm add_to_cart_button ajax_add_to_cart" data-product_id="' . esc_attr( $product->get_id() ) . '" data-product_sku="' . esc_attr( $product->get_sku() ) . '">' . esc_html__( 'Add to Cart', 'aqualuxe' ) . '</a>';
									} else {
										echo '<a href="' . esc_url( get_permalink( $product->get_id() ) ) . '" class="btn btn-primary btn-sm">' . esc_html__( 'Read More', 'aqualuxe' ) . '</a>';
									}
									?>
								</td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>
			<?php
		} else {
			?>
			<div class="wishlist-empty">
				<p><?php esc_html_e( 'No products in the wishlist.', 'aqualuxe' ); ?></p>
				<p><a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Go to Shop', 'aqualuxe' ); ?></a></p>
			</div>
			<?php
		}

		wp_reset_postdata();
	} else {
		?>
		<div class="wishlist-empty">
			<p><?php esc_html_e( 'No products in the wishlist.', 'aqualuxe' ); ?></p>
			<p><a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Go to Shop', 'aqualuxe' ); ?></a></p>
		</div>
		<?php
	}

	return ob_get_clean();
}
add_shortcode( 'aqualuxe_wishlist', 'aqualuxe_wishlist_shortcode' );

/**
 * Add distraction free checkout.
 */
function aqualuxe_distraction_free_checkout() {
	// Check if distraction free checkout is enabled.
	$distraction_free_checkout = get_theme_mod( 'aqualuxe_distraction_free_checkout', false );
	if ( ! $distraction_free_checkout ) {
		return;
	}

	// Check if we're on the checkout page.
	if ( ! is_checkout() ) {
		return;
	}

	// Remove header and footer actions.
	remove_all_actions( 'aqualuxe_header' );
	remove_all_actions( 'aqualuxe_footer' );

	// Add simplified header and footer.
	add_action( 'aqualuxe_header', 'aqualuxe_distraction_free_checkout_header' );
	add_action( 'aqualuxe_footer', 'aqualuxe_distraction_free_checkout_footer' );
}
add_action( 'wp', 'aqualuxe_distraction_free_checkout' );

/**
 * Distraction free checkout header.
 */
function aqualuxe_distraction_free_checkout_header() {
	?>
	<div class="distraction-free-checkout-header py-4 border-b border-gray-200 dark:border-dark-700">
		<div class="container mx-auto px-4">
			<div class="flex items-center justify-between">
				<div class="site-branding">
					<?php
					if ( has_custom_logo() ) {
						the_custom_logo();
					} else {
						?>
						<h1 class="site-title text-xl md:text-2xl font-bold">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-dark-600 dark:text-light-500">
								<?php bloginfo( 'name' ); ?>
							</a>
						</h1>
						<?php
					}
					?>
				</div>
				<div class="checkout-steps">
					<?php
					// Get the checkout steps.
					$checkout_steps = [
						'cart'     => __( 'Cart', 'aqualuxe' ),
						'checkout' => __( 'Checkout', 'aqualuxe' ),
						'order'    => __( 'Order Complete', 'aqualuxe' ),
					];

					// Get the current step.
					$current_step = 'checkout';
					if ( is_cart() ) {
						$current_step = 'cart';
					} elseif ( is_order_received_page() ) {
						$current_step = 'order';
					}

					// Output the steps.
					echo '<div class="flex items-center">';
					$i = 0;
					foreach ( $checkout_steps as $step => $label ) {
						$i++;
						$is_current = $step === $current_step;
						$is_completed = array_search( $current_step, array_keys( $checkout_steps ) ) >= array_search( $step, array_keys( $checkout_steps ) );

						echo '<div class="flex items-center">';
						echo '<div class="checkout-step ' . ( $is_current ? 'current' : ( $is_completed ? 'completed' : '' ) ) . '">';
						echo '<span class="checkout-step-number">' . $i . '</span>';
						echo '<span class="checkout-step-label">' . esc_html( $label ) . '</span>';
						echo '</div>';
						if ( $i < count( $checkout_steps ) ) {
							echo '<div class="checkout-step-separator"></div>';
						}
						echo '</div>';
					}
					echo '</div>';
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Distraction free checkout footer.
 */
function aqualuxe_distraction_free_checkout_footer() {
	?>
	<div class="distraction-free-checkout-footer py-4 border-t border-gray-200 dark:border-dark-700">
		<div class="container mx-auto px-4">
			<div class="flex flex-col md:flex-row justify-between items-center">
				<div class="footer-copyright text-sm text-gray-600 dark:text-gray-400 mb-4 md:mb-0">
					<?php
					$copyright_text = get_theme_mod( 'aqualuxe_copyright_text', '&copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '. All rights reserved.' );
					echo wp_kses_post( $copyright_text );
					?>
				</div>
				<div class="footer-payment-icons flex items-center space-x-2">
					<span class="text-sm text-gray-600 dark:text-gray-400 mr-2"><?php esc_html_e( 'We Accept:', 'aqualuxe' ); ?></span>
					<img src="<?php echo esc_url( AQUALUXE_ASSETS_URI . 'images/payment-visa.svg' ); ?>" alt="<?php esc_attr_e( 'Visa', 'aqualuxe' ); ?>" class="h-8">
					<img src="<?php echo esc_url( AQUALUXE_ASSETS_URI . 'images/payment-mastercard.svg' ); ?>" alt="<?php esc_attr_e( 'Mastercard', 'aqualuxe' ); ?>" class="h-8">
					<img src="<?php echo esc_url( AQUALUXE_ASSETS_URI . 'images/payment-amex.svg' ); ?>" alt="<?php esc_attr_e( 'American Express', 'aqualuxe' ); ?>" class="h-8">
					<img src="<?php echo esc_url( AQUALUXE_ASSETS_URI . 'images/payment-paypal.svg' ); ?>" alt="<?php esc_attr_e( 'PayPal', 'aqualuxe' ); ?>" class="h-8">
				</div>
			</div>
		</div>
	</div>
	<?php
}