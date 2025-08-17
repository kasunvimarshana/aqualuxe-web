<?php
/**
 * WooCommerce Quick View functionality
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Quick View class
 */
class AquaLuxe_Quick_View {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Check if quick view is enabled
		if ( ! get_theme_mod( 'aqualuxe_quick_view', true ) ) {
			return;
		}

		// Add quick view button to product loop
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'quick_view_button' ), 15 );

		// Add AJAX handler for quick view
		add_action( 'wp_ajax_aqualuxe_quick_view', array( $this, 'quick_view_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', array( $this, 'quick_view_ajax' ) );

		// Add quick view modal container
		add_action( 'wp_footer', array( $this, 'quick_view_modal' ) );
	}

	/**
	 * Add quick view button to product loop
	 */
	public function quick_view_button() {
		global $product;

		if ( ! $product ) {
			return;
		}

		echo '<a href="#" class="button quick-view-button" data-product-id="' . esc_attr( $product->get_id() ) . '" rel="nofollow">';
		echo '<svg class="icon icon-eye" aria-hidden="true" focusable="false"><use xlink:href="#icon-eye"></use></svg>';
		echo '<span>' . esc_html__( 'Quick View', 'aqualuxe' ) . '</span>';
		echo '</a>';
	}

	/**
	 * AJAX handler for quick view
	 */
	public function quick_view_ajax() {
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

		// Start output buffering
		ob_start();

		// Quick view content
		$this->quick_view_content( $product );

		// Get the buffered content
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	/**
	 * Quick view content
	 *
	 * @param WC_Product $product Product object
	 */
	public function quick_view_content( $product ) {
		?>
		<div class="quick-view-header">
			<h2 class="product-title"><?php echo esc_html( $product->get_name() ); ?></h2>
			<button class="close-modal" aria-label="<?php esc_attr_e( 'Close', 'aqualuxe' ); ?>">
				<svg class="icon icon-close" aria-hidden="true" focusable="false"><use xlink:href="#icon-close"></use></svg>
			</button>
		</div>

		<div class="quick-view-body">
			<div class="quick-view-images">
				<?php
				// Product badges
				if ( function_exists( 'aqualuxe_woocommerce_template_loop_badges' ) ) {
					aqualuxe_woocommerce_template_loop_badges();
				}

				// Product image
				$image_id = $product->get_image_id();
				$image_url = wp_get_attachment_image_url( $image_id, 'woocommerce_single' );
				$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

				if ( $image_url ) {
					echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $image_alt ) . '" class="quick-view-image" />';
				} else {
					echo wc_placeholder_img( 'woocommerce_single' );
				}

				// Product gallery
				$attachment_ids = $product->get_gallery_image_ids();

				if ( $attachment_ids ) {
					echo '<div class="quick-view-thumbnails">';
					
					// Add main image to thumbnails
					if ( $image_url ) {
						echo '<div class="quick-view-thumbnail active" data-image="' . esc_url( $image_url ) . '">';
						echo '<img src="' . esc_url( wp_get_attachment_image_url( $image_id, 'thumbnail' ) ) . '" alt="' . esc_attr( $image_alt ) . '" />';
						echo '</div>';
					}
					
					// Add gallery images to thumbnails
					foreach ( $attachment_ids as $attachment_id ) {
						$thumbnail_url = wp_get_attachment_image_url( $attachment_id, 'thumbnail' );
						$full_url = wp_get_attachment_image_url( $attachment_id, 'woocommerce_single' );
						$alt_text = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
						
						echo '<div class="quick-view-thumbnail" data-image="' . esc_url( $full_url ) . '">';
						echo '<img src="' . esc_url( $thumbnail_url ) . '" alt="' . esc_attr( $alt_text ) . '" />';
						echo '</div>';
					}
					
					echo '</div>';
				}
				?>
			</div>

			<div class="quick-view-details">
				<?php
				// Product categories
				if ( function_exists( 'aqualuxe_woocommerce_product_categories' ) ) {
					aqualuxe_woocommerce_product_categories();
				}

				// Product title
				echo '<h2 class="product-title">' . esc_html( $product->get_name() ) . '</h2>';

				// Product rating
				if ( function_exists( 'aqualuxe_woocommerce_product_rating' ) ) {
					aqualuxe_woocommerce_product_rating();
				} else {
					woocommerce_template_loop_rating();
				}

				// Product price
				if ( function_exists( 'aqualuxe_woocommerce_product_price' ) ) {
					aqualuxe_woocommerce_product_price();
				} else {
					woocommerce_template_loop_price();
				}

				// Product short description
				if ( $product->get_short_description() ) {
					echo '<div class="product-short-description">';
					echo wp_kses_post( $product->get_short_description() );
					echo '</div>';
				}

				// Product availability
				if ( function_exists( 'aqualuxe_woocommerce_product_availability' ) ) {
					aqualuxe_woocommerce_product_availability();
				}

				// Add to cart form
				echo '<div class="quick-view-add-to-cart">';
				
				// Simple product
				if ( $product->is_type( 'simple' ) ) {
					// Quantity input
					woocommerce_quantity_input( array(
						'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
						'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
						'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( absint( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(),
					) );
					
					// Add to cart button
					echo '<button type="button" class="button alt ajax_add_to_cart add_to_cart_button" data-product_id="' . esc_attr( $product->get_id() ) . '" data-quantity="1">' . esc_html( $product->single_add_to_cart_text() ) . '</button>';
				} else {
					// Variable, grouped, etc.
					echo '<a href="' . esc_url( $product->get_permalink() ) . '" class="button alt">' . esc_html__( 'View Product', 'aqualuxe' ) . '</a>';
				}
				
				echo '</div>';

				// Wishlist button
				if ( function_exists( 'aqualuxe_wishlist_button' ) && get_theme_mod( 'aqualuxe_wishlist', true ) ) {
					echo '<div class="quick-view-wishlist">';
					aqualuxe_wishlist_button();
					echo '</div>';
				}

				// View full details link
				echo '<div class="quick-view-full-details">';
				echo '<a href="' . esc_url( $product->get_permalink() ) . '">' . esc_html__( 'View Full Details', 'aqualuxe' ) . '</a>';
				echo '</div>';
				?>
			</div>
		</div>

		<script>
			(function($) {
				// Thumbnail gallery
				$('.quick-view-thumbnail').on('click', function() {
					var imageUrl = $(this).data('image');
					$('.quick-view-image').attr('src', imageUrl);
					$('.quick-view-thumbnail').removeClass('active');
					$(this).addClass('active');
				});

				// Ajax add to cart
				$('.quick-view-add-to-cart .ajax_add_to_cart').on('click', function(e) {
					e.preventDefault();
					
					var $button = $(this);
					var productId = $button.data('product_id');
					var quantity = $('.quick-view-add-to-cart .quantity .qty').val();
					
					// Show loading
					$button.addClass('loading');
					
					// Add to cart via AJAX
					$.ajax({
						url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
						type: 'POST',
						data: {
							product_id: productId,
							quantity: quantity
						},
						success: function(response) {
							// Hide loading
							$button.removeClass('loading');
							
							if (response.error && response.product_url) {
								window.location = response.product_url;
								return;
							}
							
							// Trigger event so themes can refresh other areas
							$(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
							
							// Show success message
							$button.addClass('added');
						},
						error: function() {
							// Hide loading
							$button.removeClass('loading');
						}
					});
				});
			})(jQuery);
		</script>
		<?php
	}

	/**
	 * Add quick view modal container to footer
	 */
	public function quick_view_modal() {
		?>
		<div id="quick-view-modal" class="quick-view-modal">
			<div class="quick-view-container">
				<div class="quick-view-content"></div>
			</div>
		</div>
		<?php
	}
}

// Initialize quick view
new AquaLuxe_Quick_View();

/**
 * Quick view button function for use in templates
 */
function aqualuxe_quick_view_button() {
	global $product;

	if ( ! $product || ! get_theme_mod( 'aqualuxe_quick_view', true ) ) {
		return;
	}

	echo '<a href="#" class="button quick-view-button" data-product-id="' . esc_attr( $product->get_id() ) . '" rel="nofollow">';
	echo '<svg class="icon icon-eye" aria-hidden="true" focusable="false"><use xlink:href="#icon-eye"></use></svg>';
	echo '<span>' . esc_html__( 'Quick View', 'aqualuxe' ) . '</span>';
	echo '</a>';
}