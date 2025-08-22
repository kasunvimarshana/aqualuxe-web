<?php
/**
 * AquaLuxe Theme Ajax
 *
 * This file contains the Ajax class for the AquaLuxe theme.
 *
 * @package AquaLuxe\Core
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ajax class.
 */
class Ajax {
	/**
	 * Constructor.
	 */
	public function __construct() {
		// Register AJAX actions.
		$this->register_ajax_actions();
	}

	/**
	 * Register AJAX actions.
	 */
	private function register_ajax_actions() {
		// Register AJAX actions for both logged in and non-logged in users.
		$ajax_actions = [
			'aqualuxe_load_more_posts',
			'aqualuxe_search_products',
			'aqualuxe_filter_products',
			'aqualuxe_quick_view',
			'aqualuxe_add_to_cart',
			'aqualuxe_update_cart',
			'aqualuxe_remove_from_cart',
			'aqualuxe_toggle_wishlist',
			'aqualuxe_toggle_compare',
			'aqualuxe_newsletter_subscribe',
			'aqualuxe_contact_form',
		];

		foreach ( $ajax_actions as $action ) {
			add_action( 'wp_ajax_' . $action, [ $this, $action ] );
			add_action( 'wp_ajax_nopriv_' . $action, [ $this, $action ] );
		}

		// Register AJAX actions for logged in users only.
		$logged_in_ajax_actions = [
			'aqualuxe_save_account_details',
			'aqualuxe_save_address',
			'aqualuxe_cancel_order',
			'aqualuxe_add_review',
		];

		foreach ( $logged_in_ajax_actions as $action ) {
			add_action( 'wp_ajax_' . $action, [ $this, $action ] );
		}
	}

	/**
	 * Load more posts.
	 */
	public function aqualuxe_load_more_posts() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-load-more-nonce', 'nonce', false ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ] );
		}

		// Get the query parameters.
		$paged = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;
		$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : 'post';
		$category = isset( $_POST['category'] ) ? absint( $_POST['category'] ) : 0;
		$tag = isset( $_POST['tag'] ) ? absint( $_POST['tag'] ) : 0;
		$posts_per_page = isset( $_POST['posts_per_page'] ) ? absint( $_POST['posts_per_page'] ) : get_option( 'posts_per_page' );

		// Build the query arguments.
		$args = [
			'post_type'      => $post_type,
			'paged'          => $paged,
			'posts_per_page' => $posts_per_page,
		];

		// Add category filter.
		if ( $category > 0 ) {
			$args['cat'] = $category;
		}

		// Add tag filter.
		if ( $tag > 0 ) {
			$args['tag_id'] = $tag;
		}

		// Run the query.
		$query = new \WP_Query( $args );

		// Check if we have posts.
		if ( $query->have_posts() ) {
			ob_start();

			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part( 'templates/parts/content/content', get_post_type() );
			}

			$html = ob_get_clean();

			wp_send_json_success(
				[
					'html'       => $html,
					'found'      => $query->found_posts,
					'remaining'  => $query->found_posts - ( $paged * $posts_per_page ),
					'max_pages'  => $query->max_num_pages,
					'next_page'  => $paged + 1,
				]
			);
		} else {
			wp_send_json_error( [ 'message' => __( 'No more posts found.', 'aqualuxe' ) ] );
		}

		wp_reset_postdata();
		wp_die();
	}

	/**
	 * Search products.
	 */
	public function aqualuxe_search_products() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			wp_send_json_error( [ 'message' => __( 'WooCommerce is not active.', 'aqualuxe' ) ] );
		}

		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-search-nonce', 'nonce', false ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ] );
		}

		// Get the search term.
		$search_term = isset( $_POST['search_term'] ) ? sanitize_text_field( $_POST['search_term'] ) : '';

		// Check if search term is empty.
		if ( empty( $search_term ) ) {
			wp_send_json_error( [ 'message' => __( 'Please enter a search term.', 'aqualuxe' ) ] );
		}

		// Build the query arguments.
		$args = [
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 10,
			's'              => $search_term,
		];

		// Run the query.
		$query = new \WP_Query( $args );

		// Check if we have products.
		if ( $query->have_posts() ) {
			$products = [];

			while ( $query->have_posts() ) {
				$query->the_post();
				$product = wc_get_product( get_the_ID() );

				$products[] = [
					'id'        => $product->get_id(),
					'title'     => $product->get_name(),
					'url'       => get_permalink( $product->get_id() ),
					'image'     => wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ),
					'price'     => $product->get_price_html(),
					'sku'       => $product->get_sku(),
					'stock'     => $product->is_in_stock() ? __( 'In Stock', 'aqualuxe' ) : __( 'Out of Stock', 'aqualuxe' ),
					'excerpt'   => wp_trim_words( $product->get_short_description(), 10 ),
				];
			}

			wp_send_json_success(
				[
					'products'   => $products,
					'found'      => $query->found_posts,
					'search_url' => add_query_arg( 's', $search_term, get_permalink( wc_get_page_id( 'shop' ) ) ),
				]
			);
		} else {
			wp_send_json_error( [ 'message' => __( 'No products found.', 'aqualuxe' ) ] );
		}

		wp_reset_postdata();
		wp_die();
	}

	/**
	 * Filter products.
	 */
	public function aqualuxe_filter_products() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			wp_send_json_error( [ 'message' => __( 'WooCommerce is not active.', 'aqualuxe' ) ] );
		}

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
		$query = new \WP_Query( $args );

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

	/**
	 * Quick view.
	 */
	public function aqualuxe_quick_view() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			wp_send_json_error( [ 'message' => __( 'WooCommerce is not active.', 'aqualuxe' ) ] );
		}

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

	/**
	 * Add to cart.
	 */
	public function aqualuxe_add_to_cart() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			wp_send_json_error( [ 'message' => __( 'WooCommerce is not active.', 'aqualuxe' ) ] );
		}

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
						'.cart-count'                  => '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>',
					],
					'cart_hash' => WC()->cart->get_cart_hash(),
				]
			);
		} else {
			wp_send_json_error( [ 'message' => __( 'Failed to add product to cart.', 'aqualuxe' ) ] );
		}

		wp_die();
	}

	/**
	 * Update cart.
	 */
	public function aqualuxe_update_cart() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			wp_send_json_error( [ 'message' => __( 'WooCommerce is not active.', 'aqualuxe' ) ] );
		}

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
						'.cart-count'                  => '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>',
					],
					'cart_hash' => WC()->cart->get_cart_hash(),
				]
			);
		} else {
			wp_send_json_error( [ 'message' => __( 'Failed to update cart.', 'aqualuxe' ) ] );
		}

		wp_die();
	}

	/**
	 * Remove from cart.
	 */
	public function aqualuxe_remove_from_cart() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			wp_send_json_error( [ 'message' => __( 'WooCommerce is not active.', 'aqualuxe' ) ] );
		}

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
						'.cart-count'                  => '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>',
					],
					'cart_hash' => WC()->cart->get_cart_hash(),
				]
			);
		} else {
			wp_send_json_error( [ 'message' => __( 'Failed to remove product from cart.', 'aqualuxe' ) ] );
		}

		wp_die();
	}

	/**
	 * Toggle wishlist.
	 */
	public function aqualuxe_toggle_wishlist() {
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
		$wishlist = $this->get_wishlist();

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
		$this->save_wishlist( $wishlist );

		wp_send_json_success(
			[
				'status'  => $status,
				'message' => $message,
				'count'   => count( $wishlist ),
			]
		);

		wp_die();
	}

	/**
	 * Toggle compare.
	 */
	public function aqualuxe_toggle_compare() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-compare-nonce', 'nonce', false ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ] );
		}

		// Get the product ID.
		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

		// Check if product ID is valid.
		if ( $product_id <= 0 ) {
			wp_send_json_error( [ 'message' => __( 'Invalid product ID.', 'aqualuxe' ) ] );
		}

		// Get the current compare list.
		$compare = $this->get_compare();

		// Check if the product is already in the compare list.
		$index = array_search( $product_id, $compare, true );

		if ( false !== $index ) {
			// Remove the product from the compare list.
			unset( $compare[ $index ] );
			$status = 'removed';
			$message = __( 'Product removed from compare list.', 'aqualuxe' );
		} else {
			// Add the product to the compare list.
			$compare[] = $product_id;
			$status = 'added';
			$message = __( 'Product added to compare list.', 'aqualuxe' );
		}

		// Save the compare list.
		$this->save_compare( $compare );

		wp_send_json_success(
			[
				'status'  => $status,
				'message' => $message,
				'count'   => count( $compare ),
			]
		);

		wp_die();
	}

	/**
	 * Newsletter subscribe.
	 */
	public function aqualuxe_newsletter_subscribe() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-newsletter-nonce', 'nonce', false ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ] );
		}

		// Get the email.
		$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';

		// Check if email is valid.
		if ( ! is_email( $email ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid email address.', 'aqualuxe' ) ] );
		}

		// Get the name.
		$name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';

		// Get the consent.
		$consent = isset( $_POST['consent'] ) ? (bool) $_POST['consent'] : false;

		// Check if consent is given.
		if ( ! $consent ) {
			wp_send_json_error( [ 'message' => __( 'You must agree to the privacy policy.', 'aqualuxe' ) ] );
		}

		// Save the subscriber.
		$subscriber_id = $this->save_subscriber( $email, $name );

		// Check if the subscriber was saved.
		if ( $subscriber_id ) {
			wp_send_json_success( [ 'message' => __( 'Thank you for subscribing!', 'aqualuxe' ) ] );
		} else {
			wp_send_json_error( [ 'message' => __( 'Failed to subscribe. Please try again.', 'aqualuxe' ) ] );
		}

		wp_die();
	}

	/**
	 * Contact form.
	 */
	public function aqualuxe_contact_form() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-contact-nonce', 'nonce', false ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ] );
		}

		// Get the form data.
		$name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
		$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		$subject = isset( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : '';
		$message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

		// Check if required fields are filled.
		if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
			wp_send_json_error( [ 'message' => __( 'Please fill in all required fields.', 'aqualuxe' ) ] );
		}

		// Check if email is valid.
		if ( ! is_email( $email ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid email address.', 'aqualuxe' ) ] );
		}

		// Set the email recipient.
		$to = get_option( 'admin_email' );

		// Set the email subject.
		$email_subject = ! empty( $subject ) ? $subject : __( 'New contact form submission', 'aqualuxe' );

		// Set the email headers.
		$headers = [
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . $name . ' <' . $email . '>',
			'Reply-To: ' . $email,
		];

		// Build the email content.
		$email_content = '<p><strong>' . __( 'Name', 'aqualuxe' ) . ':</strong> ' . $name . '</p>';
		$email_content .= '<p><strong>' . __( 'Email', 'aqualuxe' ) . ':</strong> ' . $email . '</p>';
		$email_content .= '<p><strong>' . __( 'Subject', 'aqualuxe' ) . ':</strong> ' . $subject . '</p>';
		$email_content .= '<p><strong>' . __( 'Message', 'aqualuxe' ) . ':</strong></p>';
		$email_content .= '<p>' . nl2br( $message ) . '</p>';

		// Send the email.
		$sent = wp_mail( $to, $email_subject, $email_content, $headers );

		// Check if the email was sent.
		if ( $sent ) {
			wp_send_json_success( [ 'message' => __( 'Thank you for your message. We will get back to you soon!', 'aqualuxe' ) ] );
		} else {
			wp_send_json_error( [ 'message' => __( 'Failed to send message. Please try again.', 'aqualuxe' ) ] );
		}

		wp_die();
	}

	/**
	 * Save account details.
	 */
	public function aqualuxe_save_account_details() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			wp_send_json_error( [ 'message' => __( 'WooCommerce is not active.', 'aqualuxe' ) ] );
		}

		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-save-account-details-nonce', 'nonce', false ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ] );
		}

		// Check if user is logged in.
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => __( 'You must be logged in to update your account details.', 'aqualuxe' ) ] );
		}

		// Get the current user.
		$user = wp_get_current_user();

		// Get the form data.
		$account_first_name = isset( $_POST['account_first_name'] ) ? sanitize_text_field( $_POST['account_first_name'] ) : '';
		$account_last_name = isset( $_POST['account_last_name'] ) ? sanitize_text_field( $_POST['account_last_name'] ) : '';
		$account_display_name = isset( $_POST['account_display_name'] ) ? sanitize_text_field( $_POST['account_display_name'] ) : '';
		$account_email = isset( $_POST['account_email'] ) ? sanitize_email( $_POST['account_email'] ) : '';
		$password_current = isset( $_POST['password_current'] ) ? $_POST['password_current'] : '';
		$password_1 = isset( $_POST['password_1'] ) ? $_POST['password_1'] : '';
		$password_2 = isset( $_POST['password_2'] ) ? $_POST['password_2'] : '';

		// Check if required fields are filled.
		if ( empty( $account_first_name ) || empty( $account_last_name ) || empty( $account_email ) ) {
			wp_send_json_error( [ 'message' => __( 'Please fill in all required fields.', 'aqualuxe' ) ] );
		}

		// Check if email is valid.
		if ( ! is_email( $account_email ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid email address.', 'aqualuxe' ) ] );
		}

		// Check if email is already in use.
		if ( $account_email !== $user->user_email && email_exists( $account_email ) ) {
			wp_send_json_error( [ 'message' => __( 'This email address is already registered.', 'aqualuxe' ) ] );
		}

		// Check if password is being changed.
		$update_pass = false;
		if ( ! empty( $password_current ) && ! empty( $password_1 ) && ! empty( $password_2 ) ) {
			// Check if current password is correct.
			if ( ! wp_check_password( $password_current, $user->user_pass, $user->ID ) ) {
				wp_send_json_error( [ 'message' => __( 'Current password is incorrect.', 'aqualuxe' ) ] );
			}

			// Check if new passwords match.
			if ( $password_1 !== $password_2 ) {
				wp_send_json_error( [ 'message' => __( 'New passwords do not match.', 'aqualuxe' ) ] );
			}

			// Check if new password is strong enough.
			if ( strlen( $password_1 ) < 8 ) {
				wp_send_json_error( [ 'message' => __( 'Password must be at least 8 characters long.', 'aqualuxe' ) ] );
			}

			$update_pass = true;
		}

		// Update user data.
		$userdata = [
			'ID'           => $user->ID,
			'first_name'   => $account_first_name,
			'last_name'    => $account_last_name,
			'display_name' => $account_display_name,
			'user_email'   => $account_email,
		];

		if ( $update_pass ) {
			$userdata['user_pass'] = $password_1;
		}

		// Update the user.
		$user_id = wp_update_user( $userdata );

		// Check if the user was updated.
		if ( is_wp_error( $user_id ) ) {
			wp_send_json_error( [ 'message' => $user_id->get_error_message() ] );
		} else {
			wp_send_json_success( [ 'message' => __( 'Account details updated successfully.', 'aqualuxe' ) ] );
		}

		wp_die();
	}

	/**
	 * Save address.
	 */
	public function aqualuxe_save_address() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			wp_send_json_error( [ 'message' => __( 'WooCommerce is not active.', 'aqualuxe' ) ] );
		}

		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-save-address-nonce', 'nonce', false ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ] );
		}

		// Check if user is logged in.
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => __( 'You must be logged in to update your address.', 'aqualuxe' ) ] );
		}

		// Get the current user.
		$user_id = get_current_user_id();

		// Get the address type.
		$address_type = isset( $_POST['address_type'] ) ? sanitize_text_field( $_POST['address_type'] ) : 'billing';

		// Check if address type is valid.
		if ( ! in_array( $address_type, [ 'billing', 'shipping' ], true ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid address type.', 'aqualuxe' ) ] );
		}

		// Get the address fields.
		$address_fields = WC()->countries->get_address_fields( '', $address_type . '_' );

		// Update the address fields.
		foreach ( $address_fields as $key => $field ) {
			$field_key = str_replace( $address_type . '_', '', $key );
			$value = isset( $_POST[ $key ] ) ? sanitize_text_field( $_POST[ $key ] ) : '';

			// Check if field is required.
			if ( isset( $field['required'] ) && $field['required'] && empty( $value ) ) {
				wp_send_json_error( [ 'message' => sprintf( __( '%s is a required field.', 'aqualuxe' ), $field['label'] ) ] );
			}

			// Update the user meta.
			update_user_meta( $user_id, $key, $value );
		}

		wp_send_json_success( [ 'message' => __( 'Address updated successfully.', 'aqualuxe' ) ] );
		wp_die();
	}

	/**
	 * Cancel order.
	 */
	public function aqualuxe_cancel_order() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			wp_send_json_error( [ 'message' => __( 'WooCommerce is not active.', 'aqualuxe' ) ] );
		}

		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-cancel-order-nonce', 'nonce', false ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ] );
		}

		// Check if user is logged in.
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => __( 'You must be logged in to cancel an order.', 'aqualuxe' ) ] );
		}

		// Get the order ID.
		$order_id = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : 0;

		// Check if order ID is valid.
		if ( $order_id <= 0 ) {
			wp_send_json_error( [ 'message' => __( 'Invalid order ID.', 'aqualuxe' ) ] );
		}

		// Get the order.
		$order = wc_get_order( $order_id );

		// Check if order exists.
		if ( ! $order ) {
			wp_send_json_error( [ 'message' => __( 'Order not found.', 'aqualuxe' ) ] );
		}

		// Check if the current user is the order owner.
		if ( $order->get_customer_id() !== get_current_user_id() ) {
			wp_send_json_error( [ 'message' => __( 'You do not have permission to cancel this order.', 'aqualuxe' ) ] );
		}

		// Check if the order can be cancelled.
		if ( ! $order->has_status( [ 'pending', 'processing' ] ) ) {
			wp_send_json_error( [ 'message' => __( 'This order cannot be cancelled.', 'aqualuxe' ) ] );
		}

		// Cancel the order.
		$order->update_status( 'cancelled', __( 'Order cancelled by customer.', 'aqualuxe' ) );

		wp_send_json_success( [ 'message' => __( 'Order cancelled successfully.', 'aqualuxe' ) ] );
		wp_die();
	}

	/**
	 * Add review.
	 */
	public function aqualuxe_add_review() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			wp_send_json_error( [ 'message' => __( 'WooCommerce is not active.', 'aqualuxe' ) ] );
		}

		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-add-review-nonce', 'nonce', false ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ] );
		}

		// Check if user is logged in.
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => __( 'You must be logged in to add a review.', 'aqualuxe' ) ] );
		}

		// Get the product ID.
		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

		// Check if product ID is valid.
		if ( $product_id <= 0 ) {
			wp_send_json_error( [ 'message' => __( 'Invalid product ID.', 'aqualuxe' ) ] );
		}

		// Get the rating.
		$rating = isset( $_POST['rating'] ) ? absint( $_POST['rating'] ) : 0;

		// Check if rating is valid.
		if ( $rating < 1 || $rating > 5 ) {
			wp_send_json_error( [ 'message' => __( 'Invalid rating. Please select a rating between 1 and 5.', 'aqualuxe' ) ] );
		}

		// Get the review.
		$review = isset( $_POST['review'] ) ? sanitize_textarea_field( $_POST['review'] ) : '';

		// Check if review is empty.
		if ( empty( $review ) ) {
			wp_send_json_error( [ 'message' => __( 'Please enter a review.', 'aqualuxe' ) ] );
		}

		// Get the current user.
		$user = wp_get_current_user();

		// Check if the user has already reviewed this product.
		$comments = get_comments(
			[
				'user_id'    => $user->ID,
				'post_id'    => $product_id,
				'status'     => 'approve',
				'type'       => 'review',
				'count'      => true,
			]
		);

		if ( $comments > 0 ) {
			wp_send_json_error( [ 'message' => __( 'You have already reviewed this product.', 'aqualuxe' ) ] );
		}

		// Add the review.
		$comment_data = [
			'comment_post_ID'      => $product_id,
			'comment_author'       => $user->display_name,
			'comment_author_email' => $user->user_email,
			'comment_author_url'   => '',
			'comment_content'      => $review,
			'comment_type'         => 'review',
			'comment_parent'       => 0,
			'user_id'              => $user->ID,
			'comment_approved'     => 1,
		];

		// Insert the comment.
		$comment_id = wp_insert_comment( $comment_data );

		// Check if the comment was inserted.
		if ( ! $comment_id ) {
			wp_send_json_error( [ 'message' => __( 'Failed to add review. Please try again.', 'aqualuxe' ) ] );
		}

		// Add the rating.
		add_comment_meta( $comment_id, 'rating', $rating );

		// Update the product rating.
		$this->update_product_rating( $product_id );

		wp_send_json_success( [ 'message' => __( 'Review added successfully.', 'aqualuxe' ) ] );
		wp_die();
	}

	/**
	 * Get wishlist.
	 *
	 * @return array The wishlist.
	 */
	private function get_wishlist() {
		// Check if user is logged in.
		if ( is_user_logged_in() ) {
			// Get the wishlist from user meta.
			$wishlist = get_user_meta( get_current_user_id(), 'aqualuxe_wishlist', true );
		} else {
			// Get the wishlist from cookie.
			$wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : [];
		}

		// Ensure the wishlist is an array.
		if ( ! is_array( $wishlist ) ) {
			$wishlist = [];
		}

		return array_unique( array_filter( $wishlist ) );
	}

	/**
	 * Save wishlist.
	 *
	 * @param array $wishlist The wishlist.
	 */
	private function save_wishlist( $wishlist ) {
		// Ensure the wishlist is an array.
		if ( ! is_array( $wishlist ) ) {
			$wishlist = [];
		}

		// Remove duplicates and empty values.
		$wishlist = array_unique( array_filter( $wishlist ) );

		// Check if user is logged in.
		if ( is_user_logged_in() ) {
			// Save the wishlist to user meta.
			update_user_meta( get_current_user_id(), 'aqualuxe_wishlist', $wishlist );
		} else {
			// Save the wishlist to cookie.
			setcookie( 'aqualuxe_wishlist', json_encode( $wishlist ), time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
		}
	}

	/**
	 * Get compare list.
	 *
	 * @return array The compare list.
	 */
	private function get_compare() {
		// Check if user is logged in.
		if ( is_user_logged_in() ) {
			// Get the compare list from user meta.
			$compare = get_user_meta( get_current_user_id(), 'aqualuxe_compare', true );
		} else {
			// Get the compare list from cookie.
			$compare = isset( $_COOKIE['aqualuxe_compare'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_compare'] ) ), true ) : [];
		}

		// Ensure the compare list is an array.
		if ( ! is_array( $compare ) ) {
			$compare = [];
		}

		return array_unique( array_filter( $compare ) );
	}

	/**
	 * Save compare list.
	 *
	 * @param array $compare The compare list.
	 */
	private function save_compare( $compare ) {
		// Ensure the compare list is an array.
		if ( ! is_array( $compare ) ) {
			$compare = [];
		}

		// Remove duplicates and empty values.
		$compare = array_unique( array_filter( $compare ) );

		// Check if user is logged in.
		if ( is_user_logged_in() ) {
			// Save the compare list to user meta.
			update_user_meta( get_current_user_id(), 'aqualuxe_compare', $compare );
		} else {
			// Save the compare list to cookie.
			setcookie( 'aqualuxe_compare', json_encode( $compare ), time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
		}
	}

	/**
	 * Save subscriber.
	 *
	 * @param string $email The subscriber email.
	 * @param string $name  The subscriber name.
	 * @return int|false The subscriber ID or false on failure.
	 */
	private function save_subscriber( $email, $name = '' ) {
		// Check if the subscriber already exists.
		$subscriber = get_page_by_title( $email, OBJECT, 'aqualuxe_subscriber' );

		if ( $subscriber ) {
			// Update the subscriber.
			$subscriber_id = wp_update_post(
				[
					'ID'           => $subscriber->ID,
					'post_title'   => $email,
					'post_content' => $name,
					'post_status'  => 'publish',
					'post_type'    => 'aqualuxe_subscriber',
				]
			);
		} else {
			// Create the subscriber.
			$subscriber_id = wp_insert_post(
				[
					'post_title'   => $email,
					'post_content' => $name,
					'post_status'  => 'publish',
					'post_type'    => 'aqualuxe_subscriber',
				]
			);
		}

		return $subscriber_id;
	}

	/**
	 * Update product rating.
	 *
	 * @param int $product_id The product ID.
	 */
	private function update_product_rating( $product_id ) {
		// Get the product.
		$product = wc_get_product( $product_id );

		// Check if product exists.
		if ( ! $product ) {
			return;
		}

		// Get the product reviews.
		$comments = get_comments(
			[
				'post_id' => $product_id,
				'status'  => 'approve',
				'type'    => 'review',
			]
		);

		// Check if there are reviews.
		if ( ! $comments ) {
			return;
		}

		// Calculate the average rating.
		$total_rating = 0;
		$count = 0;

		foreach ( $comments as $comment ) {
			$rating = get_comment_meta( $comment->comment_ID, 'rating', true );
			if ( $rating ) {
				$total_rating += $rating;
				$count++;
			}
		}

		$average_rating = $count > 0 ? round( $total_rating / $count, 2 ) : 0;

		// Update the product rating.
		update_post_meta( $product_id, '_wc_average_rating', $average_rating );
		update_post_meta( $product_id, '_wc_review_count', $count );
	}
}