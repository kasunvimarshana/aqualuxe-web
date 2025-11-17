<?php
/**
 * AquaLuxe WooCommerce Review Template Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom WooCommerce review callback.
 *
 * @param WP_Comment $comment Comment object.
 * @param array      $args    Comment arguments.
 * @param int        $depth   Comment depth.
 */
function aqualuxe_woocommerce_review( $comment, $args, $depth ) {
	$comment_id = $comment->comment_ID;
	$rating     = intval( get_comment_meta( $comment_id, 'rating', true ) );
	$verified   = wc_review_is_from_verified_owner( $comment_id );
	?>
	<li id="comment-<?php echo esc_attr( $comment_id ); ?>" <?php comment_class( 'review', $comment ); ?>>
		<div id="div-comment-<?php echo esc_attr( $comment_id ); ?>" class="comment-body">
			<div class="comment-meta">
				<div class="comment-author vcard">
					<?php
					/**
					 * The woocommerce_review_before hook
					 *
					 * @hooked woocommerce_review_display_gravatar - 10
					 */
					do_action( 'woocommerce_review_before', $comment );
					?>
					<div class="comment-author-info">
						<div class="comment-author-name">
							<?php comment_author( $comment ); ?>
							
							<?php if ( $verified ) : ?>
								<span class="verified-badge" title="<?php esc_attr_e( 'Verified owner', 'aqualuxe' ); ?>">
									<i class="fas fa-check-circle"></i>
									<?php esc_html_e( 'Verified Purchase', 'aqualuxe' ); ?>
								</span>
							<?php endif; ?>
						</div>
						
						<div class="comment-metadata">
							<time datetime="<?php echo esc_attr( get_comment_date( 'c', $comment_id ) ); ?>">
								<?php
								/* translators: %s: Comment date */
								printf( esc_html__( 'Posted on %s', 'aqualuxe' ), esc_html( get_comment_date( wc_date_format(), $comment_id ) ) );
								?>
							</time>
						</div>
					</div>
				</div>
				
				<?php if ( $rating && wc_review_ratings_enabled() ) : ?>
					<div class="comment-rating">
						<?php echo wc_get_rating_html( $rating ); // WPCS: XSS ok. ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="comment-content">
				<?php
				/**
				 * The woocommerce_review_meta hook
				 *
				 * @hooked woocommerce_review_display_meta - 10
				 */
				do_action( 'woocommerce_review_meta', $comment );

				/**
				 * The woocommerce_review_before_comment_text hook
				 */
				do_action( 'woocommerce_review_before_comment_text', $comment );

				/**
				 * The woocommerce_review_comment_text hook
				 *
				 * @hooked woocommerce_review_display_comment_text - 10
				 */
				do_action( 'woocommerce_review_comment_text', $comment );

				/**
				 * The woocommerce_review_after_comment_text hook
				 */
				do_action( 'woocommerce_review_after_comment_text', $comment );
				?>
			</div>
			
			<div class="review-actions">
				<?php
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<div class="reply">',
							'after'     => '</div>',
						)
					),
					$comment
				);
				?>
				
				<?php if ( current_user_can( 'edit_comment', $comment_id ) ) : ?>
					<div class="edit-link">
						<a href="<?php echo esc_url( get_edit_comment_link( $comment_id ) ); ?>">
							<i class="fas fa-edit"></i> <?php esc_html_e( 'Edit', 'aqualuxe' ); ?>
						</a>
					</div>
				<?php endif; ?>
				
				<div class="review-helpful">
					<button type="button" class="helpful-button" data-comment-id="<?php echo esc_attr( $comment_id ); ?>">
						<i class="far fa-thumbs-up"></i> <?php esc_html_e( 'Helpful', 'aqualuxe' ); ?>
						<span class="helpful-count"><?php echo esc_html( get_comment_meta( $comment_id, 'helpful_count', true ) ?: '0' ); ?></span>
					</button>
				</div>
			</div>
		</div>
	<?php
}

/**
 * AJAX handler for marking reviews as helpful
 */
function aqualuxe_mark_review_helpful() {
	// Check nonce
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe-nonce' ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Security check failed.', 'aqualuxe' ) ) );
	}

	// Check if comment ID is set
	if ( ! isset( $_POST['comment_id'] ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Comment ID is missing.', 'aqualuxe' ) ) );
	}

	$comment_id = absint( $_POST['comment_id'] );
	$comment = get_comment( $comment_id );

	// Check if comment exists and is approved
	if ( ! $comment || '1' !== $comment->comment_approved ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid comment.', 'aqualuxe' ) ) );
	}

	// Get current helpful count
	$helpful_count = get_comment_meta( $comment_id, 'helpful_count', true );
	$helpful_count = $helpful_count ? absint( $helpful_count ) : 0;

	// Get user IP
	$user_ip = $_SERVER['REMOTE_ADDR'];

	// Get users who marked this comment as helpful
	$helpful_users = get_comment_meta( $comment_id, 'helpful_users', true );
	$helpful_users = $helpful_users ? $helpful_users : array();

	// Check if user already marked this comment as helpful
	if ( in_array( $user_ip, $helpful_users, true ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'You have already marked this review as helpful.', 'aqualuxe' ) ) );
	}

	// Increment helpful count
	$helpful_count++;
	update_comment_meta( $comment_id, 'helpful_count', $helpful_count );

	// Add user to helpful users
	$helpful_users[] = $user_ip;
	update_comment_meta( $comment_id, 'helpful_users', $helpful_users );

	wp_send_json_success( array(
		'message' => esc_html__( 'Thank you for your feedback!', 'aqualuxe' ),
		'count'   => $helpful_count,
	) );
}
add_action( 'wp_ajax_aqualuxe_mark_review_helpful', 'aqualuxe_mark_review_helpful' );
add_action( 'wp_ajax_nopriv_aqualuxe_mark_review_helpful', 'aqualuxe_mark_review_helpful' );

/**
 * Add review helpful count to WooCommerce review schema
 *
 * @param array      $markup    The schema markup.
 * @param WP_Comment $comment   The comment object.
 * @return array
 */
function aqualuxe_add_review_helpful_count_to_schema( $markup, $comment ) {
	$helpful_count = get_comment_meta( $comment->comment_ID, 'helpful_count', true );
	
	if ( $helpful_count ) {
		$markup['positiveNotes'] = absint( $helpful_count );
	}
	
	return $markup;
}
add_filter( 'woocommerce_structured_data_review', 'aqualuxe_add_review_helpful_count_to_schema', 10, 2 );