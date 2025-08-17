<?php
/**
 * WooCommerce Wishlist Class
 *
 * @package AquaLuxe
 * @subpackage WooCommerce
 */

namespace AquaLuxe\WooCommerce;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wishlist Class
 *
 * Handles wishlist functionality for WooCommerce.
 */
class Wishlist {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Add wishlist button to product loops.
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'add_wishlist_button' ), 15 );
		
		// Add wishlist button to single product.
		add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'add_wishlist_button_single' ), 10 );
		
		// Register wishlist endpoint.
		add_action( 'init', array( $this, 'add_wishlist_endpoint' ) );
		add_filter( 'query_vars', array( $this, 'add_wishlist_query_vars' ), 0 );
		add_filter( 'woocommerce_account_menu_items', array( $this, 'add_wishlist_menu_item' ) );
		
		// Add wishlist content.
		add_action( 'woocommerce_account_wishlist_endpoint', array( $this, 'wishlist_content' ) );
		
		// AJAX handlers.
		add_action( 'wp_ajax_aqualuxe_add_to_wishlist', array( $this, 'ajax_add_to_wishlist' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_add_to_wishlist', array( $this, 'ajax_add_to_wishlist' ) );
		add_action( 'wp_ajax_aqualuxe_remove_from_wishlist', array( $this, 'ajax_remove_from_wishlist' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_remove_from_wishlist', array( $this, 'ajax_remove_from_wishlist' ) );
		
		// Enqueue scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		// Add wishlist count to header.
		add_action( 'aqualuxe_header_icons', array( $this, 'wishlist_header_icon' ) );
		
		// Add wishlist share functionality.
		add_action( 'wp_ajax_aqualuxe_share_wishlist', array( $this, 'ajax_share_wishlist' ) );
		
		// Add public wishlist view.
		add_action( 'init', array( $this, 'add_public_wishlist_endpoint' ) );
		add_action( 'template_redirect', array( $this, 'handle_public_wishlist' ) );
	}

	/**
	 * Add wishlist button to product loops
	 */
	public function add_wishlist_button() {
		global $product;
		
		// Only show if wishlist is enabled.
		if ( ! get_theme_mod( 'aqualuxe_product_wishlist', true ) ) {
			return;
		}
		
		$product_id = $product->get_id();
		$in_wishlist = $this->is_product_in_wishlist( $product_id );
		$button_class = $in_wishlist ? 'aqualuxe-wishlist-button added' : 'aqualuxe-wishlist-button';
		$button_text = $in_wishlist ? esc_html__( 'Remove from Wishlist', 'aqualuxe' ) : esc_html__( 'Add to Wishlist', 'aqualuxe' );
		$button_icon = $in_wishlist ? 'fas fa-heart' : 'far fa-heart';
		
		echo '<a href="#" class="' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '" data-product-type="' . esc_attr( $product->get_type() ) . '" aria-label="' . esc_attr( $button_text ) . '">';
		echo '<i class="' . esc_attr( $button_icon ) . '"></i>';
		echo '</a>';
	}

	/**
	 * Add wishlist button to single product
	 */
	public function add_wishlist_button_single() {
		global $product;
		
		// Only show if wishlist is enabled.
		if ( ! get_theme_mod( 'aqualuxe_product_wishlist', true ) ) {
			return;
		}
		
		$product_id = $product->get_id();
		$in_wishlist = $this->is_product_in_wishlist( $product_id );
		$button_class = $in_wishlist ? 'aqualuxe-wishlist-button-single added' : 'aqualuxe-wishlist-button-single';
		$button_text = $in_wishlist ? esc_html__( 'Remove from Wishlist', 'aqualuxe' ) : esc_html__( 'Add to Wishlist', 'aqualuxe' );
		$button_icon = $in_wishlist ? 'fas fa-heart' : 'far fa-heart';
		
		echo '<button type="button" class="' . esc_attr( $button_class ) . '" data-product-id="' . esc_attr( $product_id ) . '" data-product-type="' . esc_attr( $product->get_type() ) . '">';
		echo '<i class="' . esc_attr( $button_icon ) . '"></i>';
		echo '<span>' . esc_html( $button_text ) . '</span>';
		echo '</button>';
	}

	/**
	 * Add wishlist endpoint
	 */
	public function add_wishlist_endpoint() {
		add_rewrite_endpoint( 'wishlist', EP_ROOT | EP_PAGES );
	}

	/**
	 * Add wishlist query vars
	 *
	 * @param array $vars Query vars.
	 * @return array Modified query vars.
	 */
	public function add_wishlist_query_vars( $vars ) {
		$vars[] = 'wishlist';
		return $vars;
	}

	/**
	 * Add wishlist menu item
	 *
	 * @param array $items Menu items.
	 * @return array Modified menu items.
	 */
	public function add_wishlist_menu_item( $items ) {
		// Add wishlist item after orders.
		$new_items = array();
		
		foreach ( $items as $key => $value ) {
			$new_items[ $key ] = $value;
			
			if ( 'orders' === $key ) {
				$new_items['wishlist'] = esc_html__( 'Wishlist', 'aqualuxe' );
			}
		}
		
		return $new_items;
	}

	/**
	 * Wishlist content
	 */
	public function wishlist_content() {
		$wishlist_items = $this->get_wishlist_items();
		
		echo '<div class="aqualuxe-wishlist-content">';
		echo '<h2>' . esc_html__( 'My Wishlist', 'aqualuxe' ) . '</h2>';
		
		if ( empty( $wishlist_items ) ) {
			echo '<div class="aqualuxe-wishlist-empty">';
			echo '<p>' . esc_html__( 'Your wishlist is currently empty.', 'aqualuxe' ) . '</p>';
			echo '<a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" class="button">';
			echo esc_html__( 'Browse Products', 'aqualuxe' );
			echo '</a>';
			echo '</div>';
		} else {
			echo '<div class="aqualuxe-wishlist-actions">';
			echo '<button type="button" class="aqualuxe-share-wishlist button">';
			echo '<i class="fas fa-share-alt"></i>';
			echo '<span>' . esc_html__( 'Share Wishlist', 'aqualuxe' ) . '</span>';
			echo '</button>';
			
			echo '<button type="button" class="aqualuxe-clear-wishlist button">';
			echo '<i class="fas fa-trash-alt"></i>';
			echo '<span>' . esc_html__( 'Clear Wishlist', 'aqualuxe' ) . '</span>';
			echo '</button>';
			echo '</div>';
			
			echo '<div class="aqualuxe-wishlist-items">';
			
			foreach ( $wishlist_items as $item ) {
				$product_id = $item['product_id'];
				$product = wc_get_product( $product_id );
				
				if ( ! $product ) {
					continue;
				}
				
				echo '<div class="aqualuxe-wishlist-item" data-product-id="' . esc_attr( $product_id ) . '">';
				
				echo '<div class="aqualuxe-wishlist-item-image">';
				echo '<a href="' . esc_url( $product->get_permalink() ) . '">';
				echo $product->get_image( 'woocommerce_thumbnail' );
				echo '</a>';
				echo '</div>';
				
				echo '<div class="aqualuxe-wishlist-item-details">';
				echo '<h3 class="aqualuxe-wishlist-item-title">';
				echo '<a href="' . esc_url( $product->get_permalink() ) . '">';
				echo esc_html( $product->get_name() );
				echo '</a>';
				echo '</h3>';
				
				echo '<div class="aqualuxe-wishlist-item-price">';
				echo $product->get_price_html();
				echo '</div>';
				
				echo '<div class="aqualuxe-wishlist-item-stock">';
				if ( $product->is_in_stock() ) {
					echo '<span class="in-stock">' . esc_html__( 'In Stock', 'aqualuxe' ) . '</span>';
				} else {
					echo '<span class="out-of-stock">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
				}
				echo '</div>';
				
				echo '<div class="aqualuxe-wishlist-item-actions">';
				if ( $product->is_in_stock() ) {
					echo '<a href="' . esc_url( $product->add_to_cart_url() ) . '" class="button add_to_cart_button ajax_add_to_cart" data-product_id="' . esc_attr( $product_id ) . '" data-product_sku="' . esc_attr( $product->get_sku() ) . '">';
					echo esc_html__( 'Add to Cart', 'aqualuxe' );
					echo '</a>';
				}
				
				echo '<button type="button" class="aqualuxe-remove-from-wishlist" data-product-id="' . esc_attr( $product_id ) . '">';
				echo '<i class="fas fa-trash-alt"></i>';
				echo '<span class="screen-reader-text">' . esc_html__( 'Remove', 'aqualuxe' ) . '</span>';
				echo '</button>';
				echo '</div>';
				
				echo '</div>';
				echo '</div>';
			}
			
			echo '</div>';
			
			// Share wishlist modal.
			echo '<div class="aqualuxe-wishlist-share-modal" style="display: none;">';
			echo '<div class="aqualuxe-wishlist-share-modal-content">';
			echo '<span class="aqualuxe-wishlist-share-close">&times;</span>';
			echo '<h3>' . esc_html__( 'Share Your Wishlist', 'aqualuxe' ) . '</h3>';
			echo '<p>' . esc_html__( 'Copy the link below to share your wishlist with friends and family.', 'aqualuxe' ) . '</p>';
			
			echo '<div class="aqualuxe-wishlist-share-link">';
			echo '<input type="text" id="aqualuxe-wishlist-share-url" value="" readonly>';
			echo '<button type="button" class="aqualuxe-copy-wishlist-link button">';
			echo esc_html__( 'Copy Link', 'aqualuxe' );
			echo '</button>';
			echo '</div>';
			
			echo '<div class="aqualuxe-wishlist-share-social">';
			echo '<p>' . esc_html__( 'Or share directly:', 'aqualuxe' ) . '</p>';
			echo '<a href="#" class="aqualuxe-wishlist-share-facebook"><i class="fab fa-facebook-f"></i></a>';
			echo '<a href="#" class="aqualuxe-wishlist-share-twitter"><i class="fab fa-twitter"></i></a>';
			echo '<a href="#" class="aqualuxe-wishlist-share-email"><i class="fas fa-envelope"></i></a>';
			echo '<a href="#" class="aqualuxe-wishlist-share-whatsapp"><i class="fab fa-whatsapp"></i></a>';
			echo '</div>';
			
			echo '</div>';
			echo '</div>';
		}
		
		echo '</div>';
	}

	/**
	 * AJAX add to wishlist
	 */
	public function ajax_add_to_wishlist() {
		// Check nonce.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_wishlist_nonce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Security check failed.', 'aqualuxe' ) ) );
		}
		
		// Get product ID.
		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		
		if ( ! $product_id ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid product.', 'aqualuxe' ) ) );
		}
		
		// Get current wishlist.
		$wishlist = $this->get_wishlist();
		
		// Check if product is already in wishlist.
		if ( in_array( $product_id, array_column( $wishlist, 'product_id' ), true ) ) {
			// Remove from wishlist.
			foreach ( $wishlist as $key => $item ) {
				if ( $item['product_id'] === $product_id ) {
					unset( $wishlist[ $key ] );
					break;
				}
			}
			
			// Reset array keys.
			$wishlist = array_values( $wishlist );
			
			// Save wishlist.
			$this->save_wishlist( $wishlist );
			
			wp_send_json_success( array(
				'message'   => esc_html__( 'Product removed from wishlist.', 'aqualuxe' ),
				'action'    => 'removed',
				'count'     => count( $wishlist ),
			) );
		} else {
			// Add to wishlist.
			$wishlist[] = array(
				'product_id' => $product_id,
				'date_added' => current_time( 'timestamp' ),
			);
			
			// Save wishlist.
			$this->save_wishlist( $wishlist );
			
			wp_send_json_success( array(
				'message'   => esc_html__( 'Product added to wishlist.', 'aqualuxe' ),
				'action'    => 'added',
				'count'     => count( $wishlist ),
			) );
		}
	}

	/**
	 * AJAX remove from wishlist
	 */
	public function ajax_remove_from_wishlist() {
		// Check nonce.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_wishlist_nonce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Security check failed.', 'aqualuxe' ) ) );
		}
		
		// Get product ID.
		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		
		if ( ! $product_id ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid product.', 'aqualuxe' ) ) );
		}
		
		// Get current wishlist.
		$wishlist = $this->get_wishlist();
		
		// Remove from wishlist.
		foreach ( $wishlist as $key => $item ) {
			if ( $item['product_id'] === $product_id ) {
				unset( $wishlist[ $key ] );
				break;
			}
		}
		
		// Reset array keys.
		$wishlist = array_values( $wishlist );
		
		// Save wishlist.
		$this->save_wishlist( $wishlist );
		
		wp_send_json_success( array(
			'message'   => esc_html__( 'Product removed from wishlist.', 'aqualuxe' ),
			'count'     => count( $wishlist ),
		) );
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			'aqualuxe-wishlist',
			get_template_directory_uri() . '/assets/js/wishlist.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
		
		wp_localize_script(
			'aqualuxe-wishlist',
			'aqualuxeWishlist',
			array(
				'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'aqualuxe_wishlist_nonce' ),
				'addText'   => esc_html__( 'Add to Wishlist', 'aqualuxe' ),
				'removeText' => esc_html__( 'Remove from Wishlist', 'aqualuxe' ),
				'addIcon'   => 'far fa-heart',
				'removeIcon' => 'fas fa-heart',
				'addedText' => esc_html__( 'Added to wishlist', 'aqualuxe' ),
				'removedText' => esc_html__( 'Removed from wishlist', 'aqualuxe' ),
				'shareUrl'  => home_url( 'shared-wishlist/' ),
				'loginUrl'  => wc_get_page_permalink( 'myaccount' ),
				'isLoggedIn' => is_user_logged_in(),
				'loginMessage' => esc_html__( 'Please log in to add items to your wishlist.', 'aqualuxe' ),
			)
		);
	}

	/**
	 * Wishlist header icon
	 */
	public function wishlist_header_icon() {
		// Only show if wishlist is enabled.
		if ( ! get_theme_mod( 'aqualuxe_product_wishlist', true ) ) {
			return;
		}
		
		$wishlist_count = count( $this->get_wishlist_items() );
		$wishlist_url = wc_get_account_endpoint_url( 'wishlist' );
		
		echo '<a href="' . esc_url( $wishlist_url ) . '" class="aqualuxe-header-wishlist">';
		echo '<i class="far fa-heart"></i>';
		echo '<span class="aqualuxe-header-wishlist-count">' . esc_html( $wishlist_count ) . '</span>';
		echo '</a>';
	}

	/**
	 * AJAX share wishlist
	 */
	public function ajax_share_wishlist() {
		// Check nonce.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_wishlist_nonce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Security check failed.', 'aqualuxe' ) ) );
		}
		
		// Get current user ID.
		$user_id = get_current_user_id();
		
		if ( ! $user_id ) {
			wp_send_json_error( array( 'message' => esc_html__( 'You must be logged in to share your wishlist.', 'aqualuxe' ) ) );
		}
		
		// Generate a unique token.
		$token = md5( $user_id . time() );
		
		// Save token to user meta.
		update_user_meta( $user_id, 'aqualuxe_wishlist_share_token', $token );
		
		// Generate share URL.
		$share_url = home_url( 'shared-wishlist/' . $token );
		
		wp_send_json_success( array(
			'shareUrl' => $share_url,
		) );
	}

	/**
	 * Add public wishlist endpoint
	 */
	public function add_public_wishlist_endpoint() {
		add_rewrite_rule( 'shared-wishlist/([^/]+)/?$', 'index.php?shared_wishlist=$matches[1]', 'top' );
		add_rewrite_tag( '%shared_wishlist%', '([^/]+)' );
	}

	/**
	 * Handle public wishlist
	 */
	public function handle_public_wishlist() {
		global $wp_query;
		
		if ( ! isset( $wp_query->query_vars['shared_wishlist'] ) ) {
			return;
		}
		
		$token = $wp_query->query_vars['shared_wishlist'];
		
		// Find user by token.
		$args = array(
			'meta_key'   => 'aqualuxe_wishlist_share_token',
			'meta_value' => $token,
			'number'     => 1,
		);
		
		$user_query = new \WP_User_Query( $args );
		$users = $user_query->get_results();
		
		if ( empty( $users ) ) {
			wp_redirect( home_url() );
			exit;
		}
		
		$user = $users[0];
		
		// Get user's wishlist.
		$wishlist = $this->get_user_wishlist( $user->ID );
		
		// Set up template.
		include get_template_directory() . '/woocommerce/shared-wishlist.php';
		exit;
	}

	/**
	 * Get wishlist
	 *
	 * @return array Wishlist items.
	 */
	private function get_wishlist() {
		if ( is_user_logged_in() ) {
			// Get wishlist from user meta.
			$user_id = get_current_user_id();
			return $this->get_user_wishlist( $user_id );
		} else {
			// Get wishlist from session.
			$wishlist = WC()->session->get( 'aqualuxe_wishlist', array() );
			return is_array( $wishlist ) ? $wishlist : array();
		}
	}

	/**
	 * Get user wishlist
	 *
	 * @param int $user_id User ID.
	 * @return array Wishlist items.
	 */
	private function get_user_wishlist( $user_id ) {
		$wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );
		return is_array( $wishlist ) ? $wishlist : array();
	}

	/**
	 * Save wishlist
	 *
	 * @param array $wishlist Wishlist items.
	 */
	private function save_wishlist( $wishlist ) {
		if ( is_user_logged_in() ) {
			// Save wishlist to user meta.
			$user_id = get_current_user_id();
			update_user_meta( $user_id, 'aqualuxe_wishlist', $wishlist );
		} else {
			// Save wishlist to session.
			WC()->session->set( 'aqualuxe_wishlist', $wishlist );
		}
	}

	/**
	 * Get wishlist items
	 *
	 * @return array Wishlist items.
	 */
	private function get_wishlist_items() {
		return $this->get_wishlist();
	}

	/**
	 * Check if product is in wishlist
	 *
	 * @param int $product_id Product ID.
	 * @return bool Whether product is in wishlist.
	 */
	private function is_product_in_wishlist( $product_id ) {
		$wishlist = $this->get_wishlist();
		return in_array( $product_id, array_column( $wishlist, 'product_id' ), true );
	}
}

// Initialize the class.
new Wishlist();