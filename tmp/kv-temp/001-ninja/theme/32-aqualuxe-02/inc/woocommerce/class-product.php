<?php
/**
 * AquaLuxe WooCommerce Product Class
 *
 * @package AquaLuxe
 * @subpackage WooCommerce
 * @since 1.1.0
 */

namespace AquaLuxe\WooCommerce;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce Product Class
 *
 * Handles WooCommerce product customizations and enhancements.
 *
 * @since 1.1.0
 */
class Product {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Product display hooks.
		add_action( 'woocommerce_before_shop_loop_item', array( $this, 'product_wrapper_open' ), 5 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'product_wrapper_close' ), 15 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_image_wrapper_open' ), 5 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_image_wrapper_close' ), 15 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_badges' ), 10 );
		add_action( 'woocommerce_shop_loop_item_title', array( $this, 'product_title' ), 10 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'product_rating' ), 5 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'product_price' ), 10 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'product_buttons' ), 10 );
		
		// Single product hooks.
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'product_gallery_wrapper_open' ), 5 );
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'product_gallery_wrapper_close' ), 25 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'product_summary_wrapper_open' ), 5 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'product_summary_wrapper_close' ), 65 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'product_meta_wrapper_open' ), 35 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'product_meta_wrapper_close' ), 45 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'product_share' ), 50 );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'product_tabs_wrapper_open' ), 5 );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'product_tabs_wrapper_close' ), 15 );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'related_products_wrapper_open' ), 15 );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'related_products_wrapper_close' ), 25 );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'recently_viewed_products' ), 26 );
		
		// Product data hooks.
		add_filter( 'woocommerce_product_tabs', array( $this, 'product_tabs' ), 10 );
		add_filter( 'woocommerce_product_get_rating_html', array( $this, 'product_rating_html' ), 10, 3 );
		add_filter( 'woocommerce_get_star_rating_html', array( $this, 'star_rating_html' ), 10, 3 );
		add_filter( 'woocommerce_product_get_price_html', array( $this, 'product_price_html' ), 10, 2 );
		add_filter( 'woocommerce_get_price_html', array( $this, 'price_html' ), 10, 2 );
		add_filter( 'woocommerce_product_additional_information_heading', array( $this, 'additional_information_heading' ), 10 );
		add_filter( 'woocommerce_product_description_heading', array( $this, 'description_heading' ), 10 );
		add_filter( 'woocommerce_product_reviews_heading', array( $this, 'reviews_heading' ), 10 );
		add_filter( 'woocommerce_product_related_products_heading', array( $this, 'related_products_heading' ), 10 );
		add_filter( 'woocommerce_product_upsell_products_heading', array( $this, 'upsell_products_heading' ), 10 );
		add_filter( 'woocommerce_cross_sells_heading', array( $this, 'cross_sells_heading' ), 10 );
		
		// Product image hooks.
		add_filter( 'woocommerce_single_product_image_gallery_classes', array( $this, 'gallery_classes' ), 10 );
		add_filter( 'woocommerce_single_product_image_thumbnail_html', array( $this, 'gallery_thumbnail_html' ), 10, 2 );
		add_filter( 'woocommerce_gallery_image_size', array( $this, 'gallery_image_size' ), 10 );
		add_filter( 'woocommerce_gallery_thumbnail_size', array( $this, 'gallery_thumbnail_size' ), 10 );
		
		// Product structured data.
		add_filter( 'woocommerce_structured_data_product', array( $this, 'structured_data' ), 10, 2 );
	}

	/**
	 * Product wrapper open.
	 *
	 * @return void
	 */
	public function product_wrapper_open() {
		echo '<div class="product-wrapper">';
	}

	/**
	 * Product wrapper close.
	 *
	 * @return void
	 */
	public function product_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Product image wrapper open.
	 *
	 * @return void
	 */
	public function product_image_wrapper_open() {
		echo '<div class="product-image-wrapper">';
	}

	/**
	 * Product image wrapper close.
	 *
	 * @return void
	 */
	public function product_image_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Product badges.
	 *
	 * @return void
	 */
	public function product_badges() {
		global $product;
		
		// Check if product badges are enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_product_badges', true ) ) {
			return;
		}
		
		// Get badge position.
		$badge_position = get_theme_mod( 'aqualuxe_product_badge_position', 'top-left' );
		
		echo '<div class="product-badges position-' . esc_attr( $badge_position ) . '">';
		
		// Sale badge.
		if ( $product->is_on_sale() && get_theme_mod( 'aqualuxe_enable_sale_badge', true ) ) {
			// Get sale badge style.
			$sale_badge_style = get_theme_mod( 'aqualuxe_sale_badge_style', 'text' );
			
			if ( 'percentage' === $sale_badge_style && $product->get_type() === 'simple' ) {
				// Calculate percentage.
				$regular_price = (float) $product->get_regular_price();
				$sale_price = (float) $product->get_sale_price();
				
				if ( $regular_price > 0 ) {
					$percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
					echo '<span class="badge sale-badge percentage">' . esc_html( sprintf( __( '%s%% Off', 'aqualuxe' ), $percentage ) ) . '</span>';
				} else {
					echo '<span class="badge sale-badge">' . esc_html__( 'Sale', 'aqualuxe' ) . '</span>';
				}
			} else {
				echo '<span class="badge sale-badge">' . esc_html__( 'Sale', 'aqualuxe' ) . '</span>';
			}
		}
		
		// New badge.
		$days_new = get_theme_mod( 'aqualuxe_product_new_days', 7 );
		$product_date = strtotime( $product->get_date_created() );
		$now = time();
		$days_since = floor( ( $now - $product_date ) / DAY_IN_SECONDS );
		
		if ( $days_since <= $days_new && get_theme_mod( 'aqualuxe_enable_new_badge', true ) ) {
			echo '<span class="badge new-badge">' . esc_html__( 'New', 'aqualuxe' ) . '</span>';
		}
		
		// Featured badge.
		if ( $product->is_featured() && get_theme_mod( 'aqualuxe_enable_featured_badge', true ) ) {
			echo '<span class="badge featured-badge">' . esc_html__( 'Featured', 'aqualuxe' ) . '</span>';
		}
		
		// Out of stock badge.
		if ( ! $product->is_in_stock() && get_theme_mod( 'aqualuxe_enable_out_of_stock_badge', true ) ) {
			echo '<span class="badge out-of-stock-badge">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
		}
		
		// Custom badge.
		$custom_badge = get_post_meta( $product->get_id(), '_aqualuxe_custom_badge', true );
		
		if ( $custom_badge && get_theme_mod( 'aqualuxe_enable_custom_badge', true ) ) {
			echo '<span class="badge custom-badge">' . esc_html( $custom_badge ) . '</span>';
		}
		
		echo '</div>';
	}

	/**
	 * Product title.
	 *
	 * @return void
	 */
	public function product_title() {
		// Get product title tag.
		$title_tag = get_theme_mod( 'aqualuxe_product_title_tag', 'h2' );
		
		echo '<' . esc_attr( $title_tag ) . ' class="woocommerce-loop-product__title">' . get_the_title() . '</' . esc_attr( $title_tag ) . '>';
	}

	/**
	 * Product rating.
	 *
	 * @return void
	 */
	public function product_rating() {
		global $product;
		
		// Check if product ratings are enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_product_rating', true ) ) {
			return;
		}
		
		// Check if product has rating.
		if ( $product->get_rating_count() > 0 ) {
			echo wc_get_rating_html( $product->get_average_rating() );
		} else {
			// Show empty rating.
			if ( get_theme_mod( 'aqualuxe_show_empty_rating', true ) ) {
				echo '<div class="star-rating"><span style="width:0%"></span></div>';
			}
		}
	}

	/**
	 * Product price.
	 *
	 * @return void
	 */
	public function product_price() {
		global $product;
		
		// Check if product prices are enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_product_price', true ) ) {
			return;
		}
		
		echo '<div class="price-wrapper">';
		echo $product->get_price_html();
		echo '</div>';
	}

	/**
	 * Product buttons.
	 *
	 * @return void
	 */
	public function product_buttons() {
		global $product;
		
		echo '<div class="product-buttons">';
		
		// Add to cart button.
		if ( get_theme_mod( 'aqualuxe_enable_add_to_cart', true ) ) {
			echo '<div class="button-wrapper add-to-cart-wrapper">';
			woocommerce_template_loop_add_to_cart();
			echo '</div>';
		}
		
		// Quick view button.
		if ( get_theme_mod( 'aqualuxe_enable_quick_view', true ) ) {
			echo '<div class="button-wrapper quick-view-wrapper">';
			echo '<a href="#" class="button quick-view" data-product-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__( 'Quick View', 'aqualuxe' ) . '</a>';
			echo '</div>';
		}
		
		// Wishlist button.
		if ( get_theme_mod( 'aqualuxe_enable_wishlist', true ) ) {
			// Check if product is in wishlist.
			$wishlist = $this->get_wishlist();
			$in_wishlist = in_array( $product->get_id(), $wishlist, true );
			
			echo '<div class="button-wrapper wishlist-wrapper">';
			echo '<a href="#" class="button wishlist-add' . ( $in_wishlist ? ' in-wishlist' : '' ) . '" data-product-id="' . esc_attr( $product->get_id() ) . '">';
			
			if ( $in_wishlist ) {
				echo '<span class="wishlist-text">' . esc_html__( 'Added to wishlist', 'aqualuxe' ) . '</span>';
			} else {
				echo '<span class="wishlist-text">' . esc_html__( 'Add to wishlist', 'aqualuxe' ) . '</span>';
			}
			
			echo '</a>';
			echo '</div>';
		}
		
		// Compare button.
		if ( get_theme_mod( 'aqualuxe_enable_compare', true ) ) {
			echo '<div class="button-wrapper compare-wrapper">';
			echo '<a href="#" class="button compare-add" data-product-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__( 'Compare', 'aqualuxe' ) . '</a>';
			echo '</div>';
		}
		
		echo '</div>';
	}

	/**
	 * Product gallery wrapper open.
	 *
	 * @return void
	 */
	public function product_gallery_wrapper_open() {
		echo '<div class="product-gallery-wrapper">';
	}

	/**
	 * Product gallery wrapper close.
	 *
	 * @return void
	 */
	public function product_gallery_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Product summary wrapper open.
	 *
	 * @return void
	 */
	public function product_summary_wrapper_open() {
		echo '<div class="product-summary-wrapper">';
	}

	/**
	 * Product summary wrapper close.
	 *
	 * @return void
	 */
	public function product_summary_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Product meta wrapper open.
	 *
	 * @return void
	 */
	public function product_meta_wrapper_open() {
		echo '<div class="product-meta-wrapper">';
	}

	/**
	 * Product meta wrapper close.
	 *
	 * @return void
	 */
	public function product_meta_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Product share.
	 *
	 * @return void
	 */
	public function product_share() {
		// Check if product sharing is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_product_share', true ) ) {
			return;
		}
		
		// Get product share template.
		get_template_part( 'template-parts/woocommerce/product', 'share' );
	}

	/**
	 * Product tabs wrapper open.
	 *
	 * @return void
	 */
	public function product_tabs_wrapper_open() {
		echo '<div class="product-tabs-wrapper">';
	}

	/**
	 * Product tabs wrapper close.
	 *
	 * @return void
	 */
	public function product_tabs_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Related products wrapper open.
	 *
	 * @return void
	 */
	public function related_products_wrapper_open() {
		echo '<div class="related-products-wrapper">';
	}

	/**
	 * Related products wrapper close.
	 *
	 * @return void
	 */
	public function related_products_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Recently viewed products.
	 *
	 * @return void
	 */
	public function recently_viewed_products() {
		// Check if recently viewed products are enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_recently_viewed', true ) ) {
			return;
		}
		
		// Get current product ID.
		$product_id = get_the_ID();
		
		// Get recently viewed products.
		$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? explode( '|', sanitize_text_field( wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) ) : array();
		
		// Remove current product.
		$viewed_products = array_diff( $viewed_products, array( $product_id ) );
		
		// Check if there are any viewed products.
		if ( empty( $viewed_products ) ) {
			return;
		}
		
		// Get number of products to show.
		$limit = get_theme_mod( 'aqualuxe_recently_viewed_count', 4 );
		
		// Get products.
		$args = array(
			'post_type'      => 'product',
			'post__in'       => $viewed_products,
			'posts_per_page' => $limit,
			'orderby'        => 'post__in',
			'post_status'    => 'publish',
		);
		
		$products = new \WP_Query( $args );
		
		if ( ! $products->have_posts() ) {
			return;
		}
		
		// Get columns.
		$columns = get_theme_mod( 'aqualuxe_recently_viewed_columns', 4 );
		
		// Get heading.
		$heading = get_theme_mod( 'aqualuxe_recently_viewed_heading', __( 'Recently Viewed Products', 'aqualuxe' ) );
		
		// Output wrapper.
		echo '<div class="recently-viewed-products">';
		
		// Output heading.
		echo '<h2 class="recently-viewed-products-heading">' . esc_html( $heading ) . '</h2>';
		
		// Output products.
		echo '<div class="products columns-' . esc_attr( $columns ) . '">';
		
		while ( $products->have_posts() ) {
			$products->the_post();
			wc_get_template_part( 'content', 'product' );
		}
		
		echo '</div>';
		echo '</div>';
		
		wp_reset_postdata();
	}

	/**
	 * Product tabs.
	 *
	 * @param array $tabs Product tabs.
	 * @return array
	 */
	public function product_tabs( $tabs ) {
		// Check if custom tabs are enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_custom_tabs', true ) ) {
			return $tabs;
		}
		
		// Get custom tabs.
		$custom_tabs = get_theme_mod( 'aqualuxe_custom_tabs', array() );
		
		if ( ! empty( $custom_tabs ) ) {
			foreach ( $custom_tabs as $key => $tab ) {
				$tabs[ 'custom_tab_' . $key ] = array(
					'title'    => $tab['title'],
					'priority' => 50 + $key,
					'callback' => function() use ( $tab ) {
						echo wp_kses_post( wpautop( $tab['content'] ) );
					},
				);
			}
		}
		
		return $tabs;
	}

	/**
	 * Product rating HTML.
	 *
	 * @param string $html   Rating HTML.
	 * @param float  $rating Rating.
	 * @param int    $count  Count.
	 * @return string
	 */
	public function product_rating_html( $html, $rating, $count ) {
		// Check if product ratings are enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_product_rating', true ) ) {
			return '';
		}
		
		// Check if rating count should be shown.
		if ( get_theme_mod( 'aqualuxe_show_rating_count', true ) ) {
			$html .= ' <span class="rating-count">(' . esc_html( $count ) . ')</span>';
		}
		
		return $html;
	}

	/**
	 * Star rating HTML.
	 *
	 * @param string $html   Rating HTML.
	 * @param float  $rating Rating.
	 * @param int    $count  Count.
	 * @return string
	 */
	public function star_rating_html( $html, $rating, $count ) {
		// Check if product ratings are enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_product_rating', true ) ) {
			return '';
		}
		
		// Get rating style.
		$rating_style = get_theme_mod( 'aqualuxe_rating_style', 'stars' );
		
		if ( 'numbers' === $rating_style ) {
			$html = '<div class="number-rating">' . esc_html( $rating ) . '/5</div>';
		}
		
		return $html;
	}

	/**
	 * Product price HTML.
	 *
	 * @param string      $price  Price HTML.
	 * @param WC_Product $product Product object.
	 * @return string
	 */
	public function product_price_html( $price, $product ) {
		// Check if product prices are enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_product_price', true ) ) {
			return '';
		}
		
		// Check if product is on sale.
		if ( $product->is_on_sale() ) {
			// Get sale price style.
			$sale_price_style = get_theme_mod( 'aqualuxe_sale_price_style', 'default' );
			
			if ( 'strikethrough' === $sale_price_style ) {
				$regular_price = wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price() ) );
				$sale_price = wc_get_price_to_display( $product, array( 'price' => $product->get_sale_price() ) );
				
				$price = '<del>' . wc_price( $regular_price ) . '</del> <ins>' . wc_price( $sale_price ) . '</ins>';
			}
		}
		
		return $price;
	}

	/**
	 * Price HTML.
	 *
	 * @param string      $price  Price HTML.
	 * @param WC_Product $product Product object.
	 * @return string
	 */
	public function price_html( $price, $product ) {
		// Check if product prices are enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_product_price', true ) ) {
			return '';
		}
		
		return $price;
	}

	/**
	 * Additional information heading.
	 *
	 * @param string $heading Heading.
	 * @return string
	 */
	public function additional_information_heading( $heading ) {
		// Get custom heading.
		$custom_heading = get_theme_mod( 'aqualuxe_additional_information_heading', '' );
		
		if ( ! empty( $custom_heading ) ) {
			$heading = $custom_heading;
		}
		
		return $heading;
	}

	/**
	 * Description heading.
	 *
	 * @param string $heading Heading.
	 * @return string
	 */
	public function description_heading( $heading ) {
		// Get custom heading.
		$custom_heading = get_theme_mod( 'aqualuxe_description_heading', '' );
		
		if ( ! empty( $custom_heading ) ) {
			$heading = $custom_heading;
		}
		
		return $heading;
	}

	/**
	 * Reviews heading.
	 *
	 * @param string $heading Heading.
	 * @return string
	 */
	public function reviews_heading( $heading ) {
		// Get custom heading.
		$custom_heading = get_theme_mod( 'aqualuxe_reviews_heading', '' );
		
		if ( ! empty( $custom_heading ) ) {
			$heading = $custom_heading;
		}
		
		return $heading;
	}

	/**
	 * Related products heading.
	 *
	 * @param string $heading Heading.
	 * @return string
	 */
	public function related_products_heading( $heading ) {
		// Get custom heading.
		$custom_heading = get_theme_mod( 'aqualuxe_related_products_heading', '' );
		
		if ( ! empty( $custom_heading ) ) {
			$heading = $custom_heading;
		}
		
		return $heading;
	}

	/**
	 * Upsell products heading.
	 *
	 * @param string $heading Heading.
	 * @return string
	 */
	public function upsell_products_heading( $heading ) {
		// Get custom heading.
		$custom_heading = get_theme_mod( 'aqualuxe_upsell_products_heading', '' );
		
		if ( ! empty( $custom_heading ) ) {
			$heading = $custom_heading;
		}
		
		return $heading;
	}

	/**
	 * Cross sells heading.
	 *
	 * @param string $heading Heading.
	 * @return string
	 */
	public function cross_sells_heading( $heading ) {
		// Get custom heading.
		$custom_heading = get_theme_mod( 'aqualuxe_cross_sells_heading', '' );
		
		if ( ! empty( $custom_heading ) ) {
			$heading = $custom_heading;
		}
		
		return $heading;
	}

	/**
	 * Gallery classes.
	 *
	 * @param array $classes Gallery classes.
	 * @return array
	 */
	public function gallery_classes( $classes ) {
		// Get gallery layout.
		$gallery_layout = get_theme_mod( 'aqualuxe_product_gallery_layout', 'horizontal' );
		
		// Add gallery layout class.
		$classes[] = 'layout-' . $gallery_layout;
		
		return $classes;
	}

	/**
	 * Gallery thumbnail HTML.
	 *
	 * @param string $html    Thumbnail HTML.
	 * @param int    $attachment_id Attachment ID.
	 * @return string
	 */
	public function gallery_thumbnail_html( $html, $attachment_id ) {
		// Get gallery zoom.
		$gallery_zoom = get_theme_mod( 'aqualuxe_product_gallery_zoom', true );
		
		// Add zoom class.
		if ( $gallery_zoom ) {
			$html = str_replace( 'class="', 'class="zoom ', $html );
		}
		
		return $html;
	}

	/**
	 * Gallery image size.
	 *
	 * @param string $size Image size.
	 * @return string
	 */
	public function gallery_image_size( $size ) {
		// Get gallery image size.
		$gallery_image_size = get_theme_mod( 'aqualuxe_product_gallery_image_size', 'woocommerce_single' );
		
		if ( ! empty( $gallery_image_size ) ) {
			$size = $gallery_image_size;
		}
		
		return $size;
	}

	/**
	 * Gallery thumbnail size.
	 *
	 * @param string $size Thumbnail size.
	 * @return string
	 */
	public function gallery_thumbnail_size( $size ) {
		// Get gallery thumbnail size.
		$gallery_thumbnail_size = get_theme_mod( 'aqualuxe_product_gallery_thumbnail_size', 'woocommerce_gallery_thumbnail' );
		
		if ( ! empty( $gallery_thumbnail_size ) ) {
			$size = $gallery_thumbnail_size;
		}
		
		return $size;
	}

	/**
	 * Structured data.
	 *
	 * @param array      $markup  Structured data.
	 * @param WC_Product $product Product object.
	 * @return array
	 */
	public function structured_data( $markup, $product ) {
		// Add brand.
		$brands = wp_get_post_terms( $product->get_id(), 'product_brand' );
		
		if ( $brands && ! is_wp_error( $brands ) ) {
			$markup['brand'] = array(
				'@type' => 'Brand',
				'name'  => $brands[0]->name,
			);
		}
		
		// Add SKU.
		$sku = $product->get_sku();
		
		if ( $sku ) {
			$markup['sku'] = $sku;
		}
		
		// Add MPN.
		$mpn = get_post_meta( $product->get_id(), '_aqualuxe_mpn', true );
		
		if ( $mpn ) {
			$markup['mpn'] = $mpn;
		}
		
		// Add GTIN.
		$gtin = get_post_meta( $product->get_id(), '_aqualuxe_gtin', true );
		
		if ( $gtin ) {
			$markup['gtin'] = $gtin;
		}
		
		return $markup;
	}

	/**
	 * Get wishlist.
	 *
	 * @return array
	 */
	private function get_wishlist() {
		// Check if user is logged in.
		if ( is_user_logged_in() ) {
			// Get wishlist from user meta.
			$wishlist = get_user_meta( get_current_user_id(), 'aqualuxe_wishlist', true );
			
			if ( ! $wishlist ) {
				$wishlist = array();
			}
		} else {
			// Get wishlist from cookie.
			$wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : array();
			
			if ( ! $wishlist ) {
				$wishlist = array();
			}
		}
		
		return $wishlist;
	}
}