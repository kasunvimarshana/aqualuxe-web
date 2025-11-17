<?php
/**
 * WooCommerce Quick View Class
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
 * Quick View Class
 *
 * Handles quick view functionality for WooCommerce products.
 */
class QuickView {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Add quick view button to product loops.
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'add_quick_view_button' ), 10 );
		
		// AJAX handlers.
		add_action( 'wp_ajax_aqualuxe_quick_view', array( $this, 'ajax_quick_view' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', array( $this, 'ajax_quick_view' ) );
		
		// Enqueue scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		// Add quick view modal container.
		add_action( 'wp_footer', array( $this, 'quick_view_modal' ) );
		
		// Customize quick view content.
		add_action( 'aqualuxe_quick_view_product_image', array( $this, 'quick_view_product_image' ), 10 );
		add_action( 'aqualuxe_quick_view_product_summary', array( $this, 'quick_view_product_summary' ), 10 );
		
		// Remove unnecessary elements from quick view.
		add_filter( 'woocommerce_add_to_cart_form_action', array( $this, 'quick_view_form_action' ), 10, 1 );
	}

	/**
	 * Add quick view button to product loops
	 */
	public function add_quick_view_button() {
		global $product;
		
		// Only show if quick view is enabled.
		if ( ! get_theme_mod( 'aqualuxe_product_quick_view', true ) ) {
			return;
		}
		
		echo '<div class="aqualuxe-quick-view-wrapper">';
		echo '<button type="button" class="aqualuxe-quick-view-button" data-product-id="' . esc_attr( $product->get_id() ) . '">';
		echo '<i class="fas fa-eye"></i>';
		echo '<span>' . esc_html__( 'Quick View', 'aqualuxe' ) . '</span>';
		echo '</button>';
		echo '</div>';
	}

	/**
	 * AJAX quick view
	 */
	public function ajax_quick_view() {
		// Check nonce.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_quick_view_nonce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Security check failed.', 'aqualuxe' ) ) );
		}
		
		// Get product ID.
		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		
		if ( ! $product_id ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid product.', 'aqualuxe' ) ) );
		}
		
		// Get product.
		$product = wc_get_product( $product_id );
		
		if ( ! $product ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Product not found.', 'aqualuxe' ) ) );
		}
		
		// Start output buffering.
		ob_start();
		
		// Set the global product variable.
		global $post;
		$post = get_post( $product_id );
		setup_postdata( $post );
		
		// Quick view content.
		echo '<div class="aqualuxe-quick-view-content">';
		echo '<div class="aqualuxe-quick-view-image">';
		do_action( 'aqualuxe_quick_view_product_image', $product );
		echo '</div>';
		
		echo '<div class="aqualuxe-quick-view-summary">';
		do_action( 'aqualuxe_quick_view_product_summary', $product );
		echo '</div>';
		echo '</div>';
		
		// Reset post data.
		wp_reset_postdata();
		
		// Get the buffered content.
		$content = ob_get_clean();
		
		wp_send_json_success( array(
			'content' => $content,
		) );
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
		// Only enqueue if quick view is enabled.
		if ( ! get_theme_mod( 'aqualuxe_product_quick_view', true ) ) {
			return;
		}
		
		wp_enqueue_script(
			'aqualuxe-quick-view',
			get_template_directory_uri() . '/assets/js/quick-view.js',
			array( 'jquery', 'wc-add-to-cart-variation' ),
			AQUALUXE_VERSION,
			true
		);
		
		wp_localize_script(
			'aqualuxe-quick-view',
			'aqualuxeQuickView',
			array(
				'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'aqualuxe_quick_view_nonce' ),
				'loading'   => esc_html__( 'Loading...', 'aqualuxe' ),
				'error'     => esc_html__( 'Error loading product.', 'aqualuxe' ),
				'adding'    => esc_html__( 'Adding...', 'aqualuxe' ),
				'added'     => esc_html__( 'Added to cart', 'aqualuxe' ),
				'viewCart'  => esc_html__( 'View Cart', 'aqualuxe' ),
				'cartUrl'   => wc_get_cart_url(),
			)
		);
		
		// Enqueue variation scripts.
		wp_enqueue_script( 'wc-add-to-cart-variation' );
	}

	/**
	 * Quick view modal
	 */
	public function quick_view_modal() {
		// Only show if quick view is enabled.
		if ( ! get_theme_mod( 'aqualuxe_product_quick_view', true ) ) {
			return;
		}
		
		?>
		<div id="aqualuxe-quick-view-modal" class="aqualuxe-quick-view-modal">
			<div class="aqualuxe-quick-view-modal-content">
				<span class="aqualuxe-quick-view-close">&times;</span>
				<div class="aqualuxe-quick-view-inner">
					<div class="aqualuxe-quick-view-loader">
						<div class="aqualuxe-quick-view-loader-spinner"></div>
					</div>
					<div id="aqualuxe-quick-view-content" class="woocommerce"></div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Quick view product image
	 *
	 * @param object $product Product object.
	 */
	public function quick_view_product_image( $product ) {
		// Main image.
		echo '<div class="aqualuxe-quick-view-main-image">';
		echo $product->get_image( 'woocommerce_single' );
		echo '</div>';
		
		// Gallery thumbnails.
		$attachment_ids = $product->get_gallery_image_ids();
		
		if ( ! empty( $attachment_ids ) ) {
			echo '<div class="aqualuxe-quick-view-thumbnails">';
			
			// Add main image as first thumbnail.
			echo '<div class="aqualuxe-quick-view-thumbnail active" data-image-id="' . esc_attr( $product->get_image_id() ) . '">';
			echo $product->get_image( 'woocommerce_gallery_thumbnail' );
			echo '</div>';
			
			// Add gallery images as thumbnails.
			foreach ( $attachment_ids as $attachment_id ) {
				echo '<div class="aqualuxe-quick-view-thumbnail" data-image-id="' . esc_attr( $attachment_id ) . '">';
				echo wp_get_attachment_image( $attachment_id, 'woocommerce_gallery_thumbnail' );
				echo '</div>';
			}
			
			echo '</div>';
		}
	}

	/**
	 * Quick view product summary
	 *
	 * @param object $product Product object.
	 */
	public function quick_view_product_summary( $product ) {
		// Title.
		echo '<h2 class="product_title entry-title">' . esc_html( $product->get_name() ) . '</h2>';
		
		// Rating.
		if ( wc_review_ratings_enabled() ) {
			echo wc_get_rating_html( $product->get_average_rating() );
		}
		
		// Price.
		echo '<div class="price">' . $product->get_price_html() . '</div>';
		
		// Short description.
		echo '<div class="woocommerce-product-details__short-description">';
		echo wp_kses_post( $product->get_short_description() );
		echo '</div>';
		
		// Add to cart form.
		echo '<div class="aqualuxe-quick-view-add-to-cart">';
		woocommerce_template_single_add_to_cart();
		echo '</div>';
		
		// Meta.
		echo '<div class="product_meta">';
		
		// SKU.
		if ( wc_product_sku_enabled() && $product->get_sku() ) {
			echo '<span class="sku_wrapper">' . esc_html__( 'SKU:', 'aqualuxe' ) . ' <span class="sku">' . esc_html( $product->get_sku() ) . '</span></span>';
		}
		
		// Categories.
		echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'aqualuxe' ) . ' ', '</span>' );
		
		// Tags.
		echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'aqualuxe' ) . ' ', '</span>' );
		
		echo '</div>';
		
		// View full details link.
		echo '<div class="aqualuxe-quick-view-full-details">';
		echo '<a href="' . esc_url( $product->get_permalink() ) . '" class="button">';
		echo esc_html__( 'View Full Details', 'aqualuxe' );
		echo '</a>';
		echo '</div>';
	}

	/**
	 * Quick view form action
	 *
	 * @param string $form_action Form action URL.
	 * @return string Modified form action URL.
	 */
	public function quick_view_form_action( $form_action ) {
		// Check if we're in quick view.
		if ( wp_doing_ajax() && isset( $_POST['action'] ) && 'aqualuxe_quick_view' === $_POST['action'] ) {
			return '';
		}
		
		return $form_action;
	}
}

// Initialize the class.
new QuickView();