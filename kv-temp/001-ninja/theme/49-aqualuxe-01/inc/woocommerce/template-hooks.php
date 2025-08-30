<?php
/**
 * WooCommerce template hooks
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Remove default WooCommerce hooks
 */
function aqualuxe_remove_woocommerce_hooks() {
	// Remove breadcrumb (we'll add our own)
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	
	// Remove sidebar (we'll add our own)
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	
	// Remove default product loop item elements
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
	
	// Remove default single product elements
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	
	// Remove default shop page elements
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
}
add_action( 'init', 'aqualuxe_remove_woocommerce_hooks' );

/**
 * Add custom WooCommerce hooks
 */
function aqualuxe_add_woocommerce_hooks() {
	// Shop page
	add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_shop_title', 30 );
	add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_shop_description', 40 );
	add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_category_image', 50 );
	add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_category_description', 60 );
	
	// Shop toolbar
	add_action( 'woocommerce_before_shop_loop', 'aqualuxe_woocommerce_shop_toolbar', 20 );
	add_action( 'woocommerce_before_shop_loop', 'aqualuxe_woocommerce_shop_sidebar_toggle', 10 );
	
	// Product loop
	add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_badges', 5 );
	add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_categories', 5 );
	add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_title', 10 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_rating', 5 );
	add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_actions', 10 );
	add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
	
	// Single product
	add_action( 'woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_template_loop_badges', 5 );
	add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_categories', 5 );
	add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_rating', 15 );
	add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_short_description', 25 );
	add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_availability', 35 );
	add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_meta', 40 );
	add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_wishlist', 45 );
	add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_sharing', 50 );
	
	// After single product summary
	add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_specifications', 15 );
	
	// Cart page
	add_action( 'woocommerce_cart_is_empty', 'aqualuxe_woocommerce_cart_empty_message', 10 );
	
	// Checkout page
	add_action( 'woocommerce_before_checkout_form', 'aqualuxe_woocommerce_checkout_login_message', 5 );
	add_action( 'woocommerce_before_checkout_form', 'aqualuxe_woocommerce_checkout_coupon_message', 10 );
	add_action( 'woocommerce_checkout_before_order_review_heading', 'aqualuxe_woocommerce_order_review_heading', 10 );
	add_action( 'woocommerce_checkout_before_payment', 'aqualuxe_woocommerce_payment_methods_heading', 10 );
	add_action( 'woocommerce_checkout_after_customer_details', 'aqualuxe_woocommerce_checkout_additional_information', 10 );
	add_action( 'woocommerce_review_order_after_payment', 'aqualuxe_woocommerce_checkout_secure_payment', 10 );
	
	// Account page
	add_action( 'woocommerce_account_dashboard', 'aqualuxe_woocommerce_account_welcome', 10 );
	add_action( 'woocommerce_account_dashboard', 'aqualuxe_woocommerce_account_dashboard_stats', 20 );
	add_action( 'woocommerce_account_dashboard', 'aqualuxe_woocommerce_account_dashboard_orders', 30 );
	add_action( 'woocommerce_account_dashboard', 'aqualuxe_woocommerce_account_dashboard_addresses', 40 );
}
add_action( 'after_setup_theme', 'aqualuxe_add_woocommerce_hooks' );

/**
 * Modify WooCommerce product loop columns
 */
function aqualuxe_woocommerce_loop_columns_class( $classes ) {
	$columns = get_theme_mod( 'aqualuxe_product_columns', 4 );
	$classes[] = 'columns-' . $columns;
	
	// Add view class (grid/list)
	$view = isset( $_COOKIE['aqualuxe_product_view'] ) ? sanitize_text_field( $_COOKIE['aqualuxe_product_view'] ) : 'grid';
	$classes[] = 'product-view-' . $view;
	
	return $classes;
}
add_filter( 'woocommerce_product_loop_class', 'aqualuxe_woocommerce_loop_columns_class' );

/**
 * Add custom classes to product items
 */
function aqualuxe_woocommerce_post_class( $classes, $product ) {
	if ( is_a( $product, 'WC_Product' ) ) {
		// Add stock status class
		if ( ! $product->is_in_stock() ) {
			$classes[] = 'out-of-stock';
		}
		
		// Add sale class
		if ( $product->is_on_sale() ) {
			$classes[] = 'on-sale';
		}
		
		// Add featured class
		if ( $product->is_featured() ) {
			$classes[] = 'featured';
		}
		
		// Add new class (products less than 30 days old)
		$days_old = ( time() - get_the_time( 'U' ) ) / DAY_IN_SECONDS;
		
		if ( $days_old < 30 ) {
			$classes[] = 'new-product';
		}
	}
	
	return $classes;
}
add_filter( 'woocommerce_post_class', 'aqualuxe_woocommerce_post_class', 10, 2 );

/**
 * Add custom classes to body for WooCommerce pages
 */
function aqualuxe_woocommerce_body_class( $classes ) {
	// Add class for shop sidebar position
	if ( is_shop() || is_product_category() || is_product_tag() ) {
		$sidebar_position = get_theme_mod( 'aqualuxe_shop_sidebar_position', 'left' );
		$classes[] = 'shop-sidebar-' . $sidebar_position;
	}
	
	// Add class for product view (grid/list)
	if ( is_shop() || is_product_category() || is_product_tag() ) {
		$view = isset( $_COOKIE['aqualuxe_product_view'] ) ? sanitize_text_field( $_COOKIE['aqualuxe_product_view'] ) : 'grid';
		$classes[] = 'product-view-' . $view;
	}
	
	return $classes;
}
add_filter( 'body_class', 'aqualuxe_woocommerce_body_class' );

/**
 * Modify breadcrumb arguments
 */
function aqualuxe_woocommerce_breadcrumb_args( $args ) {
	$args['delimiter'] = '<span class="breadcrumb-separator">/</span>';
	$args['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';
	$args['wrap_after'] = '</nav>';
	$args['home'] = esc_html__( 'Home', 'aqualuxe' );
	
	return $args;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_args' );

/**
 * Modify sale flash text
 */
function aqualuxe_woocommerce_sale_flash( $text, $post, $product ) {
	$discount = '';
	
	if ( $product->is_type( 'variable' ) ) {
		$percentages = array();
		
		$prices = $product->get_variation_prices();
		
		foreach ( $prices['price'] as $key => $price ) {
			if ( $prices['regular_price'][ $key ] !== $price ) {
				$percentages[] = round( 100 - ( $price / $prices['regular_price'][ $key ] * 100 ) );
			}
		}
		
		if ( ! empty( $percentages ) ) {
			$discount = max( $percentages );
		}
	} elseif ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) {
		$regular_price = $product->get_regular_price();
		$sale_price = $product->get_sale_price();
		
		if ( $regular_price && $sale_price ) {
			$discount = round( 100 - ( $sale_price / $regular_price * 100 ) );
		}
	}
	
	if ( $discount ) {
		return '<span class="onsale">-' . $discount . '%</span>';
	} else {
		return '<span class="onsale">' . esc_html__( 'Sale!', 'aqualuxe' ) . '</span>';
	}
}
add_filter( 'woocommerce_sale_flash', 'aqualuxe_woocommerce_sale_flash', 10, 3 );

/**
 * Modify product tabs
 */
function aqualuxe_woocommerce_product_tabs( $tabs ) {
	// Rename Description tab
	if ( isset( $tabs['description'] ) ) {
		$tabs['description']['title'] = esc_html__( 'Product Details', 'aqualuxe' );
	}
	
	// Add Shipping tab
	$tabs['shipping'] = array(
		'title'    => esc_html__( 'Shipping & Returns', 'aqualuxe' ),
		'priority' => 30,
		'callback' => 'aqualuxe_woocommerce_shipping_tab_content',
	);
	
	// Add Care tab for specific categories
	global $product;
	
	if ( $product && has_term( array( 'fish', 'plants', 'aquariums' ), 'product_cat', $product->get_id() ) ) {
		$tabs['care'] = array(
			'title'    => esc_html__( 'Care Guide', 'aqualuxe' ),
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
	// Get shipping content from theme options or use default
	$shipping_content = get_theme_mod( 'aqualuxe_shipping_content', '' );
	
	if ( empty( $shipping_content ) ) {
		// Default shipping content
		?>
		<h3><?php esc_html_e( 'Shipping Information', 'aqualuxe' ); ?></h3>
		<p><?php esc_html_e( 'We ship worldwide with various shipping options available at checkout.', 'aqualuxe' ); ?></p>
		
		<h4><?php esc_html_e( 'Domestic Shipping', 'aqualuxe' ); ?></h4>
		<ul>
			<li><?php esc_html_e( 'Standard Shipping: 3-5 business days', 'aqualuxe' ); ?></li>
			<li><?php esc_html_e( 'Express Shipping: 1-2 business days', 'aqualuxe' ); ?></li>
			<li><?php esc_html_e( 'Free shipping on orders over $100', 'aqualuxe' ); ?></li>
		</ul>
		
		<h4><?php esc_html_e( 'International Shipping', 'aqualuxe' ); ?></h4>
		<ul>
			<li><?php esc_html_e( 'Standard International: 7-14 business days', 'aqualuxe' ); ?></li>
			<li><?php esc_html_e( 'Express International: 3-5 business days', 'aqualuxe' ); ?></li>
			<li><?php esc_html_e( 'Import duties and taxes may apply', 'aqualuxe' ); ?></li>
		</ul>
		
		<h3><?php esc_html_e( 'Returns & Exchanges', 'aqualuxe' ); ?></h3>
		<p><?php esc_html_e( 'We want you to be completely satisfied with your purchase.', 'aqualuxe' ); ?></p>
		
		<ul>
			<li><?php esc_html_e( '30-day return policy for most items', 'aqualuxe' ); ?></li>
			<li><?php esc_html_e( 'Items must be unused and in original packaging', 'aqualuxe' ); ?></li>
			<li><?php esc_html_e( 'Live fish and plants have special return policies', 'aqualuxe' ); ?></li>
			<li><?php esc_html_e( 'Contact our customer service team to initiate a return', 'aqualuxe' ); ?></li>
		</ul>
		<?php
	} else {
		echo wp_kses_post( wpautop( $shipping_content ) );
	}
}

/**
 * Care tab content
 */
function aqualuxe_woocommerce_care_tab_content() {
	global $product;
	
	// Get care guide content from product meta or use default based on category
	$care_guide = get_post_meta( $product->get_id(), '_care_guide', true );
	
	if ( empty( $care_guide ) ) {
		// Default care guide based on category
		if ( has_term( 'fish', 'product_cat', $product->get_id() ) ) {
			?>
			<h3><?php esc_html_e( 'Fish Care Guide', 'aqualuxe' ); ?></h3>
			<p><?php esc_html_e( 'Proper care is essential for the health and longevity of your aquatic pets.', 'aqualuxe' ); ?></p>
			
			<h4><?php esc_html_e( 'Water Parameters', 'aqualuxe' ); ?></h4>
			<ul>
				<li><?php esc_html_e( 'Temperature: 72-78°F (22-26°C)', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'pH: 6.8-7.5', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Ammonia: 0 ppm', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Nitrite: 0 ppm', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Nitrate: <20 ppm', 'aqualuxe' ); ?></li>
			</ul>
			
			<h4><?php esc_html_e( 'Feeding', 'aqualuxe' ); ?></h4>
			<p><?php esc_html_e( 'Feed small amounts 1-2 times daily. Remove uneaten food after 2-3 minutes.', 'aqualuxe' ); ?></p>
			
			<h4><?php esc_html_e( 'Maintenance', 'aqualuxe' ); ?></h4>
			<ul>
				<li><?php esc_html_e( 'Perform 25% water changes weekly', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Clean filter media monthly (in tank water, not tap water)', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Test water parameters regularly', 'aqualuxe' ); ?></li>
			</ul>
			<?php
		} elseif ( has_term( 'plants', 'product_cat', $product->get_id() ) ) {
			?>
			<h3><?php esc_html_e( 'Aquatic Plant Care Guide', 'aqualuxe' ); ?></h3>
			<p><?php esc_html_e( 'Proper care will help your aquatic plants thrive and enhance your aquarium.', 'aqualuxe' ); ?></p>
			
			<h4><?php esc_html_e( 'Lighting', 'aqualuxe' ); ?></h4>
			<p><?php esc_html_e( 'Most aquatic plants require 8-10 hours of light daily. LED lights with the right spectrum are recommended.', 'aqualuxe' ); ?></p>
			
			<h4><?php esc_html_e( 'Substrate', 'aqualuxe' ); ?></h4>
			<p><?php esc_html_e( 'Use a nutrient-rich substrate designed for planted aquariums.', 'aqualuxe' ); ?></p>
			
			<h4><?php esc_html_e( 'Fertilization', 'aqualuxe' ); ?></h4>
			<p><?php esc_html_e( 'Regular dosing with liquid fertilizers will provide essential nutrients.', 'aqualuxe' ); ?></p>
			
			<h4><?php esc_html_e( 'CO2', 'aqualuxe' ); ?></h4>
			<p><?php esc_html_e( 'For lush growth, consider adding a CO2 system to your aquarium.', 'aqualuxe' ); ?></p>
			
			<h4><?php esc_html_e( 'Maintenance', 'aqualuxe' ); ?></h4>
			<ul>
				<li><?php esc_html_e( 'Trim plants regularly to encourage growth', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Remove dead or decaying leaves', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Monitor for algae growth', 'aqualuxe' ); ?></li>
			</ul>
			<?php
		} elseif ( has_term( 'aquariums', 'product_cat', $product->get_id() ) ) {
			?>
			<h3><?php esc_html_e( 'Aquarium Setup & Maintenance Guide', 'aqualuxe' ); ?></h3>
			<p><?php esc_html_e( 'Follow these guidelines to set up and maintain your aquarium properly.', 'aqualuxe' ); ?></p>
			
			<h4><?php esc_html_e( 'Initial Setup', 'aqualuxe' ); ?></h4>
			<ol>
				<li><?php esc_html_e( 'Place the aquarium on a sturdy, level surface', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Add substrate (2-3 inches)', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Install filtration and heating equipment', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Add decorations and plants', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Fill with dechlorinated water', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Cycle the tank for 4-6 weeks before adding fish', 'aqualuxe' ); ?></li>
			</ol>
			
			<h4><?php esc_html_e( 'Regular Maintenance', 'aqualuxe' ); ?></h4>
			<ul>
				<li><?php esc_html_e( 'Weekly: 25% water change, clean glass, vacuum substrate', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Monthly: Clean filter media, check equipment', 'aqualuxe' ); ?></li>
				<li><?php esc_html_e( 'Regularly test water parameters', 'aqualuxe' ); ?></li>
			</ul>
			
			<h4><?php esc_html_e( 'Troubleshooting', 'aqualuxe' ); ?></h4>
			<p><?php esc_html_e( 'If you notice cloudy water, algae growth, or fish stress, check water parameters and adjust maintenance routine as needed.', 'aqualuxe' ); ?></p>
			<?php
		} else {
			?>
			<h3><?php esc_html_e( 'Product Care Guide', 'aqualuxe' ); ?></h3>
			<p><?php esc_html_e( 'Follow the manufacturer\'s instructions for best results. For specific care questions, please contact our customer service team.', 'aqualuxe' ); ?></p>
			<?php
		}
	} else {
		echo wp_kses_post( wpautop( $care_guide ) );
	}
}

/**
 * Modify related products heading
 */
function aqualuxe_woocommerce_related_products_heading( $heading ) {
	return esc_html__( 'You May Also Like', 'aqualuxe' );
}
add_filter( 'woocommerce_product_related_products_heading', 'aqualuxe_woocommerce_related_products_heading' );

/**
 * Modify upsell products heading
 */
function aqualuxe_woocommerce_upsell_products_heading( $heading ) {
	return esc_html__( 'Complete Your Setup', 'aqualuxe' );
}
add_filter( 'woocommerce_product_upsells_products_heading', 'aqualuxe_woocommerce_upsell_products_heading' );

/**
 * Modify cross-sell products heading
 */
function aqualuxe_woocommerce_cross_sell_products_heading( $heading ) {
	return esc_html__( 'Recommended With Your Selection', 'aqualuxe' );
}
add_filter( 'woocommerce_product_cross_sells_products_heading', 'aqualuxe_woocommerce_cross_sell_products_heading' );

/**
 * Add custom sorting options
 */
function aqualuxe_woocommerce_catalog_orderby( $options ) {
	$options['best_selling'] = esc_html__( 'Sort by best selling', 'aqualuxe' );
	$options['featured'] = esc_html__( 'Sort by featured', 'aqualuxe' );
	$options['newest'] = esc_html__( 'Sort by newest', 'aqualuxe' );
	
	return $options;
}
add_filter( 'woocommerce_catalog_orderby', 'aqualuxe_woocommerce_catalog_orderby' );

/**
 * Handle custom sorting options
 */
function aqualuxe_woocommerce_get_catalog_ordering_args( $args ) {
	if ( isset( $_GET['orderby'] ) ) {
		switch ( $_GET['orderby'] ) {
			case 'best_selling':
				$args['meta_key'] = 'total_sales';
				$args['orderby'] = 'meta_value_num';
				break;
			case 'featured':
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = '_featured';
				$args['meta_value'] = 'yes';
				break;
			case 'newest':
				$args['orderby'] = 'date';
				$args['order'] = 'DESC';
				break;
		}
	}
	
	return $args;
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'aqualuxe_woocommerce_get_catalog_ordering_args' );

/**
 * Add custom product data tabs
 */
function aqualuxe_woocommerce_custom_product_data_tabs( $tabs ) {
	$tabs['care_guide'] = array(
		'label'    => esc_html__( 'Care Guide', 'aqualuxe' ),
		'target'   => 'care_guide_product_data',
		'class'    => array(),
		'priority' => 60,
	);
	
	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'aqualuxe_woocommerce_custom_product_data_tabs' );

/**
 * Add custom product data fields
 */
function aqualuxe_woocommerce_custom_product_data_fields() {
	global $post;
	
	echo '<div id="care_guide_product_data" class="panel woocommerce_options_panel">';
	
	woocommerce_wp_textarea_input( array(
		'id'          => '_care_guide',
		'label'       => esc_html__( 'Care Guide', 'aqualuxe' ),
		'placeholder' => esc_html__( 'Enter care instructions for this product', 'aqualuxe' ),
		'desc_tip'    => true,
		'description' => esc_html__( 'This content will appear in the Care Guide tab on the product page.', 'aqualuxe' ),
	) );
	
	echo '</div>';
}
add_action( 'woocommerce_product_data_panels', 'aqualuxe_woocommerce_custom_product_data_fields' );

/**
 * Save custom product data fields
 */
function aqualuxe_woocommerce_save_custom_product_data_fields( $post_id ) {
	if ( isset( $_POST['_care_guide'] ) ) {
		update_post_meta( $post_id, '_care_guide', wp_kses_post( $_POST['_care_guide'] ) );
	}
}
add_action( 'woocommerce_process_product_meta', 'aqualuxe_woocommerce_save_custom_product_data_fields' );

/**
 * Add view cart button after add to cart
 */
function aqualuxe_woocommerce_add_to_cart_message( $message, $products ) {
	$message .= sprintf(
		'<a href="%s" class="button wc-forward">%s</a>',
		esc_url( wc_get_page_permalink( 'cart' ) ),
		esc_html__( 'View Cart', 'aqualuxe' )
	);
	
	return $message;
}
add_filter( 'wc_add_to_cart_message_html', 'aqualuxe_woocommerce_add_to_cart_message', 10, 2 );

/**
 * Add continue shopping button to cart page
 */
function aqualuxe_woocommerce_continue_shopping_button() {
	echo '<a href="' . esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) . '" class="button continue-shopping">';
	echo esc_html__( 'Continue Shopping', 'aqualuxe' );
	echo '</a>';
}
add_action( 'woocommerce_cart_actions', 'aqualuxe_woocommerce_continue_shopping_button' );

/**
 * Add product view switcher script
 */
function aqualuxe_woocommerce_view_switcher_script() {
	if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
		return;
	}
	
	?>
	<script>
		(function($) {
			$(document).ready(function() {
				// View switcher
				$('.view-switcher a').on('click', function(e) {
					e.preventDefault();
					
					var view = $(this).data('view');
					
					// Set cookie
					document.cookie = 'aqualuxe_product_view=' + view + '; path=/; max-age=31536000';
					
					// Update active class
					$('.view-switcher a').removeClass('active');
					$(this).addClass('active');
					
					// Update body class
					$('body').removeClass('product-view-grid product-view-list').addClass('product-view-' + view);
					
					// Update products class
					$('ul.products').removeClass('product-view-grid product-view-list').addClass('product-view-' + view);
				});
			});
		})(jQuery);
	</script>
	<?php
}
add_action( 'wp_footer', 'aqualuxe_woocommerce_view_switcher_script' );

/**
 * Add shop sidebar toggle script
 */
function aqualuxe_woocommerce_shop_sidebar_toggle_script() {
	if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
		return;
	}
	
	if ( ! is_active_sidebar( 'shop-sidebar' ) ) {
		return;
	}
	
	?>
	<script>
		(function($) {
			$(document).ready(function() {
				// Shop sidebar toggle
				$('.shop-sidebar-toggle').on('click', function() {
					var $sidebar = $('#secondary.shop-sidebar');
					var isExpanded = $(this).attr('aria-expanded') === 'true';
					
					if (isExpanded) {
						$sidebar.slideUp(200);
						$(this).attr('aria-expanded', 'false');
					} else {
						$sidebar.slideDown(200);
						$(this).attr('aria-expanded', 'true');
					}
				});
				
				// Close sidebar on mobile when filter is applied
				$(document.body).on('wc_fragments_refreshed', function() {
					if (window.innerWidth < 768) {
						$('#secondary.shop-sidebar').slideUp(200);
						$('.shop-sidebar-toggle').attr('aria-expanded', 'false');
					}
				});
			});
		})(jQuery);
	</script>
	<?php
}
add_action( 'wp_footer', 'aqualuxe_woocommerce_shop_sidebar_toggle_script' );

/**
 * Add quick view modal script
 */
function aqualuxe_woocommerce_quick_view_script() {
	if ( ! get_theme_mod( 'aqualuxe_quick_view', true ) ) {
		return;
	}
	
	?>
	<script>
		(function($) {
			$(document).ready(function() {
				// Quick view modal
				$(document.body).on('click', '.quick-view-button', function(e) {
					e.preventDefault();
					
					var $button = $(this);
					var productId = $button.data('product-id');
					
					// Show loading
					$button.addClass('loading');
					
					// Get quick view content via AJAX
					$.ajax({
						url: aqualuxeWooCommerce.ajaxUrl,
						type: 'POST',
						data: {
							action: 'aqualuxe_quick_view',
							product_id: productId,
							nonce: aqualuxeWooCommerce.nonce
						},
						success: function(response) {
							// Hide loading
							$button.removeClass('loading');
							
							// Show modal
							if (response.success) {
								// Create modal if it doesn't exist
								if ($('#quick-view-modal').length === 0) {
									$('body').append('<div id="quick-view-modal" class="quick-view-modal"><div class="quick-view-content"></div></div>');
								}
								
								// Add content to modal
								$('#quick-view-modal .quick-view-content').html(response.data.html);
								
								// Show modal
								$('#quick-view-modal').addClass('open');
								
								// Initialize product gallery
								if (typeof wc_single_product_params !== 'undefined') {
									$('#quick-view-modal .woocommerce-product-gallery').each(function() {
										$(this).wc_product_gallery(wc_single_product_params);
									});
								}
								
								// Initialize quantity buttons
								$('#quick-view-modal .quantity').on('click', '.plus, .minus', function() {
									var $qty = $(this).closest('.quantity').find('.qty');
									var currentVal = parseFloat($qty.val());
									var max = parseFloat($qty.attr('max'));
									var min = parseFloat($qty.attr('min'));
									var step = parseFloat($qty.attr('step'));
									
									if ($(this).is('.plus')) {
										if (max && (max <= currentVal)) {
											$qty.val(max);
										} else {
											$qty.val(currentVal + step);
										}
									} else {
										if (min && (min >= currentVal)) {
											$qty.val(min);
										} else if (currentVal > 0) {
											$qty.val(currentVal - step);
										}
									}
									
									$qty.trigger('change');
								});
							}
						},
						error: function() {
							// Hide loading
							$button.removeClass('loading');
						}
					});
				});
				
				// Close quick view modal
				$(document.body).on('click', '#quick-view-modal', function(e) {
					if ($(e.target).is('#quick-view-modal')) {
						$(this).removeClass('open');
					}
				});
				
				$(document.body).on('click', '#quick-view-modal .close-modal', function(e) {
					e.preventDefault();
					$('#quick-view-modal').removeClass('open');
				});
				
				// Close modal on escape key
				$(document).keyup(function(e) {
					if (e.key === 'Escape' && $('#quick-view-modal').hasClass('open')) {
						$('#quick-view-modal').removeClass('open');
					}
				});
			});
		})(jQuery);
	</script>
	<?php
}
add_action( 'wp_footer', 'aqualuxe_woocommerce_quick_view_script' );

/**
 * Add wishlist script
 */
function aqualuxe_woocommerce_wishlist_script() {
	if ( ! get_theme_mod( 'aqualuxe_wishlist', true ) ) {
		return;
	}
	
	?>
	<script>
		(function($) {
			$(document).ready(function() {
				// Wishlist toggle
				$(document.body).on('click', '.wishlist-button', function(e) {
					e.preventDefault();
					
					var $button = $(this);
					var productId = $button.data('product-id');
					
					// Show loading
					$button.addClass('loading');
					
					// Toggle wishlist via AJAX
					$.ajax({
						url: aqualuxeWooCommerce.ajaxUrl,
						type: 'POST',
						data: {
							action: 'aqualuxe_toggle_wishlist',
							product_id: productId,
							nonce: aqualuxeWooCommerce.nonce
						},
						success: function(response) {
							// Hide loading
							$button.removeClass('loading');
							
							if (response.success) {
								// Update button state
								if (response.data.in_wishlist) {
									$button.addClass('in-wishlist');
									$button.attr('title', aqualuxeWooCommerce.i18n.remove_from_wishlist);
								} else {
									$button.removeClass('in-wishlist');
									$button.attr('title', aqualuxeWooCommerce.i18n.add_to_wishlist);
								}
								
								// Update wishlist count
								$('.wishlist-count').text(response.data.count);
							}
						},
						error: function() {
							// Hide loading
							$button.removeClass('loading');
						}
					});
				});
			});
		})(jQuery);
	</script>
	<?php
}
add_action( 'wp_footer', 'aqualuxe_woocommerce_wishlist_script' );