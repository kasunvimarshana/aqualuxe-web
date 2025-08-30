<?php
/**
 * AquaLuxe Product Metaboxes
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add product highlights metabox
 */
function aqualuxe_add_product_highlights_metabox() {
	add_meta_box(
		'aqualuxe_product_highlights',
		esc_html__( 'Product Highlights', 'aqualuxe' ),
		'aqualuxe_product_highlights_metabox_callback',
		'product',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'aqualuxe_add_product_highlights_metabox' );

/**
 * Product highlights metabox callback
 *
 * @param WP_Post $post Current post object.
 */
function aqualuxe_product_highlights_metabox_callback( $post ) {
	// Add nonce for security
	wp_nonce_field( 'aqualuxe_product_highlights_nonce', 'aqualuxe_product_highlights_nonce' );

	// Get saved highlights
	$highlights = get_post_meta( $post->ID, '_aqualuxe_product_highlights', true );

	// Display metabox
	?>
	<div class="aqualuxe-metabox product-highlights-metabox">
		<p><?php esc_html_e( 'Add key highlights for this product. These will be displayed on the product page.', 'aqualuxe' ); ?></p>
		
		<div id="product_highlights">
			<?php
			if ( ! empty( $highlights ) && is_array( $highlights ) ) {
				foreach ( $highlights as $index => $highlight ) {
					?>
					<div class="highlight">
						<input type="text" name="product_highlight[]" class="widefat" value="<?php echo esc_attr( $highlight ); ?>" placeholder="<?php esc_attr_e( 'Enter product highlight', 'aqualuxe' ); ?>" />
						<button type="button" class="button remove_highlight"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
					</div>
					<?php
				}
			}
			?>
		</div>
		
		<p><button type="button" class="button add_highlight"><?php esc_html_e( 'Add Highlight', 'aqualuxe' ); ?></button></p>
	</div>
	<?php
}

/**
 * Save product highlights
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_save_product_highlights( $post_id ) {
	// Check if nonce is set
	if ( ! isset( $_POST['aqualuxe_product_highlights_nonce'] ) ) {
		return;
	}

	// Verify nonce
	if ( ! wp_verify_nonce( $_POST['aqualuxe_product_highlights_nonce'], 'aqualuxe_product_highlights_nonce' ) ) {
		return;
	}

	// Check if autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check permissions
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Save highlights
	$highlights = array();
	
	if ( isset( $_POST['product_highlight'] ) && is_array( $_POST['product_highlight'] ) ) {
		foreach ( $_POST['product_highlight'] as $highlight ) {
			if ( ! empty( $highlight ) ) {
				$highlights[] = sanitize_text_field( $highlight );
			}
		}
	}
	
	update_post_meta( $post_id, '_aqualuxe_product_highlights', $highlights );
}
add_action( 'save_post_product', 'aqualuxe_save_product_highlights' );

/**
 * Add total stock field to product inventory settings
 */
function aqualuxe_product_total_stock_field() {
	global $post;
	
	echo '<div class="options_group">';
	
	woocommerce_wp_text_input(
		array(
			'id'          => '_aqualuxe_total_stock',
			'label'       => esc_html__( 'Total Stock', 'aqualuxe' ),
			'desc_tip'    => true,
			'description' => esc_html__( 'Enter the total stock quantity for the progress bar display.', 'aqualuxe' ),
			'type'        => 'number',
			'custom_attributes' => array(
				'step' => '1',
				'min'  => '0',
			),
		)
	);
	
	echo '</div>';
}
add_action( 'woocommerce_product_options_inventory_product_data', 'aqualuxe_product_total_stock_field' );

/**
 * Save total stock field
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_save_product_total_stock( $post_id ) {
	if ( isset( $_POST['_aqualuxe_total_stock'] ) ) {
		update_post_meta( $post_id, '_aqualuxe_total_stock', absint( $_POST['_aqualuxe_total_stock'] ) );
	}
}
add_action( 'woocommerce_process_product_meta', 'aqualuxe_save_product_total_stock' );

/**
 * Add product video field
 */
function aqualuxe_product_video_field() {
	global $post;
	
	echo '<div class="options_group">';
	
	woocommerce_wp_text_input(
		array(
			'id'          => '_aqualuxe_product_video',
			'label'       => esc_html__( 'Product Video URL', 'aqualuxe' ),
			'desc_tip'    => true,
			'description' => esc_html__( 'Enter YouTube, Vimeo, or self-hosted video URL.', 'aqualuxe' ),
			'type'        => 'url',
			'placeholder' => 'https://',
		)
	);
	
	echo '<p class="form-field _aqualuxe_product_video_field">';
	echo '<label for="_aqualuxe_product_video">' . esc_html__( 'Or Upload Video', 'aqualuxe' ) . '</label>';
	echo '<input type="text" class="short" style="width: 80%;" name="_aqualuxe_product_video_upload" id="_aqualuxe_product_video_upload" value="" placeholder="' . esc_attr__( 'Upload or enter video URL', 'aqualuxe' ) . '" />';
	echo '<button type="button" class="button upload_product_video_button">' . esc_html__( 'Upload', 'aqualuxe' ) . '</button>';
	echo '</p>';
	
	echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'aqualuxe_product_video_field' );

/**
 * Save product video field
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_save_product_video_field( $post_id ) {
	if ( isset( $_POST['_aqualuxe_product_video'] ) ) {
		update_post_meta( $post_id, '_aqualuxe_product_video', esc_url_raw( $_POST['_aqualuxe_product_video'] ) );
	}
	
	if ( isset( $_POST['_aqualuxe_product_video_upload'] ) && ! empty( $_POST['_aqualuxe_product_video_upload'] ) ) {
		update_post_meta( $post_id, '_aqualuxe_product_video', esc_url_raw( $_POST['_aqualuxe_product_video_upload'] ) );
	}
}
add_action( 'woocommerce_process_product_meta', 'aqualuxe_save_product_video_field' );

/**
 * Add size guide field
 */
function aqualuxe_size_guide_field() {
	global $post;
	
	echo '<div class="options_group">';
	
	woocommerce_wp_textarea_input(
		array(
			'id'          => '_aqualuxe_size_guide',
			'label'       => esc_html__( 'Size Guide', 'aqualuxe' ),
			'desc_tip'    => true,
			'description' => esc_html__( 'Enter size guide content. HTML is allowed. Leave blank to hide size guide button.', 'aqualuxe' ),
		)
	);
	
	echo '</div>';
}
add_action( 'woocommerce_product_options_advanced', 'aqualuxe_size_guide_field' );

/**
 * Save size guide field
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_save_size_guide_field( $post_id ) {
	if ( isset( $_POST['_aqualuxe_size_guide'] ) ) {
		update_post_meta( $post_id, '_aqualuxe_size_guide', wp_kses_post( $_POST['_aqualuxe_size_guide'] ) );
	}
}
add_action( 'woocommerce_process_product_meta', 'aqualuxe_save_size_guide_field' );