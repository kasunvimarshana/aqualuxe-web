<?php
/**
 * AquaLuxe WooCommerce Wishlist Class
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
 * WooCommerce Wishlist Class
 *
 * Handles WooCommerce wishlist functionality.
 *
 * @since 1.1.0
 */
class Wishlist {

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

		// Check if wishlist is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_wishlist', true ) ) {
			return;
		}

		// Wishlist button.
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'wishlist_button' ), 11 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'wishlist_button' ), 35 );
		
		// Wishlist AJAX.
		add_action( 'wp_ajax_aqualuxe_wishlist_add', array( $this, 'wishlist_add_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_wishlist_add', array( $this, 'wishlist_add_ajax' ) );
		add_action( 'wp_ajax_aqualuxe_wishlist_remove', array( $this, 'wishlist_remove_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_wishlist_remove', array( $this, 'wishlist_remove_ajax' ) );
		
		// Wishlist page.
		add_action( 'init', array( $this, 'add_wishlist_endpoint' ) );
		add_filter( 'query_vars', array( $this, 'add_wishlist_query_var' ), 0 );
		add_action( 'woocommerce_account_wishlist_endpoint', array( $this, 'wishlist_content' ) );
		
		// Wishlist share.
		add_action( 'init', array( $this, 'add_wishlist_share_endpoint' ) );
		add_filter( 'query_vars', array( $this, 'add_wishlist_share_query_var' ), 0 );
		add_action( 'template_redirect', array( $this, 'wishlist_share_template' ) );
		
		// Wishlist count.
		add_action( 'wp_footer', array( $this, 'wishlist_count' ) );
		
		// User login/logout.
		add_action( 'wp_login', array( $this, 'user_login' ), 10, 2 );
		add_action( 'wp_logout', array( $this, 'user_logout' ) );
		
		// User registration.
		add_action( 'user_register', array( $this, 'user_register' ) );
	}

	/**
	 * Wishlist button.
	 *
	 * @return void
	 */
	public function wishlist_button() {
		global $product;
		
		// Check if wishlist is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_wishlist', true ) ) {
			return;
		}
		
		// Check if product is in wishlist.
		$wishlist = $this->get_wishlist();
		$in_wishlist = in_array( $product->get_id(), $wishlist, true );
		
		// Get button style.
		$button_style = get_theme_mod( 'aqualuxe_wishlist_button_style', 'icon-text' );
		
		// Get button icon.
		$button_icon = get_theme_mod( 'aqualuxe_wishlist_button_icon', 'heart' );
		
		// Get button text.
		$add_text = get_theme_mod( 'aqualuxe_wishlist_button_add_text', __( 'Add to wishlist', 'aqualuxe' ) );
		$added_text = get_theme_mod( 'aqualuxe_wishlist_button_added_text', __( 'Added to wishlist', 'aqualuxe' ) );
		
		// Output button.
		echo '<div class="wishlist-button">';
		echo '<a href="#" class="button wishlist-add' . ( $in_wishlist ? ' in-wishlist' : '' ) . '" data-product-id="' . esc_attr( $product->get_id() ) . '">';
		
		if ( 'icon-text' === $button_style || 'icon-only' === $button_style ) {
			echo '<i class="fa' . ( $in_wishlist ? 's' : 'r' ) . ' fa-' . esc_attr( $button_icon ) . '"></i>';
		}
		
		if ( 'icon-text' === $button_style || 'text-only' === $button_style ) {
			echo '<span class="wishlist-text">';
			
			if ( $in_wishlist ) {
				echo esc_html( $added_text );
			} else {
				echo esc_html( $add_text );
			}
			
			echo '</span>';
		}
		
		echo '</a>';
		echo '</div>';
	}

	/**
	 * Wishlist add AJAX.
	 *
	 * @return void
	 */
	public function wishlist_add_ajax() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-woocommerce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ) );
		}
		
		// Check product ID.
		if ( ! isset( $_POST['product_id'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID.', 'aqualuxe' ) ) );
		}
		
		// Get product.
		$product_id = absint( $_POST['product_id'] );
		$product    = wc_get_product( $product_id );
		
		if ( ! $product ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Product not found.', 'aqualuxe' ) ) );
		}
		
		// Get wishlist.
		$wishlist = $this->get_wishlist();
		
		// Check if product is in wishlist.
		$in_wishlist = in_array( $product_id, $wishlist, true );
		
		if ( $in_wishlist ) {
			// Remove from wishlist.
			$wishlist = array_diff( $wishlist, array( $product_id ) );
			$this->set_wishlist( $wishlist );
			
			wp_send_json_success(
				array(
					'message'    => esc_html__( 'Product removed from wishlist.', 'aqualuxe' ),
					'in_wishlist' => false,
					'text'       => esc_html__( 'Add to wishlist', 'aqualuxe' ),
					'count'      => count( $wishlist ),
				)
			);
		} else {
			// Add to wishlist.
			$wishlist[] = $product_id;
			$this->set_wishlist( $wishlist );
			
			wp_send_json_success(
				array(
					'message'    => esc_html__( 'Product added to wishlist.', 'aqualuxe' ),
					'in_wishlist' => true,
					'text'       => esc_html__( 'Added to wishlist', 'aqualuxe' ),
					'count'      => count( $wishlist ),
				)
			);
		}
	}

	/**
	 * Wishlist remove AJAX.
	 *
	 * @return void
	 */
	public function wishlist_remove_ajax() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-woocommerce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ) );
		}
		
		// Check product ID.
		if ( ! isset( $_POST['product_id'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID.', 'aqualuxe' ) ) );
		}
		
		// Get product.
		$product_id = absint( $_POST['product_id'] );
		$product    = wc_get_product( $product_id );
		
		if ( ! $product ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Product not found.', 'aqualuxe' ) ) );
		}
		
		// Get wishlist.
		$wishlist = $this->get_wishlist();
		
		// Remove from wishlist.
		$wishlist = array_diff( $wishlist, array( $product_id ) );
		$this->set_wishlist( $wishlist );
		
		wp_send_json_success(
			array(
				'message' => esc_html__( 'Product removed from wishlist.', 'aqualuxe' ),
				'count'   => count( $wishlist ),
			)
		);
	}

	/**
	 * Add wishlist endpoint.
	 *
	 * @return void
	 */
	public function add_wishlist_endpoint() {
		add_rewrite_endpoint( 'wishlist', EP_ROOT | EP_PAGES );
	}

	/**
	 * Add wishlist query var.
	 *
	 * @param array $vars Query vars.
	 * @return array
	 */
	public function add_wishlist_query_var( $vars ) {
		$vars[] = 'wishlist';
		return $vars;
	}

	/**
	 * Wishlist content.
	 *
	 * @return void
	 */
	public function wishlist_content() {
		// Get wishlist.
		$wishlist = $this->get_wishlist();
		
		// Get wishlist style.
		$wishlist_style = get_theme_mod( 'aqualuxe_wishlist_style', 'grid' );
		
		// Get wishlist columns.
		$wishlist_columns = get_theme_mod( 'aqualuxe_wishlist_columns', 4 );
		
		// Output wishlist.
		echo '<div class="wishlist-content">';
		
		// Output wishlist header.
		echo '<div class="wishlist-header">';
		echo '<h2>' . esc_html__( 'My Wishlist', 'aqualuxe' ) . '</h2>';
		
		// Output wishlist share button.
		if ( get_theme_mod( 'aqualuxe_wishlist_share', true ) ) {
			echo '<div class="wishlist-share">';
			echo '<a href="' . esc_url( $this->get_wishlist_share_url() ) . '" class="button wishlist-share-button">';
			echo '<i class="fas fa-share-alt"></i>';
			echo '<span>' . esc_html__( 'Share Wishlist', 'aqualuxe' ) . '</span>';
			echo '</a>';
			echo '</div>';
		}
		
		echo '</div>';
		
		// Check if wishlist is empty.
		if ( empty( $wishlist ) ) {
			// Output empty wishlist message.
			echo '<div class="wishlist-empty">';
			echo '<p>' . esc_html__( 'Your wishlist is empty.', 'aqualuxe' ) . '</p>';
			echo '<a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" class="button">' . esc_html__( 'Browse products', 'aqualuxe' ) . '</a>';
			echo '</div>';
		} else {
			// Output wishlist products.
			echo '<div class="wishlist-products layout-' . esc_attr( $wishlist_style ) . ' columns-' . esc_attr( $wishlist_columns ) . '">';
			
			// Get products.
			$args = array(
				'post_type'      => 'product',
				'post__in'       => $wishlist,
				'posts_per_page' => -1,
				'orderby'        => 'post__in',
				'post_status'    => 'publish',
			);
			
			$products = new \WP_Query( $args );
			
			if ( $products->have_posts() ) {
				while ( $products->have_posts() ) {
					$products->the_post();
					global $product;
					
					// Output product.
					echo '<div class="wishlist-product">';
					
					// Output product image.
					echo '<div class="wishlist-product-image">';
					echo '<a href="' . esc_url( get_permalink() ) . '">';
					echo $product->get_image( 'woocommerce_thumbnail' );
					echo '</a>';
					echo '</div>';
					
					// Output product details.
					echo '<div class="wishlist-product-details">';
					
					// Output product title.
					echo '<h3 class="wishlist-product-title">';
					echo '<a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a>';
					echo '</h3>';
					
					// Output product price.
					echo '<div class="wishlist-product-price">';
					echo $product->get_price_html();
					echo '</div>';
					
					// Output product rating.
					if ( get_theme_mod( 'aqualuxe_wishlist_show_rating', true ) ) {
						echo '<div class="wishlist-product-rating">';
						echo wc_get_rating_html( $product->get_average_rating() );
						echo '</div>';
					}
					
					// Output product stock status.
					if ( get_theme_mod( 'aqualuxe_wishlist_show_stock', true ) ) {
						echo '<div class="wishlist-product-stock">';
						echo wc_get_stock_html( $product );
						echo '</div>';
					}
					
					// Output product buttons.
					echo '<div class="wishlist-product-buttons">';
					
					// Output add to cart button.
					echo '<div class="wishlist-product-cart">';
					woocommerce_template_loop_add_to_cart();
					echo '</div>';
					
					// Output remove button.
					echo '<div class="wishlist-product-remove">';
					echo '<a href="#" class="button wishlist-remove" data-product-id="' . esc_attr( $product->get_id() ) . '">';
					echo '<i class="fas fa-trash-alt"></i>';
					echo '<span>' . esc_html__( 'Remove', 'aqualuxe' ) . '</span>';
					echo '</a>';
					echo '</div>';
					
					echo '</div>';
					
					echo '</div>';
					
					echo '</div>';
				}
				
				wp_reset_postdata();
			}
			
			echo '</div>';
			
			// Output wishlist actions.
			echo '<div class="wishlist-actions">';
			
			// Output add all to cart button.
			if ( get_theme_mod( 'aqualuxe_wishlist_add_all_to_cart', true ) ) {
				echo '<a href="#" class="button wishlist-add-all-to-cart">';
				echo '<i class="fas fa-shopping-cart"></i>';
				echo '<span>' . esc_html__( 'Add all to cart', 'aqualuxe' ) . '</span>';
				echo '</a>';
			}
			
			// Output clear wishlist button.
			if ( get_theme_mod( 'aqualuxe_wishlist_clear', true ) ) {
				echo '<a href="#" class="button wishlist-clear">';
				echo '<i class="fas fa-trash-alt"></i>';
				echo '<span>' . esc_html__( 'Clear wishlist', 'aqualuxe' ) . '</span>';
				echo '</a>';
			}
			
			echo '</div>';
		}
		
		echo '</div>';
	}

	/**
	 * Add wishlist share endpoint.
	 *
	 * @return void
	 */
	public function add_wishlist_share_endpoint() {
		add_rewrite_endpoint( 'wishlist-share', EP_ROOT | EP_PAGES );
	}

	/**
	 * Add wishlist share query var.
	 *
	 * @param array $vars Query vars.
	 * @return array
	 */
	public function add_wishlist_share_query_var( $vars ) {
		$vars[] = 'wishlist-share';
		return $vars;
	}

	/**
	 * Wishlist share template.
	 *
	 * @return void
	 */
	public function wishlist_share_template() {
		global $wp_query;
		
		// Check if wishlist share endpoint.
		if ( isset( $wp_query->query_vars['wishlist-share'] ) ) {
			// Get wishlist ID.
			$wishlist_id = get_query_var( 'wishlist-share' );
			
			// Check if wishlist ID is valid.
			if ( empty( $wishlist_id ) ) {
				wp_redirect( wc_get_page_permalink( 'shop' ) );
				exit;
			}
			
			// Get wishlist.
			$wishlist = get_user_meta( $wishlist_id, 'aqualuxe_wishlist', true );
			
			// Check if wishlist exists.
			if ( empty( $wishlist ) ) {
				wp_redirect( wc_get_page_permalink( 'shop' ) );
				exit;
			}
			
			// Load wishlist share template.
			wc_get_template( 'wishlist-share.php', array( 'wishlist' => $wishlist, 'user_id' => $wishlist_id ) );
			exit;
		}
	}

	/**
	 * Wishlist count.
	 *
	 * @return void
	 */
	public function wishlist_count() {
		// Check if wishlist count is enabled.
		if ( ! get_theme_mod( 'aqualuxe_wishlist_count', true ) ) {
			return;
		}
		
		// Get wishlist.
		$wishlist = $this->get_wishlist();
		
		// Get wishlist count.
		$count = count( $wishlist );
		
		// Output wishlist count.
		echo '<div class="wishlist-count-wrapper">';
		echo '<a href="' . esc_url( wc_get_account_endpoint_url( 'wishlist' ) ) . '" class="wishlist-count">';
		echo '<i class="far fa-heart"></i>';
		echo '<span class="count">' . esc_html( $count ) . '</span>';
		echo '</a>';
		echo '</div>';
	}

	/**
	 * User login.
	 *
	 * @param string  $user_login Username.
	 * @param WP_User $user User object.
	 * @return void
	 */
	public function user_login( $user_login, $user ) {
		// Get cookie wishlist.
		$cookie_wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : array();
		
		if ( ! $cookie_wishlist ) {
			$cookie_wishlist = array();
		}
		
		// Get user wishlist.
		$user_wishlist = get_user_meta( $user->ID, 'aqualuxe_wishlist', true );
		
		if ( ! $user_wishlist ) {
			$user_wishlist = array();
		}
		
		// Merge wishlists.
		$wishlist = array_unique( array_merge( $cookie_wishlist, $user_wishlist ) );
		
		// Update user wishlist.
		update_user_meta( $user->ID, 'aqualuxe_wishlist', $wishlist );
		
		// Clear cookie wishlist.
		setcookie( 'aqualuxe_wishlist', '', time() - HOUR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
	}

	/**
	 * User logout.
	 *
	 * @return void
	 */
	public function user_logout() {
		// Clear cookie wishlist.
		setcookie( 'aqualuxe_wishlist', '', time() - HOUR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
	}

	/**
	 * User register.
	 *
	 * @param int $user_id User ID.
	 * @return void
	 */
	public function user_register( $user_id ) {
		// Get cookie wishlist.
		$cookie_wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : array();
		
		if ( ! $cookie_wishlist ) {
			$cookie_wishlist = array();
		}
		
		// Update user wishlist.
		update_user_meta( $user_id, 'aqualuxe_wishlist', $cookie_wishlist );
		
		// Clear cookie wishlist.
		setcookie( 'aqualuxe_wishlist', '', time() - HOUR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
	}

	/**
	 * Get wishlist.
	 *
	 * @return array
	 */
	public function get_wishlist() {
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

	/**
	 * Set wishlist.
	 *
	 * @param array $wishlist Wishlist.
	 * @return void
	 */
	public function set_wishlist( $wishlist ) {
		// Check if user is logged in.
		if ( is_user_logged_in() ) {
			// Set wishlist to user meta.
			update_user_meta( get_current_user_id(), 'aqualuxe_wishlist', $wishlist );
		} else {
			// Set wishlist to cookie.
			setcookie( 'aqualuxe_wishlist', wp_json_encode( $wishlist ), time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
		}
	}

	/**
	 * Get wishlist share URL.
	 *
	 * @return string
	 */
	public function get_wishlist_share_url() {
		// Check if user is logged in.
		if ( ! is_user_logged_in() ) {
			return '';
		}
		
		// Get user ID.
		$user_id = get_current_user_id();
		
		// Get wishlist share URL.
		$url = home_url( 'wishlist-share/' . $user_id );
		
		return $url;
	}
}