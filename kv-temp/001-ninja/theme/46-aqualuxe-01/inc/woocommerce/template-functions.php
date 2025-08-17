<?php
/**
 * WooCommerce template functions
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Display product categories as a list
 */
function aqualuxe_woocommerce_product_categories() {
	global $product;

	if ( ! $product ) {
		return;
	}

	$categories = wc_get_product_category_list( $product->get_id(), ', ', '<div class="product-categories">', '</div>' );

	if ( $categories ) {
		echo wp_kses_post( $categories );
	}
}

/**
 * Display product tags as a list
 */
function aqualuxe_woocommerce_product_tags() {
	global $product;

	if ( ! $product ) {
		return;
	}

	$tags = wc_get_product_tag_list( $product->get_id(), ', ', '<div class="product-tags">', '</div>' );

	if ( $tags ) {
		echo wp_kses_post( $tags );
	}
}

/**
 * Display product SKU
 */
function aqualuxe_woocommerce_product_sku() {
	global $product;

	if ( ! $product || ! $product->get_sku() ) {
		return;
	}

	echo '<div class="product-sku">' . esc_html__( 'SKU:', 'aqualuxe' ) . ' <span>' . esc_html( $product->get_sku() ) . '</span></div>';
}

/**
 * Display product stock status
 */
function aqualuxe_woocommerce_product_stock_status() {
	global $product;

	if ( ! $product ) {
		return;
	}

	$availability = $product->get_availability();
	$stock_status = $availability['class'] ?? '';
	$stock_label = $availability['availability'] ?? '';

	if ( ! $stock_label ) {
		if ( $product->is_in_stock() ) {
			$stock_label = __( 'In stock', 'aqualuxe' );
		} else {
			$stock_label = __( 'Out of stock', 'aqualuxe' );
		}
	}

	echo '<div class="product-stock-status stock-status-' . esc_attr( $stock_status ) . '">';
	echo esc_html( $stock_label );
	echo '</div>';
}

/**
 * Display product dimensions
 */
function aqualuxe_woocommerce_product_dimensions() {
	global $product;

	if ( ! $product || ! $product->has_dimensions() ) {
		return;
	}

	echo '<div class="product-dimensions">';
	echo '<span class="label">' . esc_html__( 'Dimensions:', 'aqualuxe' ) . '</span> ';
	echo '<span class="value">' . esc_html( wc_format_dimensions( $product->get_dimensions( false ) ) ) . '</span>';
	echo '</div>';
}

/**
 * Display product weight
 */
function aqualuxe_woocommerce_product_weight() {
	global $product;

	if ( ! $product || ! $product->has_weight() ) {
		return;
	}

	echo '<div class="product-weight">';
	echo '<span class="label">' . esc_html__( 'Weight:', 'aqualuxe' ) . '</span> ';
	echo '<span class="value">' . esc_html( $product->get_weight() ) . ' ' . esc_html( get_option( 'woocommerce_weight_unit' ) ) . '</span>';
	echo '</div>';
}

/**
 * Display product attributes
 */
function aqualuxe_woocommerce_product_attributes() {
	global $product;

	if ( ! $product ) {
		return;
	}

	$attributes = $product->get_attributes();

	if ( empty( $attributes ) ) {
		return;
	}

	echo '<div class="product-attributes">';
	echo '<h3>' . esc_html__( 'Additional Information', 'aqualuxe' ) . '</h3>';
	echo '<table class="woocommerce-product-attributes shop_attributes">';

	foreach ( $attributes as $attribute ) {
		if ( ! $attribute->get_visible() ) {
			continue;
		}

		echo '<tr>';
		echo '<th>' . wp_kses_post( $attribute->get_name() ) . '</th>';
		echo '<td>';

		if ( $attribute->is_taxonomy() ) {
			$values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
			echo wp_kses_post( apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values ) );
		} else {
			$values = $attribute->get_options();
			echo wp_kses_post( apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values ) );
		}

		echo '</td>';
		echo '</tr>';
	}

	echo '</table>';
	echo '</div>';
}

/**
 * Display product meta information
 */
function aqualuxe_woocommerce_product_meta() {
	global $product;

	if ( ! $product ) {
		return;
	}

	echo '<div class="product-meta">';
	
	// SKU
	aqualuxe_woocommerce_product_sku();
	
	// Categories
	aqualuxe_woocommerce_product_categories();
	
	// Tags
	aqualuxe_woocommerce_product_tags();
	
	echo '</div>';
}

/**
 * Display product specifications
 */
function aqualuxe_woocommerce_product_specifications() {
	global $product;

	if ( ! $product ) {
		return;
	}

	echo '<div class="product-specifications">';
	echo '<h3>' . esc_html__( 'Specifications', 'aqualuxe' ) . '</h3>';
	echo '<div class="specifications-content">';
	
	// Dimensions
	aqualuxe_woocommerce_product_dimensions();
	
	// Weight
	aqualuxe_woocommerce_product_weight();
	
	// Attributes
	aqualuxe_woocommerce_product_attributes();
	
	echo '</div>';
	echo '</div>';
}

/**
 * Display product short description
 */
function aqualuxe_woocommerce_product_short_description() {
	global $product;

	if ( ! $product || ! $product->get_short_description() ) {
		return;
	}

	echo '<div class="product-short-description">';
	echo wp_kses_post( $product->get_short_description() );
	echo '</div>';
}

/**
 * Display product rating with text
 */
function aqualuxe_woocommerce_product_rating() {
	global $product;

	if ( ! $product || ! wc_review_ratings_enabled() ) {
		return;
	}

	$rating_count = $product->get_rating_count();
	$review_count = $product->get_review_count();
	$average = $product->get_average_rating();

	if ( $rating_count > 0 ) {
		echo '<div class="product-rating">';
		echo wc_get_rating_html( $average, $rating_count );
		
		if ( $review_count > 0 ) {
			echo '<span class="rating-count">';
			/* translators: %s: review count */
			echo sprintf( _n( '(%s review)', '(%s reviews)', $review_count, 'aqualuxe' ), esc_html( $review_count ) );
			echo '</span>';
		}
		
		echo '</div>';
	} else {
		echo '<div class="product-rating no-rating">';
		echo '<span class="rating-count">' . esc_html__( 'No reviews yet', 'aqualuxe' ) . '</span>';
		echo '</div>';
	}
}

/**
 * Display product price with sale badge
 */
function aqualuxe_woocommerce_product_price() {
	global $product;

	if ( ! $product ) {
		return;
	}

	echo '<div class="product-price">';
	
	// Sale badge
	if ( $product->is_on_sale() ) {
		echo '<span class="onsale">' . esc_html__( 'Sale!', 'aqualuxe' ) . '</span>';
	}
	
	// Price
	echo wp_kses_post( $product->get_price_html() );
	
	echo '</div>';
}

/**
 * Display product availability
 */
function aqualuxe_woocommerce_product_availability() {
	global $product;

	if ( ! $product ) {
		return;
	}

	echo '<div class="product-availability">';
	
	// Stock status
	aqualuxe_woocommerce_product_stock_status();
	
	// Backorder notification
	if ( $product->is_on_backorder() ) {
		echo '<div class="backorder-notification">' . esc_html__( 'Available on backorder', 'aqualuxe' ) . '</div>';
	}
	
	echo '</div>';
}

/**
 * Display product quantity and add to cart button
 */
function aqualuxe_woocommerce_product_add_to_cart() {
	global $product;

	if ( ! $product ) {
		return;
	}

	echo '<div class="product-add-to-cart">';
	woocommerce_template_single_add_to_cart();
	echo '</div>';
}

/**
 * Display product wishlist button
 */
function aqualuxe_woocommerce_product_wishlist() {
	if ( ! get_theme_mod( 'aqualuxe_wishlist', true ) || ! function_exists( 'aqualuxe_wishlist_button' ) ) {
		return;
	}

	echo '<div class="product-wishlist">';
	aqualuxe_wishlist_button();
	echo '</div>';
}

/**
 * Display product sharing buttons
 */
function aqualuxe_woocommerce_product_sharing() {
	if ( ! get_theme_mod( 'aqualuxe_display_social_sharing', true ) || ! function_exists( 'aqualuxe_social_sharing' ) ) {
		return;
	}

	echo '<div class="product-sharing">';
	aqualuxe_social_sharing();
	echo '</div>';
}

/**
 * Display product tabs
 */
function aqualuxe_woocommerce_product_tabs() {
	woocommerce_output_product_data_tabs();
}

/**
 * Display related products
 */
function aqualuxe_woocommerce_related_products() {
	if ( ! get_theme_mod( 'aqualuxe_related_products', true ) ) {
		return;
	}

	woocommerce_output_related_products();
}

/**
 * Display upsell products
 */
function aqualuxe_woocommerce_upsell_products() {
	woocommerce_upsell_display();
}

/**
 * Display cross-sell products
 */
function aqualuxe_woocommerce_cross_sell_products() {
	if ( ! get_theme_mod( 'aqualuxe_cart_cross_sells', true ) ) {
		return;
	}

	woocommerce_cross_sell_display();
}

/**
 * Display product category image
 */
function aqualuxe_woocommerce_category_image() {
	if ( is_product_category() ) {
		$category = get_queried_object();
		$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
		
		if ( $thumbnail_id ) {
			$image = wp_get_attachment_image_src( $thumbnail_id, 'full' );
			
			if ( $image ) {
				echo '<div class="category-image">';
				echo '<img src="' . esc_url( $image[0] ) . '" alt="' . esc_attr( $category->name ) . '" />';
				echo '</div>';
			}
		}
	}
}

/**
 * Display product category description
 */
function aqualuxe_woocommerce_category_description() {
	if ( is_product_category() ) {
		$category = get_queried_object();
		
		if ( $category && ! empty( $category->description ) ) {
			echo '<div class="category-description">';
			echo wp_kses_post( wpautop( $category->description ) );
			echo '</div>';
		}
	}
}

/**
 * Display shop page title
 */
function aqualuxe_woocommerce_shop_title() {
	if ( is_shop() ) {
		$shop_page_id = wc_get_page_id( 'shop' );
		$shop_title = get_the_title( $shop_page_id );
		
		echo '<h1 class="woocommerce-products-header__title page-title">' . esc_html( $shop_title ) . '</h1>';
	} elseif ( is_product_category() || is_product_tag() ) {
		woocommerce_page_title();
	}
}

/**
 * Display shop page description
 */
function aqualuxe_woocommerce_shop_description() {
	if ( is_shop() ) {
		$shop_page_id = wc_get_page_id( 'shop' );
		$shop_content = get_post_field( 'post_content', $shop_page_id );
		
		if ( ! empty( $shop_content ) ) {
			echo '<div class="page-description">';
			echo wp_kses_post( wpautop( $shop_content ) );
			echo '</div>';
		}
	}
}

/**
 * Display product loop item title
 */
function aqualuxe_woocommerce_template_loop_product_title() {
	echo '<h2 class="woocommerce-loop-product__title">' . get_the_title() . '</h2>';
}

/**
 * Display product loop item rating
 */
function aqualuxe_woocommerce_template_loop_rating() {
	global $product;

	if ( ! $product || ! wc_review_ratings_enabled() ) {
		return;
	}

	$rating_count = $product->get_rating_count();
	$average = $product->get_average_rating();

	if ( $rating_count > 0 ) {
		echo wc_get_rating_html( $average, $rating_count );
	} else {
		echo '<div class="star-rating"></div>';
	}
}

/**
 * Display product loop item price
 */
function aqualuxe_woocommerce_template_loop_price() {
	global $product;

	if ( ! $product ) {
		return;
	}

	echo '<span class="price">' . wp_kses_post( $product->get_price_html() ) . '</span>';
}

/**
 * Display product loop item add to cart button
 */
function aqualuxe_woocommerce_template_loop_add_to_cart() {
	global $product;

	if ( ! $product ) {
		return;
	}

	echo '<div class="loop-add-to-cart">';
	woocommerce_template_loop_add_to_cart();
	echo '</div>';
}

/**
 * Display product loop item quick view button
 */
function aqualuxe_woocommerce_template_loop_quick_view() {
	global $product;

	if ( ! $product || ! get_theme_mod( 'aqualuxe_quick_view', true ) || ! function_exists( 'aqualuxe_quick_view_button' ) ) {
		return;
	}

	echo '<div class="loop-quick-view">';
	aqualuxe_quick_view_button();
	echo '</div>';
}

/**
 * Display product loop item wishlist button
 */
function aqualuxe_woocommerce_template_loop_wishlist() {
	global $product;

	if ( ! $product || ! get_theme_mod( 'aqualuxe_wishlist', true ) || ! function_exists( 'aqualuxe_wishlist_button' ) ) {
		return;
	}

	echo '<div class="loop-wishlist">';
	aqualuxe_wishlist_button();
	echo '</div>';
}

/**
 * Display product loop item actions
 */
function aqualuxe_woocommerce_template_loop_actions() {
	echo '<div class="loop-actions">';
	aqualuxe_woocommerce_template_loop_add_to_cart();
	aqualuxe_woocommerce_template_loop_quick_view();
	aqualuxe_woocommerce_template_loop_wishlist();
	echo '</div>';
}

/**
 * Display product loop item badges
 */
function aqualuxe_woocommerce_template_loop_badges() {
	global $product;

	if ( ! $product ) {
		return;
	}

	echo '<div class="product-badges">';
	
	// Sale badge
	if ( $product->is_on_sale() ) {
		echo '<span class="badge onsale">' . esc_html__( 'Sale!', 'aqualuxe' ) . '</span>';
	}
	
	// New badge (products less than 30 days old)
	$days_old = ( time() - get_the_time( 'U' ) ) / DAY_IN_SECONDS;
	
	if ( $days_old < 30 ) {
		echo '<span class="badge new">' . esc_html__( 'New!', 'aqualuxe' ) . '</span>';
	}
	
	// Out of stock badge
	if ( ! $product->is_in_stock() ) {
		echo '<span class="badge out-of-stock">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
	}
	
	// Featured badge
	if ( $product->is_featured() ) {
		echo '<span class="badge featured">' . esc_html__( 'Featured', 'aqualuxe' ) . '</span>';
	}
	
	echo '</div>';
}

/**
 * Display product loop item categories
 */
function aqualuxe_woocommerce_template_loop_categories() {
	global $product;

	if ( ! $product ) {
		return;
	}

	$categories = wc_get_product_category_list( $product->get_id(), ', ', '<div class="loop-product-categories">', '</div>' );

	if ( $categories ) {
		echo wp_kses_post( $categories );
	}
}

/**
 * Display product filters
 */
function aqualuxe_woocommerce_product_filters() {
	if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
		return;
	}

	echo '<div class="product-filters">';
	
	// Active filters
	the_widget( 'WC_Widget_Layered_Nav_Filters', array( 'title' => __( 'Active Filters', 'aqualuxe' ) ) );
	
	// Price filter
	the_widget( 'WC_Widget_Price_Filter', array( 'title' => __( 'Filter by Price', 'aqualuxe' ) ) );
	
	// Attribute filters
	$attribute_taxonomies = wc_get_attribute_taxonomies();
	
	if ( ! empty( $attribute_taxonomies ) ) {
		foreach ( $attribute_taxonomies as $attribute ) {
			the_widget( 'WC_Widget_Layered_Nav', array(
				'title'        => sprintf( __( 'Filter by %s', 'aqualuxe' ), $attribute->attribute_label ),
				'attribute'    => $attribute->attribute_name,
				'display_type' => 'list',
			) );
		}
	}
	
	echo '</div>';
}

/**
 * Display product sorting options
 */
function aqualuxe_woocommerce_catalog_ordering() {
	if ( ! woocommerce_products_will_display() ) {
		return;
	}

	$catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array(
		'menu_order' => __( 'Default sorting', 'aqualuxe' ),
		'popularity' => __( 'Sort by popularity', 'aqualuxe' ),
		'rating'     => __( 'Sort by average rating', 'aqualuxe' ),
		'date'       => __( 'Sort by latest', 'aqualuxe' ),
		'price'      => __( 'Sort by price: low to high', 'aqualuxe' ),
		'price-desc' => __( 'Sort by price: high to low', 'aqualuxe' ),
	) );

	$default_orderby = wc_get_loop_prop( 'is_search' ) ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );
	$orderby = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby;

	if ( wc_get_loop_prop( 'is_search' ) ) {
		$catalog_orderby_options = array_merge( array( 'relevance' => __( 'Relevance', 'aqualuxe' ) ), $catalog_orderby_options );
		unset( $catalog_orderby_options['menu_order'] );
	}

	if ( ! array_key_exists( $orderby, $catalog_orderby_options ) ) {
		$orderby = $default_orderby;
	}

	wc_get_template( 'loop/orderby.php', array(
		'catalog_orderby_options' => $catalog_orderby_options,
		'orderby'                 => $orderby,
		'show_default_orderby'    => apply_filters( 'woocommerce_show_default_orderby', true ),
	) );
}

/**
 * Display product result count
 */
function aqualuxe_woocommerce_result_count() {
	if ( ! woocommerce_products_will_display() ) {
		return;
	}

	$total = wc_get_loop_prop( 'total' );
	$per_page = wc_get_loop_prop( 'per_page' );
	$current = wc_get_loop_prop( 'current_page' );

	if ( $total <= $per_page || -1 === $per_page ) {
		/* translators: %d: total results */
		printf( _n( 'Showing all %d result', 'Showing all %d results', $total, 'aqualuxe' ), $total );
	} else {
		$first = ( $per_page * $current ) - $per_page + 1;
		$last = min( $total, $per_page * $current );
		/* translators: 1: first result 2: last result 3: total results */
		printf( _nx( 'Showing %1$d&ndash;%2$d of %3$d result', 'Showing %1$d&ndash;%2$d of %3$d results', $total, 'with first and last result', 'aqualuxe' ), $first, $last, $total );
	}
}

/**
 * Display product pagination
 */
function aqualuxe_woocommerce_pagination() {
	if ( ! woocommerce_products_will_display() ) {
		return;
	}

	$total = wc_get_loop_prop( 'total_pages' );
	$current = wc_get_loop_prop( 'current_page' );
	$base = esc_url_raw( add_query_arg( 'product-page', '%#%', false ) );
	$format = '?product-page=%#%';

	if ( $total <= 1 ) {
		return;
	}

	echo '<nav class="woocommerce-pagination">';
	echo paginate_links( apply_filters( 'woocommerce_pagination_args', array(
		'base'         => $base,
		'format'       => $format,
		'add_args'     => false,
		'current'      => max( 1, $current ),
		'total'        => $total,
		'prev_text'    => '&larr;',
		'next_text'    => '&rarr;',
		'type'         => 'list',
		'end_size'     => 3,
		'mid_size'     => 3,
	) ) );
	echo '</nav>';
}

/**
 * Display product view switcher (grid/list)
 */
function aqualuxe_woocommerce_view_switcher() {
	if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
		return;
	}

	$current_view = isset( $_COOKIE['aqualuxe_product_view'] ) ? sanitize_text_field( $_COOKIE['aqualuxe_product_view'] ) : 'grid';

	echo '<div class="view-switcher">';
	echo '<span class="view-title">' . esc_html__( 'View as:', 'aqualuxe' ) . '</span>';
	echo '<a href="#" class="view-grid ' . ( 'grid' === $current_view ? 'active' : '' ) . '" data-view="grid" title="' . esc_attr__( 'Grid View', 'aqualuxe' ) . '">';
	echo '<svg class="icon icon-grid" aria-hidden="true" focusable="false"><use xlink:href="#icon-grid"></use></svg>';
	echo '<span class="screen-reader-text">' . esc_html__( 'Grid View', 'aqualuxe' ) . '</span>';
	echo '</a>';
	echo '<a href="#" class="view-list ' . ( 'list' === $current_view ? 'active' : '' ) . '" data-view="list" title="' . esc_attr__( 'List View', 'aqualuxe' ) . '">';
	echo '<svg class="icon icon-list" aria-hidden="true" focusable="false"><use xlink:href="#icon-list"></use></svg>';
	echo '<span class="screen-reader-text">' . esc_html__( 'List View', 'aqualuxe' ) . '</span>';
	echo '</a>';
	echo '</div>';
}

/**
 * Display shop toolbar
 */
function aqualuxe_woocommerce_shop_toolbar() {
	if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
		return;
	}

	echo '<div class="shop-toolbar">';
	echo '<div class="shop-toolbar-left">';
	aqualuxe_woocommerce_result_count();
	echo '</div>';
	echo '<div class="shop-toolbar-right">';
	aqualuxe_woocommerce_view_switcher();
	aqualuxe_woocommerce_catalog_ordering();
	echo '</div>';
	echo '</div>';
}

/**
 * Display shop sidebar toggle button
 */
function aqualuxe_woocommerce_shop_sidebar_toggle() {
	if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
		return;
	}

	if ( ! is_active_sidebar( 'shop-sidebar' ) ) {
		return;
	}

	echo '<button class="shop-sidebar-toggle" aria-expanded="false" aria-controls="shop-sidebar">';
	echo '<svg class="icon icon-filter" aria-hidden="true" focusable="false"><use xlink:href="#icon-filter"></use></svg>';
	echo '<span>' . esc_html__( 'Filter', 'aqualuxe' ) . '</span>';
	echo '</button>';
}

/**
 * Display cart empty message
 */
function aqualuxe_woocommerce_cart_empty_message() {
	echo '<div class="cart-empty-message">';
	echo '<div class="cart-empty-icon">';
	echo '<svg class="icon icon-cart" aria-hidden="true" focusable="false"><use xlink:href="#icon-cart"></use></svg>';
	echo '</div>';
	echo '<h2>' . esc_html__( 'Your cart is currently empty.', 'aqualuxe' ) . '</h2>';
	echo '<p>' . esc_html__( 'Looks like you haven\'t added any items to your cart yet.', 'aqualuxe' ) . '</p>';
	echo '<a href="' . esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) . '" class="button return-to-shop">';
	echo esc_html__( 'Return to Shop', 'aqualuxe' );
	echo '</a>';
	echo '</div>';
}

/**
 * Display checkout login message
 */
function aqualuxe_woocommerce_checkout_login_message() {
	if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
		return;
	}

	echo '<div class="checkout-login-reminder">';
	echo '<p>' . esc_html__( 'Returning customer?', 'aqualuxe' ) . ' <a href="#" class="showlogin">' . esc_html__( 'Click here to login', 'aqualuxe' ) . '</a></p>';
	echo '</div>';
}

/**
 * Display checkout coupon message
 */
function aqualuxe_woocommerce_checkout_coupon_message() {
	if ( ! wc_coupons_enabled() ) {
		return;
	}

	echo '<div class="checkout-coupon-reminder">';
	echo '<p>' . esc_html__( 'Have a coupon?', 'aqualuxe' ) . ' <a href="#" class="showcoupon">' . esc_html__( 'Click here to enter your code', 'aqualuxe' ) . '</a></p>';
	echo '</div>';
}

/**
 * Display order review heading
 */
function aqualuxe_woocommerce_order_review_heading() {
	echo '<h3 id="order_review_heading">' . esc_html__( 'Your order', 'aqualuxe' ) . '</h3>';
}

/**
 * Display payment methods heading
 */
function aqualuxe_woocommerce_payment_methods_heading() {
	echo '<h3 id="payment_methods_heading">' . esc_html__( 'Payment methods', 'aqualuxe' ) . '</h3>';
}

/**
 * Display checkout additional information
 */
function aqualuxe_woocommerce_checkout_additional_information() {
	echo '<div class="checkout-additional-information">';
	echo '<h3>' . esc_html__( 'Additional Information', 'aqualuxe' ) . '</h3>';
	echo '<p>' . esc_html__( 'Need help with your order? Contact our customer support team.', 'aqualuxe' ) . '</p>';
	echo '<div class="checkout-contact-info">';
	echo '<div class="checkout-contact-item">';
	echo '<svg class="icon icon-phone" aria-hidden="true" focusable="false"><use xlink:href="#icon-phone"></use></svg>';
	echo '<span>' . esc_html__( '+1 (555) 123-4567', 'aqualuxe' ) . '</span>';
	echo '</div>';
	echo '<div class="checkout-contact-item">';
	echo '<svg class="icon icon-envelope" aria-hidden="true" focusable="false"><use xlink:href="#icon-envelope"></use></svg>';
	echo '<span>' . esc_html__( 'support@aqualuxe.com', 'aqualuxe' ) . '</span>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}

/**
 * Display checkout secure payment message
 */
function aqualuxe_woocommerce_checkout_secure_payment() {
	echo '<div class="checkout-secure-payment">';
	echo '<svg class="icon icon-lock" aria-hidden="true" focusable="false"><use xlink:href="#icon-lock"></use></svg>';
	echo '<span>' . esc_html__( 'Secure Payment', 'aqualuxe' ) . '</span>';
	echo '<p>' . esc_html__( 'Your payment information is processed securely.', 'aqualuxe' ) . '</p>';
	echo '</div>';
}

/**
 * Display account dashboard welcome message
 */
function aqualuxe_woocommerce_account_welcome() {
	$current_user = wp_get_current_user();
	
	if ( $current_user->display_name ) {
		$welcome_name = $current_user->display_name;
	} elseif ( $current_user->user_firstname ) {
		$welcome_name = $current_user->user_firstname;
	} else {
		$welcome_name = $current_user->user_login;
	}

	echo '<div class="account-welcome">';
	echo '<h2>' . sprintf( esc_html__( 'Welcome, %s', 'aqualuxe' ), esc_html( $welcome_name ) ) . '</h2>';
	echo '<p>' . esc_html__( 'From your account dashboard you can view your recent orders, manage your shipping and billing addresses, and edit your password and account details.', 'aqualuxe' ) . '</p>';
	echo '</div>';
}

/**
 * Display account dashboard stats
 */
function aqualuxe_woocommerce_account_dashboard_stats() {
	$customer_id = get_current_user_id();
	$order_count = wc_get_customer_order_count( $customer_id );
	$download_count = count( wc_get_customer_available_downloads( $customer_id ) );

	echo '<div class="account-dashboard-stats">';
	
	echo '<div class="account-stat">';
	echo '<div class="account-stat-icon">';
	echo '<svg class="icon icon-package" aria-hidden="true" focusable="false"><use xlink:href="#icon-package"></use></svg>';
	echo '</div>';
	echo '<div class="account-stat-content">';
	echo '<span class="account-stat-value">' . esc_html( $order_count ) . '</span>';
	echo '<span class="account-stat-label">' . esc_html__( 'Orders', 'aqualuxe' ) . '</span>';
	echo '</div>';
	echo '</div>';
	
	echo '<div class="account-stat">';
	echo '<div class="account-stat-icon">';
	echo '<svg class="icon icon-download" aria-hidden="true" focusable="false"><use xlink:href="#icon-download"></use></svg>';
	echo '</div>';
	echo '<div class="account-stat-content">';
	echo '<span class="account-stat-value">' . esc_html( $download_count ) . '</span>';
	echo '<span class="account-stat-label">' . esc_html__( 'Downloads', 'aqualuxe' ) . '</span>';
	echo '</div>';
	echo '</div>';
	
	if ( function_exists( 'aqualuxe_get_wishlist_count' ) ) {
		$wishlist_count = aqualuxe_get_wishlist_count();
		
		echo '<div class="account-stat">';
		echo '<div class="account-stat-icon">';
		echo '<svg class="icon icon-heart" aria-hidden="true" focusable="false"><use xlink:href="#icon-heart"></use></svg>';
		echo '</div>';
		echo '<div class="account-stat-content">';
		echo '<span class="account-stat-value">' . esc_html( $wishlist_count ) . '</span>';
		echo '<span class="account-stat-label">' . esc_html__( 'Wishlist', 'aqualuxe' ) . '</span>';
		echo '</div>';
		echo '</div>';
	}
	
	echo '</div>';
}

/**
 * Display account dashboard recent orders
 */
function aqualuxe_woocommerce_account_dashboard_orders() {
	$customer_id = get_current_user_id();
	$customer_orders = wc_get_orders( array(
		'customer' => $customer_id,
		'limit'    => 5,
		'status'   => array( 'wc-processing', 'wc-completed' ),
	) );

	if ( empty( $customer_orders ) ) {
		return;
	}

	echo '<div class="account-dashboard-orders">';
	echo '<h3>' . esc_html__( 'Recent Orders', 'aqualuxe' ) . '</h3>';
	echo '<table class="account-orders-table">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>' . esc_html__( 'Order', 'aqualuxe' ) . '</th>';
	echo '<th>' . esc_html__( 'Date', 'aqualuxe' ) . '</th>';
	echo '<th>' . esc_html__( 'Status', 'aqualuxe' ) . '</th>';
	echo '<th>' . esc_html__( 'Total', 'aqualuxe' ) . '</th>';
	echo '<th>' . esc_html__( 'Actions', 'aqualuxe' ) . '</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	
	foreach ( $customer_orders as $customer_order ) {
		$order = wc_get_order( $customer_order );
		$item_count = $order->get_item_count();
		
		echo '<tr>';
		echo '<td>#' . esc_html( $order->get_order_number() ) . '</td>';
		echo '<td>' . esc_html( wc_format_datetime( $order->get_date_created() ) ) . '</td>';
		echo '<td>' . esc_html( wc_get_order_status_name( $order->get_status() ) ) . '</td>';
		echo '<td>' . wp_kses_post( $order->get_formatted_order_total() ) . '</td>';
		echo '<td><a href="' . esc_url( $order->get_view_order_url() ) . '" class="button">' . esc_html__( 'View', 'aqualuxe' ) . '</a></td>';
		echo '</tr>';
	}
	
	echo '</tbody>';
	echo '</table>';
	echo '<a href="' . esc_url( wc_get_endpoint_url( 'orders' ) ) . '" class="button view-all-orders">' . esc_html__( 'View All Orders', 'aqualuxe' ) . '</a>';
	echo '</div>';
}

/**
 * Display account dashboard addresses
 */
function aqualuxe_woocommerce_account_dashboard_addresses() {
	$customer_id = get_current_user_id();
	$shipping_address = wc_get_account_formatted_address( 'shipping' );
	$billing_address = wc_get_account_formatted_address( 'billing' );

	echo '<div class="account-dashboard-addresses">';
	echo '<h3>' . esc_html__( 'Addresses', 'aqualuxe' ) . '</h3>';
	echo '<div class="account-addresses">';
	
	echo '<div class="account-address billing-address">';
	echo '<h4>' . esc_html__( 'Billing Address', 'aqualuxe' ) . '</h4>';
	
	if ( $billing_address ) {
		echo '<address>' . wp_kses_post( $billing_address ) . '</address>';
	} else {
		echo '<p class="no-address">' . esc_html__( 'You have not set up a billing address yet.', 'aqualuxe' ) . '</p>';
	}
	
	echo '<a href="' . esc_url( wc_get_endpoint_url( 'edit-address', 'billing' ) ) . '" class="edit-address">' . esc_html__( 'Edit', 'aqualuxe' ) . '</a>';
	echo '</div>';
	
	echo '<div class="account-address shipping-address">';
	echo '<h4>' . esc_html__( 'Shipping Address', 'aqualuxe' ) . '</h4>';
	
	if ( $shipping_address ) {
		echo '<address>' . wp_kses_post( $shipping_address ) . '</address>';
	} else {
		echo '<p class="no-address">' . esc_html__( 'You have not set up a shipping address yet.', 'aqualuxe' ) . '</p>';
	}
	
	echo '<a href="' . esc_url( wc_get_endpoint_url( 'edit-address', 'shipping' ) ) . '" class="edit-address">' . esc_html__( 'Edit', 'aqualuxe' ) . '</a>';
	echo '</div>';
	
	echo '</div>';
	echo '</div>';
}