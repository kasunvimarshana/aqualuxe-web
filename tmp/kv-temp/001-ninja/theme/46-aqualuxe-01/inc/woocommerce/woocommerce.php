<?php
/**
 * WooCommerce compatibility file
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WooCommerce setup function.
 */
function aqualuxe_woocommerce_setup() {
	// Add theme support for WooCommerce
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width'         => 400,
		'single_image_width'            => 800,
		'product_grid'                  => array(
			'default_rows'    => 3,
			'min_rows'        => 1,
			'max_rows'        => 8,
			'default_columns' => 4,
			'min_columns'     => 2,
			'max_columns'     => 5,
		),
	) );

	// Add support for WooCommerce features
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Load WooCommerce specific template functions
	require_once AQUALUXE_DIR . 'inc/woocommerce/template-functions.php';
	require_once AQUALUXE_DIR . 'inc/woocommerce/template-hooks.php';

	// Load WooCommerce specific features
	require_once AQUALUXE_DIR . 'inc/woocommerce/quick-view.php';
	require_once AQUALUXE_DIR . 'inc/woocommerce/wishlist.php';
	require_once AQUALUXE_DIR . 'inc/woocommerce/advanced-filters.php';
	require_once AQUALUXE_DIR . 'inc/woocommerce/multi-currency.php';
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );

/**
 * Set the number of products per page
 *
 * @param int $products_per_page Number of products per page
 * @return int Modified number of products per page
 */
function aqualuxe_woocommerce_products_per_page( $products_per_page ) {
	return get_theme_mod( 'aqualuxe_products_per_page', 12 );
}
add_filter( 'loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page' );

/**
 * Set the number of product columns
 *
 * @param int $columns Number of columns
 * @return int Modified number of columns
 */
function aqualuxe_woocommerce_loop_columns( $columns ) {
	return get_theme_mod( 'aqualuxe_product_columns', 4 );
}
add_filter( 'loop_shop_columns', 'aqualuxe_woocommerce_loop_columns' );

/**
 * Related products arguments
 *
 * @param array $args Related products args
 * @return array Modified related products args
 */
function aqualuxe_woocommerce_related_products_args( $args ) {
	$columns = get_theme_mod( 'aqualuxe_product_columns', 4 );

	$args = array(
		'posts_per_page' => $columns,
		'columns'        => $columns,
	);

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args' );

/**
 * Cross-sell products columns
 *
 * @param int $columns Number of columns
 * @return int Modified number of columns
 */
function aqualuxe_woocommerce_cross_sells_columns( $columns ) {
	return 2;
}
add_filter( 'woocommerce_cross_sells_columns', 'aqualuxe_woocommerce_cross_sells_columns' );

/**
 * Cross-sell products limit
 *
 * @param int $limit Number of cross-sells
 * @return int Modified number of cross-sells
 */
function aqualuxe_woocommerce_cross_sells_limit( $limit ) {
	return 2;
}
add_filter( 'woocommerce_cross_sells_total', 'aqualuxe_woocommerce_cross_sells_limit' );

/**
 * Remove default WooCommerce wrapper
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Add custom WooCommerce wrapper
 */
function aqualuxe_woocommerce_wrapper_before() {
	?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
	<?php
}
add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before' );

/**
 * Add custom WooCommerce wrapper end
 */
function aqualuxe_woocommerce_wrapper_after() {
	?>
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php
}
add_action( 'woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after' );

/**
 * Add sidebar to WooCommerce pages
 */
function aqualuxe_woocommerce_sidebar() {
	$sidebar_position = get_theme_mod( 'aqualuxe_shop_sidebar_position', 'left' );

	if ( 'none' !== $sidebar_position && is_active_sidebar( 'shop-sidebar' ) ) {
		?>
		<aside id="secondary" class="widget-area shop-sidebar" role="complementary">
			<?php dynamic_sidebar( 'shop-sidebar' ); ?>
		</aside><!-- #secondary -->
		<?php
	}
}
add_action( 'woocommerce_sidebar', 'aqualuxe_woocommerce_sidebar' );

/**
 * Add body classes for WooCommerce pages
 *
 * @param array $classes Body classes
 * @return array Modified body classes
 */
function aqualuxe_woocommerce_body_classes( $classes ) {
	if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
		$sidebar_position = get_theme_mod( 'aqualuxe_shop_sidebar_position', 'left' );
		
		if ( 'left' === $sidebar_position ) {
			$classes[] = 'woocommerce-sidebar-left';
		} elseif ( 'right' === $sidebar_position ) {
			$classes[] = 'woocommerce-sidebar-right';
		} else {
			$classes[] = 'woocommerce-no-sidebar';
		}
	}

	return $classes;
}
add_filter( 'body_class', 'aqualuxe_woocommerce_body_classes' );

/**
 * Disable WooCommerce styles
 *
 * @param array $styles WooCommerce styles
 * @return array Modified WooCommerce styles
 */
function aqualuxe_woocommerce_styles( $styles ) {
	// Remove the default WooCommerce stylesheet
	unset( $styles['woocommerce-general'] );
	
	return $styles;
}
add_filter( 'woocommerce_enqueue_styles', 'aqualuxe_woocommerce_styles' );

/**
 * Add 'woocommerce-active' class to the body tag
 *
 * @param array $classes Body classes
 * @return array Modified body classes
 */
function aqualuxe_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';
	
	return $classes;
}
add_filter( 'body_class', 'aqualuxe_woocommerce_active_body_class' );

/**
 * Products per row
 *
 * @return int Number of products per row
 */
function aqualuxe_woocommerce_loop_columns() {
	return get_theme_mod( 'aqualuxe_product_columns', 4 );
}
add_filter( 'loop_shop_columns', 'aqualuxe_woocommerce_loop_columns' );

/**
 * Add custom image sizes for products
 */
function aqualuxe_woocommerce_image_dimensions() {
	global $pagenow;

	if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
		return;
	}

	$catalog = array(
		'width'  => '400',
		'height' => '400',
		'crop'   => 1,
	);
	$single = array(
		'width'  => '800',
		'height' => '800',
		'crop'   => 1,
	);
	$thumbnail = array(
		'width'  => '120',
		'height' => '120',
		'crop'   => 1,
	);

	// Update image sizes
	update_option( 'shop_catalog_image_size', $catalog );
	update_option( 'shop_single_image_size', $single );
	update_option( 'shop_thumbnail_image_size', $thumbnail );
}
add_action( 'after_switch_theme', 'aqualuxe_woocommerce_image_dimensions', 1 );

/**
 * Change number of related products
 *
 * @param array $args Related products args
 * @return array Modified related products args
 */
function aqualuxe_related_products_args( $args ) {
	$columns = get_theme_mod( 'aqualuxe_product_columns', 4 );
	
	$args['posts_per_page'] = $columns;
	$args['columns'] = $columns;
	
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_related_products_args' );

/**
 * Remove related products if disabled in customizer
 */
function aqualuxe_remove_related_products() {
	if ( ! get_theme_mod( 'aqualuxe_related_products', true ) ) {
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	}
}
add_action( 'wp', 'aqualuxe_remove_related_products' );

/**
 * Remove cross-sells from default position
 */
function aqualuxe_remove_cross_sells_from_default() {
	if ( ! get_theme_mod( 'aqualuxe_cart_cross_sells', true ) ) {
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	}
}
add_action( 'init', 'aqualuxe_remove_cross_sells_from_default' );

/**
 * Add cross-sells after cart table
 */
function aqualuxe_add_cross_sells_after_cart_table() {
	if ( get_theme_mod( 'aqualuxe_cart_cross_sells', true ) ) {
		woocommerce_cross_sell_display();
	}
}
add_action( 'woocommerce_after_cart_table', 'aqualuxe_add_cross_sells_after_cart_table' );

/**
 * Add product gallery support
 */
function aqualuxe_woocommerce_gallery_support() {
	if ( ! get_theme_mod( 'aqualuxe_product_zoom', true ) ) {
		remove_theme_support( 'wc-product-gallery-zoom' );
	}
	
	if ( ! get_theme_mod( 'aqualuxe_product_lightbox', true ) ) {
		remove_theme_support( 'wc-product-gallery-lightbox' );
	}
	
	if ( ! get_theme_mod( 'aqualuxe_product_slider', true ) ) {
		remove_theme_support( 'wc-product-gallery-slider' );
	}
}
add_action( 'wp', 'aqualuxe_woocommerce_gallery_support' );

/**
 * Add social sharing to product pages
 */
function aqualuxe_woocommerce_share() {
	if ( function_exists( 'aqualuxe_social_sharing' ) && is_product() ) {
		aqualuxe_social_sharing();
	}
}
add_action( 'woocommerce_share', 'aqualuxe_woocommerce_share' );

/**
 * Add breadcrumbs to shop pages
 */
function aqualuxe_woocommerce_breadcrumbs() {
	if ( function_exists( 'aqualuxe_breadcrumbs' ) && ( is_shop() || is_product_category() || is_product_tag() || is_product() ) ) {
		aqualuxe_breadcrumbs();
	}
}
add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_breadcrumbs', 20 );

/**
 * Add currency switcher to header
 */
function aqualuxe_woocommerce_header_currency_switcher() {
	if ( get_theme_mod( 'aqualuxe_header_currency_switcher', true ) && function_exists( 'aqualuxe_currency_switcher' ) ) {
		aqualuxe_currency_switcher();
	}
}
add_action( 'aqualuxe_header_extras', 'aqualuxe_woocommerce_header_currency_switcher', 20 );

/**
 * Add cart icon to header
 */
function aqualuxe_woocommerce_header_cart() {
	if ( is_woocommerce_activated() ) {
		?>
		<div class="site-header-cart">
			<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
				<span class="cart-contents-count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
				<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
			</a>
			<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
		</div>
		<?php
	}
}
add_action( 'aqualuxe_header_extras', 'aqualuxe_woocommerce_header_cart', 30 );

/**
 * Update cart contents count and subtotal via AJAX
 */
function aqualuxe_woocommerce_header_add_to_cart_fragment( $fragments ) {
	ob_start();
	?>
	<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
		<span class="cart-contents-count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
		<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
	</a>
	<?php
	$fragments['a.cart-contents'] = ob_get_clean();
	
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_header_add_to_cart_fragment' );

/**
 * Add account icon to header
 */
function aqualuxe_woocommerce_header_account() {
	if ( is_woocommerce_activated() ) {
		?>
		<div class="site-header-account">
			<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" title="<?php esc_attr_e( 'My Account', 'aqualuxe' ); ?>">
				<svg class="icon icon-user" aria-hidden="true" focusable="false"><use xlink:href="#icon-user"></use></svg>
				<span class="screen-reader-text"><?php esc_html_e( 'My Account', 'aqualuxe' ); ?></span>
			</a>
		</div>
		<?php
	}
}
add_action( 'aqualuxe_header_extras', 'aqualuxe_woocommerce_header_account', 40 );

/**
 * Add wishlist icon to header
 */
function aqualuxe_woocommerce_header_wishlist() {
	if ( is_woocommerce_activated() && get_theme_mod( 'aqualuxe_wishlist', true ) && function_exists( 'aqualuxe_get_wishlist_url' ) ) {
		?>
		<div class="site-header-wishlist">
			<a href="<?php echo esc_url( aqualuxe_get_wishlist_url() ); ?>" title="<?php esc_attr_e( 'Wishlist', 'aqualuxe' ); ?>">
				<svg class="icon icon-heart" aria-hidden="true" focusable="false"><use xlink:href="#icon-heart"></use></svg>
				<span class="screen-reader-text"><?php esc_html_e( 'Wishlist', 'aqualuxe' ); ?></span>
				<?php if ( function_exists( 'aqualuxe_get_wishlist_count' ) ) : ?>
					<span class="wishlist-count"><?php echo esc_html( aqualuxe_get_wishlist_count() ); ?></span>
				<?php endif; ?>
			</a>
		</div>
		<?php
	}
}
add_action( 'aqualuxe_header_extras', 'aqualuxe_woocommerce_header_wishlist', 50 );

/**
 * Check if WooCommerce is activated
 *
 * @return bool
 */
function is_woocommerce_activated() {
	return class_exists( 'WooCommerce' );
}

/**
 * Add fallback for WooCommerce functions when WooCommerce is not active
 */
if ( ! is_woocommerce_activated() ) {
	/**
	 * Fallback for wc_get_cart_url
	 *
	 * @return string
	 */
	function wc_get_cart_url() {
		return '#';
	}

	/**
	 * Fallback for wc_get_page_permalink
	 *
	 * @param string $page Page slug
	 * @return string
	 */
	function wc_get_page_permalink( $page ) {
		return '#';
	}

	/**
	 * Fallback for is_shop
	 *
	 * @return bool
	 */
	function is_shop() {
		return false;
	}

	/**
	 * Fallback for is_product_category
	 *
	 * @return bool
	 */
	function is_product_category() {
		return false;
	}

	/**
	 * Fallback for is_product_tag
	 *
	 * @return bool
	 */
	function is_product_tag() {
		return false;
	}

	/**
	 * Fallback for is_product
	 *
	 * @return bool
	 */
	function is_product() {
		return false;
	}

	/**
	 * Fallback for is_cart
	 *
	 * @return bool
	 */
	function is_cart() {
		return false;
	}

	/**
	 * Fallback for is_checkout
	 *
	 * @return bool
	 */
	function is_checkout() {
		return false;
	}

	/**
	 * Fallback for is_account_page
	 *
	 * @return bool
	 */
	function is_account_page() {
		return false;
	}

	/**
	 * Fallback for is_woocommerce
	 *
	 * @return bool
	 */
	function is_woocommerce() {
		return false;
	}
}