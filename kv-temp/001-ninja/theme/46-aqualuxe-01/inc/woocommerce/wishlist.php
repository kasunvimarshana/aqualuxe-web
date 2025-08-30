<?php
/**
 * WooCommerce Wishlist functionality
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Wishlist class
 */
class AquaLuxe_Wishlist {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Check if wishlist is enabled
		if ( ! get_theme_mod( 'aqualuxe_wishlist', true ) ) {
			return;
		}

		// Add wishlist button to product loop
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'wishlist_button' ), 20 );

		// Add wishlist button to single product
		add_action( 'woocommerce_single_product_summary', array( $this, 'wishlist_button' ), 35 );

		// Add AJAX handler for wishlist
		add_action( 'wp_ajax_aqualuxe_toggle_wishlist', array( $this, 'toggle_wishlist_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_toggle_wishlist', array( $this, 'toggle_wishlist_ajax' ) );

		// Add wishlist page
		add_action( 'init', array( $this, 'register_wishlist_endpoint' ) );
		add_filter( 'query_vars', array( $this, 'add_wishlist_query_var' ) );
		add_filter( 'woocommerce_account_menu_items', array( $this, 'add_wishlist_account_menu_item' ) );
		add_action( 'woocommerce_account_wishlist_endpoint', array( $this, 'wishlist_content' ) );

		// Add wishlist shortcode
		add_shortcode( 'aqualuxe_wishlist', array( $this, 'wishlist_shortcode' ) );

		// Add localized script data
		add_filter( 'aqualuxe_woocommerce_script_data', array( $this, 'add_script_data' ) );
	}

	/**
	 * Add wishlist button
	 */
	public function wishlist_button() {
		global $product;

		if ( ! $product ) {
			return;
		}

		$product_id = $product->get_id();
		$in_wishlist = $this->is_product_in_wishlist( $product_id );
		$title = $in_wishlist ? esc_html__( 'Remove from Wishlist', 'aqualuxe' ) : esc_html__( 'Add to Wishlist', 'aqualuxe' );
		$class = $in_wishlist ? 'wishlist-button in-wishlist' : 'wishlist-button';

		echo '<a href="#" class="' . esc_attr( $class ) . '" data-product-id="' . esc_attr( $product_id ) . '" title="' . esc_attr( $title ) . '" rel="nofollow">';
		echo '<svg class="icon icon-heart" aria-hidden="true" focusable="false"><use xlink:href="#icon-heart"></use></svg>';
		echo '<span>' . esc_html__( 'Wishlist', 'aqualuxe' ) . '</span>';
		echo '</a>';
	}

	/**
	 * AJAX handler for toggling wishlist
	 */
	public function toggle_wishlist_ajax() {
		// Check nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'aqualuxe-woocommerce-nonce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ) );
		}

		// Check product ID
		if ( ! isset( $_POST['product_id'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID', 'aqualuxe' ) ) );
		}

		$product_id = absint( $_POST['product_id'] );
		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Product not found', 'aqualuxe' ) ) );
		}

		// Toggle wishlist
		$in_wishlist = $this->is_product_in_wishlist( $product_id );
		
		if ( $in_wishlist ) {
			$this->remove_from_wishlist( $product_id );
			$in_wishlist = false;
		} else {
			$this->add_to_wishlist( $product_id );
			$in_wishlist = true;
		}

		// Get wishlist count
		$count = $this->get_wishlist_count();

		wp_send_json_success( array(
			'in_wishlist' => $in_wishlist,
			'count'       => $count,
		) );
	}

	/**
	 * Check if product is in wishlist
	 *
	 * @param int $product_id Product ID
	 * @return bool
	 */
	public function is_product_in_wishlist( $product_id ) {
		$wishlist = $this->get_wishlist();
		return in_array( $product_id, $wishlist );
	}

	/**
	 * Add product to wishlist
	 *
	 * @param int $product_id Product ID
	 */
	public function add_to_wishlist( $product_id ) {
		$wishlist = $this->get_wishlist();
		
		if ( ! in_array( $product_id, $wishlist ) ) {
			$wishlist[] = $product_id;
			$this->save_wishlist( $wishlist );
		}
	}

	/**
	 * Remove product from wishlist
	 *
	 * @param int $product_id Product ID
	 */
	public function remove_from_wishlist( $product_id ) {
		$wishlist = $this->get_wishlist();
		
		if ( in_array( $product_id, $wishlist ) ) {
			$wishlist = array_diff( $wishlist, array( $product_id ) );
			$this->save_wishlist( $wishlist );
		}
	}

	/**
	 * Get wishlist
	 *
	 * @return array
	 */
	public function get_wishlist() {
		if ( is_user_logged_in() ) {
			// Get wishlist from user meta
			$user_id = get_current_user_id();
			$wishlist = get_user_meta( $user_id, '_aqualuxe_wishlist', true );
			
			if ( ! is_array( $wishlist ) ) {
				$wishlist = array();
			}
		} else {
			// Get wishlist from cookie
			$wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : array();
			
			if ( ! is_array( $wishlist ) ) {
				$wishlist = array();
			}
		}
		
		// Convert to integers
		$wishlist = array_map( 'absint', $wishlist );
		
		// Remove duplicates
		$wishlist = array_unique( $wishlist );
		
		return $wishlist;
	}

	/**
	 * Save wishlist
	 *
	 * @param array $wishlist Wishlist
	 */
	public function save_wishlist( $wishlist ) {
		if ( is_user_logged_in() ) {
			// Save wishlist to user meta
			$user_id = get_current_user_id();
			update_user_meta( $user_id, '_aqualuxe_wishlist', $wishlist );
		} else {
			// Save wishlist to cookie
			$expiry = time() + ( 30 * DAY_IN_SECONDS );
			setcookie( 'aqualuxe_wishlist', json_encode( $wishlist ), $expiry, COOKIEPATH, COOKIE_DOMAIN );
		}
	}

	/**
	 * Get wishlist count
	 *
	 * @return int
	 */
	public function get_wishlist_count() {
		$wishlist = $this->get_wishlist();
		return count( $wishlist );
	}

	/**
	 * Register wishlist endpoint
	 */
	public function register_wishlist_endpoint() {
		add_rewrite_endpoint( 'wishlist', EP_ROOT | EP_PAGES );
	}

	/**
	 * Add wishlist query var
	 *
	 * @param array $vars Query vars
	 * @return array
	 */
	public function add_wishlist_query_var( $vars ) {
		$vars[] = 'wishlist';
		return $vars;
	}

	/**
	 * Add wishlist to account menu
	 *
	 * @param array $items Menu items
	 * @return array
	 */
	public function add_wishlist_account_menu_item( $items ) {
		// Remove logout item
		$logout = $items['customer-logout'];
		unset( $items['customer-logout'] );
		
		// Add wishlist item
		$items['wishlist'] = esc_html__( 'Wishlist', 'aqualuxe' );
		
		// Add logout item back
		$items['customer-logout'] = $logout;
		
		return $items;
	}

	/**
	 * Wishlist content
	 */
	public function wishlist_content() {
		// Get wishlist
		$wishlist = $this->get_wishlist();
		
		// Display wishlist
		echo '<div class="aqualuxe-wishlist">';
		
		if ( empty( $wishlist ) ) {
			echo '<div class="wishlist-empty">';
			echo '<p>' . esc_html__( 'Your wishlist is currently empty.', 'aqualuxe' ) . '</p>';
			echo '<a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" class="button">' . esc_html__( 'Browse Products', 'aqualuxe' ) . '</a>';
			echo '</div>';
		} else {
			echo '<table class="wishlist-table">';
			echo '<thead>';
			echo '<tr>';
			echo '<th class="product-remove"></th>';
			echo '<th class="product-thumbnail"></th>';
			echo '<th class="product-name">' . esc_html__( 'Product', 'aqualuxe' ) . '</th>';
			echo '<th class="product-price">' . esc_html__( 'Price', 'aqualuxe' ) . '</th>';
			echo '<th class="product-stock">' . esc_html__( 'Stock', 'aqualuxe' ) . '</th>';
			echo '<th class="product-actions"></th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			
			foreach ( $wishlist as $product_id ) {
				$product = wc_get_product( $product_id );
				
				if ( ! $product ) {
					continue;
				}
				
				echo '<tr>';
				
				// Remove button
				echo '<td class="product-remove">';
				echo '<a href="#" class="remove-from-wishlist" data-product-id="' . esc_attr( $product_id ) . '" title="' . esc_attr__( 'Remove from wishlist', 'aqualuxe' ) . '">&times;</a>';
				echo '</td>';
				
				// Thumbnail
				echo '<td class="product-thumbnail">';
				echo '<a href="' . esc_url( $product->get_permalink() ) . '">';
				echo $product->get_image( 'thumbnail' );
				echo '</a>';
				echo '</td>';
				
				// Name
				echo '<td class="product-name">';
				echo '<a href="' . esc_url( $product->get_permalink() ) . '">' . esc_html( $product->get_name() ) . '</a>';
				echo '</td>';
				
				// Price
				echo '<td class="product-price">';
				echo $product->get_price_html();
				echo '</td>';
				
				// Stock
				echo '<td class="product-stock">';
				echo $product->is_in_stock() ? '<span class="in-stock">' . esc_html__( 'In Stock', 'aqualuxe' ) . '</span>' : '<span class="out-of-stock">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
				echo '</td>';
				
				// Actions
				echo '<td class="product-actions">';
				if ( $product->is_in_stock() ) {
					echo '<a href="' . esc_url( $product->add_to_cart_url() ) . '" class="button add_to_cart_button">' . esc_html__( 'Add to Cart', 'aqualuxe' ) . '</a>';
				} else {
					echo '<a href="' . esc_url( $product->get_permalink() ) . '" class="button">' . esc_html__( 'View Product', 'aqualuxe' ) . '</a>';
				}
				echo '</td>';
				
				echo '</tr>';
			}
			
			echo '</tbody>';
			echo '</table>';
			
			echo '<script>
				(function($) {
					$(document).ready(function() {
						$(".remove-from-wishlist").on("click", function(e) {
							e.preventDefault();
							
							var $button = $(this);
							var productId = $button.data("product-id");
							var $row = $button.closest("tr");
							
							// Show loading
							$row.addClass("removing");
							
							// Remove from wishlist via AJAX
							$.ajax({
								url: aqualuxeWooCommerce.ajaxUrl,
								type: "POST",
								data: {
									action: "aqualuxe_toggle_wishlist",
									product_id: productId,
									nonce: aqualuxeWooCommerce.nonce
								},
								success: function(response) {
									if (response.success) {
										// Remove row
										$row.fadeOut(300, function() {
											$(this).remove();
											
											// Check if wishlist is empty
											if ($(".wishlist-table tbody tr").length === 0) {
												$(".wishlist-table").replaceWith(\'<div class="wishlist-empty"><p>' . esc_js( __( 'Your wishlist is currently empty.', 'aqualuxe' ) ) . '</p><a href="' . esc_js( wc_get_page_permalink( 'shop' ) ) . '" class="button">' . esc_js( __( 'Browse Products', 'aqualuxe' ) ) . '</a></div>\');
											}
											
											// Update wishlist count
											$(".wishlist-count").text(response.data.count);
										});
									}
								},
								error: function() {
									// Remove loading
									$row.removeClass("removing");
								}
							});
						});
					});
				})(jQuery);
			</script>';
		}
		
		echo '</div>';
	}

	/**
	 * Wishlist shortcode
	 *
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	public function wishlist_shortcode( $atts ) {
		ob_start();
		$this->wishlist_content();
		return ob_get_clean();
	}

	/**
	 * Add script data
	 *
	 * @param array $data Script data
	 * @return array
	 */
	public function add_script_data( $data ) {
		$data['i18n']['add_to_wishlist'] = esc_html__( 'Add to Wishlist', 'aqualuxe' );
		$data['i18n']['remove_from_wishlist'] = esc_html__( 'Remove from Wishlist', 'aqualuxe' );
		
		return $data;
	}
}

// Initialize wishlist
new AquaLuxe_Wishlist();

/**
 * Wishlist button function for use in templates
 */
function aqualuxe_wishlist_button() {
	global $product;

	if ( ! $product || ! get_theme_mod( 'aqualuxe_wishlist', true ) ) {
		return;
	}

	$wishlist = new AquaLuxe_Wishlist();
	$product_id = $product->get_id();
	$in_wishlist = $wishlist->is_product_in_wishlist( $product_id );
	$title = $in_wishlist ? esc_html__( 'Remove from Wishlist', 'aqualuxe' ) : esc_html__( 'Add to Wishlist', 'aqualuxe' );
	$class = $in_wishlist ? 'wishlist-button in-wishlist' : 'wishlist-button';

	echo '<a href="#" class="' . esc_attr( $class ) . '" data-product-id="' . esc_attr( $product_id ) . '" title="' . esc_attr( $title ) . '" rel="nofollow">';
	echo '<svg class="icon icon-heart" aria-hidden="true" focusable="false"><use xlink:href="#icon-heart"></use></svg>';
	echo '<span>' . esc_html__( 'Wishlist', 'aqualuxe' ) . '</span>';
	echo '</a>';
}

/**
 * Get wishlist URL
 *
 * @return string
 */
function aqualuxe_get_wishlist_url() {
	if ( is_user_logged_in() ) {
		return wc_get_endpoint_url( 'wishlist', '', wc_get_page_permalink( 'myaccount' ) );
	} else {
		return wc_get_page_permalink( 'myaccount' );
	}
}

/**
 * Get wishlist count
 *
 * @return int
 */
function aqualuxe_get_wishlist_count() {
	$wishlist = new AquaLuxe_Wishlist();
	return $wishlist->get_wishlist_count();
}