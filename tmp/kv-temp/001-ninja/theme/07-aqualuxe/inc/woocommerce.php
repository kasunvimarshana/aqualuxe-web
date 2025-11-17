<?php
/**
 * WooCommerce specific functions and customizations
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce setup function.
 */
function aqualuxe_woocommerce_setup() {
	// Add theme support for WooCommerce features
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Remove default WooCommerce styles
	add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );

/**
 * Add WooCommerce specific classes to the body tag
 *
 * @param array $classes CSS classes applied to the body tag.
 * @return array Modified CSS classes.
 */
function aqualuxe_woocommerce_body_classes( $classes ) {
	if ( is_woocommerce() ) {
		$classes[] = 'woocommerce-page';
		
		if ( is_shop() ) {
			$classes[] = 'woocommerce-shop';
		}
		
		if ( is_product() ) {
			$classes[] = 'woocommerce-product';
		}
		
		if ( is_product_category() || is_product_tag() ) {
			$classes[] = 'woocommerce-archive';
		}
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
add_filter( 'body_class', 'aqualuxe_woocommerce_body_classes' );

/**
 * Related Products Args
 *
 * @param array $args Related products args.
 * @return array Modified related products args.
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
 * Upsell Products Args
 *
 * @param array $args Upsell products args.
 * @return array Modified upsell products args.
 */
function aqualuxe_woocommerce_upsell_products_args( $args ) {
	$defaults = array(
		'posts_per_page' => get_theme_mod( 'aqualuxe_upsell_products_count', 4 ),
		'columns'        => get_theme_mod( 'aqualuxe_upsell_products_columns', 4 ),
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}
add_filter( 'woocommerce_upsell_display_args', 'aqualuxe_woocommerce_upsell_products_args' );

/**
 * Cross-sell Products Args
 *
 * @param array $args Cross-sell products args.
 * @return array Modified cross-sell products args.
 */
function aqualuxe_woocommerce_cross_sell_products_args( $args ) {
	$defaults = array(
		'posts_per_page' => get_theme_mod( 'aqualuxe_cross_sell_products_count', 2 ),
		'columns'        => get_theme_mod( 'aqualuxe_cross_sell_products_columns', 2 ),
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}
add_filter( 'woocommerce_cross_sells_args', 'aqualuxe_woocommerce_cross_sell_products_args' );

/**
 * Product gallery thumbnail columns
 *
 * @return integer number of columns
 */
function aqualuxe_woocommerce_thumbnail_columns() {
	return get_theme_mod( 'aqualuxe_product_gallery_thumbnails_columns', 4 );
}
add_filter( 'woocommerce_product_thumbnails_columns', 'aqualuxe_woocommerce_thumbnail_columns' );

/**
 * Products per page
 *
 * @return integer number of products
 */
function aqualuxe_woocommerce_products_per_page() {
	return get_theme_mod( 'aqualuxe_products_per_page', 12 );
}
add_filter( 'loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page' );

/**
 * Product gallery thumbnail size
 *
 * @return string size name
 */
function aqualuxe_woocommerce_gallery_thumbnail_size( $size ) {
	return 'woocommerce_thumbnail';
}
add_filter( 'woocommerce_gallery_thumbnail_size', 'aqualuxe_woocommerce_gallery_thumbnail_size' );

/**
 * Default loop columns on product archives
 *
 * @return integer products per row
 */
function aqualuxe_woocommerce_loop_columns() {
	return get_theme_mod( 'aqualuxe_shop_columns', 3 );
}
add_filter( 'loop_shop_columns', 'aqualuxe_woocommerce_loop_columns' );

/**
 * Modify breadcrumb defaults
 *
 * @param array $defaults Default breadcrumb args.
 * @return array Modified breadcrumb args.
 */
function aqualuxe_woocommerce_breadcrumb_defaults( $defaults ) {
	$defaults['delimiter']   = '<span class="mx-2">/</span>';
	$defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb text-sm text-gray-600 dark:text-gray-400 mb-6" itemprop="breadcrumb">';
	$defaults['wrap_after']  = '</nav>';
	$defaults['home']        = esc_html__( 'Home', 'aqualuxe' );
	
	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_defaults' );

/**
 * Add 'woocommerce-active' class to the body tag
 *
 * @param array $classes CSS classes applied to the body tag.
 * @return array Modified CSS classes.
 */
function aqualuxe_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'aqualuxe_woocommerce_active_body_class' );

/**
 * Cart Fragments
 * Ensure cart contents update when products are added to the cart via AJAX
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
add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_link_fragment' );

/**
 * Cart Link
 * Displayed a link to the cart including the number of items present
 *
 * @return void
 */
function aqualuxe_woocommerce_cart_link() {
	?>
	<a class="cart-contents relative" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
			<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
		</svg>
		<span class="count absolute -top-2 -right-2 bg-accent-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
			<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
		</span>
	</a>
	<?php
}

/**
 * Mini cart
 *
 * @return void
 */
function aqualuxe_woocommerce_mini_cart() {
	if ( is_cart() || is_checkout() ) {
		return;
	}
	?>
	<div class="mini-cart-wrapper relative">
		<?php aqualuxe_woocommerce_cart_link(); ?>
		<div class="mini-cart-dropdown hidden absolute right-0 top-full mt-2 w-80 bg-white dark:bg-dark-700 shadow-medium rounded-lg z-50">
			<div class="widget_shopping_cart_content">
				<?php woocommerce_mini_cart(); ?>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Remove default WooCommerce wrappers
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Add custom wrappers
 */
function aqualuxe_woocommerce_wrapper_before() {
	?>
	<main id="primary" class="site-main container mx-auto px-4 py-8">
	<?php
}
add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before' );

function aqualuxe_woocommerce_wrapper_after() {
	?>
	</main><!-- #main -->
	<?php
}
add_action( 'woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after' );

/**
 * Customize product loop item title
 */
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_title', 10 );

function aqualuxe_woocommerce_template_loop_product_title() {
	echo '<h2 class="woocommerce-loop-product__title text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">' . get_the_title() . '</h2>';
}

/**
 * Customize product loop item price
 */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_price', 10 );

function aqualuxe_woocommerce_template_loop_price() {
	global $product;
	?>
	<span class="price text-lg font-medium text-primary-700 dark:text-primary-400">
		<?php echo $product->get_price_html(); ?>
	</span>
	<?php
}

/**
 * Customize add to cart button
 */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_add_to_cart', 10 );

function aqualuxe_woocommerce_template_loop_add_to_cart() {
	global $product;
	
	$button_classes = 'mt-3 inline-flex items-center px-4 py-2 bg-primary-600 dark:bg-primary-700 text-white rounded-lg hover:bg-primary-700 dark:hover:bg-primary-600 transition-colors duration-300';
	
	echo apply_filters(
		'woocommerce_loop_add_to_cart_link',
		sprintf(
			'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
			esc_attr( $button_classes ),
			isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
			esc_html( $product->add_to_cart_text() )
		),
		$product,
		[]
	);
}

/**
 * Customize sale flash
 */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_show_product_loop_sale_flash', 10 );

function aqualuxe_woocommerce_show_product_loop_sale_flash() {
	global $product;
	
	if ( $product->is_on_sale() ) {
		echo '<span class="onsale absolute top-2 left-2 bg-accent-600 text-white text-xs font-bold px-2 py-1 rounded-md z-10">' . esc_html__( 'Sale!', 'aqualuxe' ) . '</span>';
	}
}

/**
 * Customize single product sale flash
 */
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
add_action( 'woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_show_product_sale_flash', 10 );

function aqualuxe_woocommerce_show_product_sale_flash() {
	global $product;
	
	if ( $product->is_on_sale() ) {
		echo '<span class="onsale absolute top-4 left-4 bg-accent-600 text-white text-sm font-bold px-3 py-1 rounded-md z-10">' . esc_html__( 'Sale!', 'aqualuxe' ) . '</span>';
	}
}

/**
 * Add quick view functionality
 */
function aqualuxe_woocommerce_quick_view_button() {
	if ( ! get_theme_mod( 'aqualuxe_enable_quick_view', true ) ) {
		return;
	}
	
	global $product;
	
	echo '<a href="#" class="quick-view-button absolute top-2 right-2 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 p-2 rounded-full shadow-soft hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300 z-10" data-product-id="' . esc_attr( $product->get_id() ) . '" title="' . esc_attr__( 'Quick View', 'aqualuxe' ) . '">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
	echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
	echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
	echo '</svg>';
	echo '</a>';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_quick_view_button', 15 );

/**
 * Add wishlist functionality
 */
function aqualuxe_woocommerce_wishlist_button() {
	if ( ! get_theme_mod( 'aqualuxe_enable_wishlist', true ) ) {
		return;
	}
	
	global $product;
	
	echo '<a href="#" class="wishlist-button absolute top-2 right-12 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 p-2 rounded-full shadow-soft hover:text-accent-600 dark:hover:text-accent-400 transition-colors duration-300 z-10" data-product-id="' . esc_attr( $product->get_id() ) . '" title="' . esc_attr__( 'Add to Wishlist', 'aqualuxe' ) . '">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
	echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />';
	echo '</svg>';
	echo '</a>';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_wishlist_button', 20 );

/**
 * Customize product loop item image
 */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_thumbnail', 10 );

function aqualuxe_woocommerce_template_loop_product_thumbnail() {
	global $product;
	
	$image_size = apply_filters( 'single_product_archive_thumbnail_size', 'woocommerce_thumbnail' );
	$image_id = $product->get_image_id();
	
	if ( $image_id ) {
		echo '<div class="product-thumbnail relative overflow-hidden rounded-lg aspect-w-1 aspect-h-1">';
		echo wp_get_attachment_image( $image_id, $image_size, false, array( 'class' => 'w-full h-full object-cover transition-transform duration-300 hover:scale-105' ) );
		
		// Show second image on hover if available
		if ( get_theme_mod( 'aqualuxe_enable_product_hover_image', true ) ) {
			$gallery_ids = $product->get_gallery_image_ids();
			if ( ! empty( $gallery_ids ) ) {
				echo wp_get_attachment_image( $gallery_ids[0], $image_size, false, array( 'class' => 'w-full h-full object-cover transition-opacity duration-300 opacity-0 hover:opacity-100 absolute inset-0' ) );
			}
		}
		
		echo '</div>';
	} else {
		echo '<div class="product-thumbnail relative overflow-hidden rounded-lg aspect-w-1 aspect-h-1">';
		echo wc_placeholder_img( $image_size );
		echo '</div>';
	}
}

/**
 * Customize product loop item wrapper
 */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

add_action( 'woocommerce_before_shop_loop_item', 'aqualuxe_woocommerce_template_loop_product_wrapper_open', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_product_wrapper_close', 50 );

function aqualuxe_woocommerce_template_loop_product_wrapper_open() {
	echo '<div class="product-card bg-white dark:bg-dark-800 rounded-lg shadow-soft overflow-hidden transition-shadow duration-300 hover:shadow-medium">';
}

function aqualuxe_woocommerce_template_loop_product_wrapper_close() {
	echo '</div>';
}

add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_link_open', 5 );
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_link_close', 15 );

function aqualuxe_woocommerce_template_loop_product_link_open() {
	global $product;
	echo '<a href="' . esc_url( get_permalink( $product->get_id() ) ) . '" class="block">';
}

function aqualuxe_woocommerce_template_loop_product_link_close() {
	echo '</a>';
}

add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_content_wrapper_open', 5 );
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_product_content_wrapper_close', 20 );

function aqualuxe_woocommerce_template_loop_product_content_wrapper_open() {
	echo '<div class="product-content p-4">';
}

function aqualuxe_woocommerce_template_loop_product_content_wrapper_close() {
	echo '</div>';
}

/**
 * Add product category to loop item
 */
function aqualuxe_woocommerce_template_loop_product_category() {
	global $product;
	
	$categories = wc_get_product_category_list( $product->get_id(), ', ', '<span class="product-category text-xs text-gray-600 dark:text-gray-400 mb-1 block">', '</span>' );
	
	if ( $categories ) {
		echo $categories;
	}
}
add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_category', 9 );

/**
 * Add product rating to loop item
 */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_rating', 15 );

function aqualuxe_woocommerce_template_loop_rating() {
	global $product;
	
	if ( ! wc_review_ratings_enabled() ) {
		return;
	}
	
	$rating_count = $product->get_rating_count();
	$average = $product->get_average_rating();
	
	if ( $rating_count > 0 ) {
		echo '<div class="star-rating-wrapper flex items-center mt-2">';
		echo wc_get_rating_html( $average, $rating_count );
		echo '<span class="rating-count text-xs text-gray-600 dark:text-gray-400 ml-2">(' . esc_html( $rating_count ) . ')</span>';
		echo '</div>';
	}
}

/**
 * Customize shop page title
 */
function aqualuxe_woocommerce_shop_page_title( $title ) {
	if ( is_shop() ) {
		$shop_page_id = wc_get_page_id( 'shop' );
		$title = get_the_title( $shop_page_id );
	}
	
	return $title;
}
add_filter( 'woocommerce_page_title', 'aqualuxe_woocommerce_shop_page_title' );

/**
 * Customize result count and catalog ordering
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

add_action( 'woocommerce_before_shop_loop', 'aqualuxe_woocommerce_result_count_ordering', 20 );

function aqualuxe_woocommerce_result_count_ordering() {
	echo '<div class="shop-controls flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pb-4 border-b border-gray-200 dark:border-dark-700">';
	
	woocommerce_result_count();
	woocommerce_catalog_ordering();
	
	echo '</div>';
}

/**
 * Customize pagination
 */
function aqualuxe_woocommerce_pagination_args( $args ) {
	$args['prev_text'] = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>' . esc_html__( 'Previous', 'aqualuxe' );
	$args['next_text'] = esc_html__( 'Next', 'aqualuxe' ) . '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>';
	
	return $args;
}
add_filter( 'woocommerce_pagination_args', 'aqualuxe_woocommerce_pagination_args' );

/**
 * Customize single product image gallery
 */
function aqualuxe_woocommerce_single_product_image_gallery_classes( $classes ) {
	$classes[] = 'product-gallery';
	
	return $classes;
}
add_filter( 'woocommerce_single_product_image_gallery_classes', 'aqualuxe_woocommerce_single_product_image_gallery_classes' );

/**
 * Customize single product tabs
 */
function aqualuxe_woocommerce_product_tabs( $tabs ) {
	// Customize tab titles
	if ( isset( $tabs['description'] ) ) {
		$tabs['description']['title'] = esc_html__( 'Description', 'aqualuxe' );
	}
	
	if ( isset( $tabs['additional_information'] ) ) {
		$tabs['additional_information']['title'] = esc_html__( 'Specifications', 'aqualuxe' );
	}
	
	if ( isset( $tabs['reviews'] ) ) {
		$tabs['reviews']['title'] = esc_html__( 'Reviews', 'aqualuxe' );
	}
	
	// Add custom tabs if enabled
	if ( get_theme_mod( 'aqualuxe_enable_shipping_tab', true ) ) {
		$tabs['shipping'] = array(
			'title'    => esc_html__( 'Shipping & Returns', 'aqualuxe' ),
			'priority' => 30,
			'callback' => 'aqualuxe_woocommerce_shipping_tab_content',
		);
	}
	
	if ( get_theme_mod( 'aqualuxe_enable_care_tab', true ) ) {
		$tabs['care'] = array(
			'title'    => esc_html__( 'Care Instructions', 'aqualuxe' ),
			'priority' => 40,
			'callback' => 'aqualuxe_woocommerce_care_tab_content',
		);
	}
	
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs' );

/**
 * Shipping tab content
 */
function aqualuxe_woocommerce_shipping_tab_content() {
	$shipping_content = get_theme_mod( 'aqualuxe_shipping_tab_content', '' );
	
	if ( empty( $shipping_content ) ) {
		$shipping_content = '<h3>' . esc_html__( 'Shipping Information', 'aqualuxe' ) . '</h3>
		<p>' . esc_html__( 'We ship worldwide using trusted carriers. Most orders are processed within 24-48 hours.', 'aqualuxe' ) . '</p>
		<ul>
			<li>' . esc_html__( 'Standard Shipping: 3-5 business days', 'aqualuxe' ) . '</li>
			<li>' . esc_html__( 'Express Shipping: 1-2 business days', 'aqualuxe' ) . '</li>
		</ul>
		
		<h3>' . esc_html__( 'Returns & Exchanges', 'aqualuxe' ) . '</h3>
		<p>' . esc_html__( 'We want you to be completely satisfied with your purchase. If you are not satisfied, you may return most items within 30 days of delivery for a full refund.', 'aqualuxe' ) . '</p>';
	}
	
	echo wp_kses_post( wpautop( $shipping_content ) );
}

/**
 * Care tab content
 */
function aqualuxe_woocommerce_care_tab_content() {
	$care_content = get_theme_mod( 'aqualuxe_care_tab_content', '' );
	
	if ( empty( $care_content ) ) {
		$care_content = '<h3>' . esc_html__( 'Care Instructions', 'aqualuxe' ) . '</h3>
		<p>' . esc_html__( 'To ensure the longevity and health of your aquatic life, please follow these care instructions:', 'aqualuxe' ) . '</p>
		<ul>
			<li>' . esc_html__( 'Maintain proper water temperature between 75-80°F (24-27°C)', 'aqualuxe' ) . '</li>
			<li>' . esc_html__( 'Change 25% of the water weekly', 'aqualuxe' ) . '</li>
			<li>' . esc_html__( 'Test water parameters regularly', 'aqualuxe' ) . '</li>
			<li>' . esc_html__( 'Feed appropriate food in small amounts 1-2 times daily', 'aqualuxe' ) . '</li>
		</ul>';
	}
	
	echo wp_kses_post( wpautop( $care_content ) );
}

/**
 * Customize single product meta
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_meta', 40 );

function aqualuxe_woocommerce_template_single_meta() {
	global $product;
	
	echo '<div class="product_meta text-sm text-gray-600 dark:text-gray-400 mt-6 pt-6 border-t border-gray-200 dark:border-dark-700">';
	
	// SKU
	if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) {
		echo '<span class="sku_wrapper block mb-2">' . esc_html__( 'SKU:', 'aqualuxe' ) . ' <span class="sku">' . ( $product->get_sku() ? $product->get_sku() : esc_html__( 'N/A', 'aqualuxe' ) ) . '</span></span>';
	}
	
	// Categories
	echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in block mb-2">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'aqualuxe' ) . ' ', '</span>' );
	
	// Tags
	echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as block mb-2">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'aqualuxe' ) . ' ', '</span>' );
	
	echo '</div>';
}

/**
 * Add social sharing buttons to single product
 */
function aqualuxe_woocommerce_share() {
	if ( ! get_theme_mod( 'aqualuxe_enable_product_sharing', true ) ) {
		return;
	}
	
	global $product;
	
	echo '<div class="product-share mt-6 pt-6 border-t border-gray-200 dark:border-dark-700">';
	echo '<h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">' . esc_html__( 'Share This Product', 'aqualuxe' ) . '</h3>';
	echo '<div class="flex flex-wrap gap-2">';
	
	// Facebook
	echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . esc_url( get_permalink() ) . '" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-[#3b5998] text-white rounded-lg hover:bg-opacity-90 transition-colors duration-300">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">';
	echo '<path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>';
	echo '</svg>';
	echo esc_html__( 'Share', 'aqualuxe' );
	echo '</a>';
	
	// Twitter
	echo '<a href="https://twitter.com/intent/tweet?text=' . esc_attr( $product->get_name() ) . '&url=' . esc_url( get_permalink() ) . '" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-[#1da1f2] text-white rounded-lg hover:bg-opacity-90 transition-colors duration-300">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">';
	echo '<path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>';
	echo '</svg>';
	echo esc_html__( 'Tweet', 'aqualuxe' );
	echo '</a>';
	
	// Pinterest
	echo '<a href="https://pinterest.com/pin/create/button/?url=' . esc_url( get_permalink() ) . '&media=' . esc_url( wp_get_attachment_url( $product->get_image_id() ) ) . '&description=' . esc_attr( $product->get_name() ) . '" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-[#bd081c] text-white rounded-lg hover:bg-opacity-90 transition-colors duration-300">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">';
	echo '<path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/>';
	echo '</svg>';
	echo esc_html__( 'Pin it', 'aqualuxe' );
	echo '</a>';
	
	// Email
	echo '<a href="mailto:?subject=' . esc_attr( $product->get_name() ) . '&body=' . esc_url( get_permalink() ) . '" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-opacity-90 transition-colors duration-300">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
	echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />';
	echo '</svg>';
	echo esc_html__( 'Email', 'aqualuxe' );
	echo '</a>';
	
	echo '</div>';
	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_share', 50 );

/**
 * Add product features to single product
 */
function aqualuxe_woocommerce_product_features() {
	if ( ! get_theme_mod( 'aqualuxe_enable_product_features', true ) ) {
		return;
	}
	
	$features = get_theme_mod( 'aqualuxe_product_features', '' );
	
	if ( empty( $features ) ) {
		$features = array(
			array(
				'icon'  => 'truck',
				'title' => esc_html__( 'Free Shipping', 'aqualuxe' ),
				'text'  => esc_html__( 'On orders over $50', 'aqualuxe' ),
			),
			array(
				'icon'  => 'shield-check',
				'title' => esc_html__( 'Guarantee', 'aqualuxe' ),
				'text'  => esc_html__( '30-day money back', 'aqualuxe' ),
			),
			array(
				'icon'  => 'refresh',
				'title' => esc_html__( 'Easy Returns', 'aqualuxe' ),
				'text'  => esc_html__( 'Hassle-free process', 'aqualuxe' ),
			),
			array(
				'icon'  => 'support',
				'title' => esc_html__( 'Expert Support', 'aqualuxe' ),
				'text'  => esc_html__( '24/7 customer service', 'aqualuxe' ),
			),
		);
	}
	
	$icons = array(
		'truck'         => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>',
		'shield-check'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>',
		'refresh'       => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>',
		'support'       => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>',
		'check-circle'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
		'gift'          => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112.83 2.83l-2.83 2.83a2 2 0 01-2.83-2.83L12 6.5V3.83a2 2 0 112.83 2.83l-2.83 2.83a2 2 0 01-2.83-2.83L12 3.5V8zm0 0V4.5a2 2 0 10-4 0v4a2 2 0 104 0z" /></svg>',
		'credit-card'   => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>',
		'lightning-bolt' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>',
	);
	
	echo '<div class="product-features mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">';
	
	foreach ( $features as $feature ) {
		$icon_key = isset( $feature['icon'] ) ? $feature['icon'] : 'check-circle';
		$icon = isset( $icons[ $icon_key ] ) ? $icons[ $icon_key ] : $icons['check-circle'];
		
		echo '<div class="product-feature p-3 bg-gray-50 dark:bg-dark-800 rounded-lg text-center">';
		echo '<div class="feature-icon text-primary-600 dark:text-primary-400 mx-auto mb-2">' . $icon . '</div>';
		echo '<h4 class="feature-title text-sm font-medium text-gray-900 dark:text-gray-100">' . esc_html( $feature['title'] ) . '</h4>';
		echo '<p class="feature-text text-xs text-gray-600 dark:text-gray-400">' . esc_html( $feature['text'] ) . '</p>';
		echo '</div>';
	}
	
	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_features', 25 );

/**
 * Customize checkout fields
 */
function aqualuxe_woocommerce_checkout_fields( $fields ) {
	// Add placeholder text
	if ( isset( $fields['billing']['billing_first_name'] ) ) {
		$fields['billing']['billing_first_name']['placeholder'] = esc_html__( 'First name', 'aqualuxe' );
	}
	
	if ( isset( $fields['billing']['billing_last_name'] ) ) {
		$fields['billing']['billing_last_name']['placeholder'] = esc_html__( 'Last name', 'aqualuxe' );
	}
	
	if ( isset( $fields['billing']['billing_company'] ) ) {
		$fields['billing']['billing_company']['placeholder'] = esc_html__( 'Company name (optional)', 'aqualuxe' );
	}
	
	if ( isset( $fields['billing']['billing_address_1'] ) ) {
		$fields['billing']['billing_address_1']['placeholder'] = esc_html__( 'Street address', 'aqualuxe' );
	}
	
	if ( isset( $fields['billing']['billing_address_2'] ) ) {
		$fields['billing']['billing_address_2']['placeholder'] = esc_html__( 'Apartment, suite, unit, etc. (optional)', 'aqualuxe' );
	}
	
	if ( isset( $fields['billing']['billing_city'] ) ) {
		$fields['billing']['billing_city']['placeholder'] = esc_html__( 'City', 'aqualuxe' );
	}
	
	if ( isset( $fields['billing']['billing_postcode'] ) ) {
		$fields['billing']['billing_postcode']['placeholder'] = esc_html__( 'Postcode / ZIP', 'aqualuxe' );
	}
	
	if ( isset( $fields['billing']['billing_phone'] ) ) {
		$fields['billing']['billing_phone']['placeholder'] = esc_html__( 'Phone', 'aqualuxe' );
	}
	
	if ( isset( $fields['billing']['billing_email'] ) ) {
		$fields['billing']['billing_email']['placeholder'] = esc_html__( 'Email address', 'aqualuxe' );
	}
	
	// Same for shipping fields
	if ( isset( $fields['shipping']['shipping_first_name'] ) ) {
		$fields['shipping']['shipping_first_name']['placeholder'] = esc_html__( 'First name', 'aqualuxe' );
	}
	
	if ( isset( $fields['shipping']['shipping_last_name'] ) ) {
		$fields['shipping']['shipping_last_name']['placeholder'] = esc_html__( 'Last name', 'aqualuxe' );
	}
	
	if ( isset( $fields['shipping']['shipping_company'] ) ) {
		$fields['shipping']['shipping_company']['placeholder'] = esc_html__( 'Company name (optional)', 'aqualuxe' );
	}
	
	if ( isset( $fields['shipping']['shipping_address_1'] ) ) {
		$fields['shipping']['shipping_address_1']['placeholder'] = esc_html__( 'Street address', 'aqualuxe' );
	}
	
	if ( isset( $fields['shipping']['shipping_address_2'] ) ) {
		$fields['shipping']['shipping_address_2']['placeholder'] = esc_html__( 'Apartment, suite, unit, etc. (optional)', 'aqualuxe' );
	}
	
	if ( isset( $fields['shipping']['shipping_city'] ) ) {
		$fields['shipping']['shipping_city']['placeholder'] = esc_html__( 'City', 'aqualuxe' );
	}
	
	if ( isset( $fields['shipping']['shipping_postcode'] ) ) {
		$fields['shipping']['shipping_postcode']['placeholder'] = esc_html__( 'Postcode / ZIP', 'aqualuxe' );
	}
	
	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'aqualuxe_woocommerce_checkout_fields' );

/**
 * Add custom CSS for WooCommerce
 */
function aqualuxe_woocommerce_custom_css() {
	?>
	<style>
		/* Product card styles */
		.woocommerce ul.products li.product {
			margin-bottom: 2rem;
		}
		
		/* Star rating styles */
		.woocommerce .star-rating span::before {
			color: var(--color-accent, #eab308);
		}
		
		/* Sale badge styles */
		.woocommerce span.onsale {
			min-height: auto;
			min-width: auto;
			padding: 0.25rem 0.5rem;
			font-size: 0.75rem;
			font-weight: 700;
			line-height: 1.5;
			top: 0.5rem;
			left: 0.5rem;
			background-color: var(--color-accent, #eab308);
			border-radius: 0.25rem;
		}
		
		/* Button styles */
		.woocommerce #respond input#submit,
		.woocommerce a.button,
		.woocommerce button.button,
		.woocommerce input.button {
			background-color: var(--color-primary, #0ea5e9);
			color: white;
			font-weight: 500;
			border-radius: 0.375rem;
			padding: 0.5rem 1rem;
			transition: background-color 0.3s;
		}
		
		.woocommerce #respond input#submit:hover,
		.woocommerce a.button:hover,
		.woocommerce button.button:hover,
		.woocommerce input.button:hover {
			background-color: var(--color-primary-dark, #0284c7);
			color: white;
		}
		
		.woocommerce #respond input#submit.alt,
		.woocommerce a.button.alt,
		.woocommerce button.button.alt,
		.woocommerce input.button.alt {
			background-color: var(--color-primary, #0ea5e9);
		}
		
		.woocommerce #respond input#submit.alt:hover,
		.woocommerce a.button.alt:hover,
		.woocommerce button.button.alt:hover,
		.woocommerce input.button.alt:hover {
			background-color: var(--color-primary-dark, #0284c7);
		}
		
		/* Pagination styles */
		.woocommerce nav.woocommerce-pagination ul {
			border: none;
		}
		
		.woocommerce nav.woocommerce-pagination ul li {
			border-right: none;
			margin: 0 0.25rem;
		}
		
		.woocommerce nav.woocommerce-pagination ul li a,
		.woocommerce nav.woocommerce-pagination ul li span {
			padding: 0.5rem 1rem;
			border-radius: 0.375rem;
			background-color: white;
		}
		
		.woocommerce nav.woocommerce-pagination ul li a:focus,
		.woocommerce nav.woocommerce-pagination ul li a:hover,
		.woocommerce nav.woocommerce-pagination ul li span.current {
			background-color: var(--color-primary, #0ea5e9);
			color: white;
		}
		
		/* Form field styles */
		.woocommerce form .form-row input.input-text,
		.woocommerce form .form-row textarea {
			padding: 0.75rem 1rem;
			border: 1px solid #d1d5db;
			border-radius: 0.375rem;
		}
		
		/* Dark mode styles */
		.dark .woocommerce form .form-row input.input-text,
		.dark .woocommerce form .form-row textarea {
			background-color: #1f2937;
			border-color: #374151;
			color: #e5e7eb;
		}
		
		.dark .woocommerce-message,
		.dark .woocommerce-info,
		.dark .woocommerce-error {
			background-color: #1f2937;
			color: #e5e7eb;
		}
		
		.dark .woocommerce div.product .woocommerce-tabs ul.tabs li {
			background-color: #1f2937;
			border-color: #374151;
		}
		
		.dark .woocommerce div.product .woocommerce-tabs ul.tabs li.active {
			background-color: #111827;
			border-bottom-color: #111827;
		}
		
		.dark .woocommerce div.product .woocommerce-tabs .panel {
			background-color: #111827;
			color: #e5e7eb;
		}
		
		/* Mini cart styles */
		.mini-cart-dropdown {
			width: 320px;
			padding: 1rem;
		}
		
		.mini-cart-dropdown .woocommerce-mini-cart__empty-message {
			padding: 1rem 0;
			text-align: center;
		}
		
		.mini-cart-dropdown .woocommerce-mini-cart {
			max-height: 300px;
			overflow-y: auto;
		}
		
		.mini-cart-dropdown .woocommerce-mini-cart-item {
			padding: 0.75rem 0;
			border-bottom: 1px solid #e5e7eb;
		}
		
		.dark .mini-cart-dropdown .woocommerce-mini-cart-item {
			border-bottom-color: #374151;
		}
		
		.mini-cart-dropdown .woocommerce-mini-cart__total {
			padding: 0.75rem 0;
			margin: 0.75rem 0;
			border-top: 1px solid #e5e7eb;
			border-bottom: 1px solid #e5e7eb;
			display: flex;
			justify-content: space-between;
		}
		
		.dark .mini-cart-dropdown .woocommerce-mini-cart__total {
			border-color: #374151;
		}
		
		.mini-cart-dropdown .woocommerce-mini-cart__buttons {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 0.75rem;
		}
		
		.mini-cart-dropdown .woocommerce-mini-cart__buttons a {
			margin: 0 !important;
			text-align: center;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'aqualuxe_woocommerce_custom_css' );