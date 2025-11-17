<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}

?>
<div id="reviews" class="woocommerce-Reviews">
	<div class="reviews-header">
		<h2 class="woocommerce-Reviews-title">
			<?php
			$count = $product->get_review_count();
			if ( $count && wc_review_ratings_enabled() ) {
				/* translators: 1: reviews count */
				$reviews_title = sprintf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'aqualuxe' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
				echo apply_filters( 'woocommerce_reviews_title', $reviews_title, $count, $product ); // WPCS: XSS ok.
			} else {
				esc_html_e( 'Reviews', 'aqualuxe' );
			}
			?>
		</h2>
		
		<?php if ( wc_review_ratings_enabled() && $product->get_average_rating() > 0 ) : ?>
			<div class="reviews-summary">
				<div class="average-rating">
					<div class="rating-number"><?php echo esc_html( $product->get_average_rating() ); ?></div>
					<div class="rating-stars">
						<?php echo wc_get_rating_html( $product->get_average_rating() ); // WPCS: XSS ok. ?>
					</div>
					<div class="rating-count">
						<?php
						/* translators: %s: review count */
						printf( esc_html( _n( 'Based on %s review', 'Based on %s reviews', $count, 'aqualuxe' ) ), esc_html( $count ) );
						?>
					</div>
				</div>
				
				<?php if ( $count > 0 ) : ?>
					<div class="rating-bars">
						<?php
						$rating_counts = $product->get_rating_counts();
						for ( $rating = 5; $rating >= 1; $rating-- ) {
							$rating_count = isset( $rating_counts[ $rating ] ) ? $rating_counts[ $rating ] : 0;
							$percentage = ( $count > 0 ) ? ( $rating_count / $count ) * 100 : 0;
							?>
							<div class="rating-bar-item">
								<div class="rating-label">
									<?php
									/* translators: %s: rating */
									printf( esc_html__( '%s star', 'aqualuxe' ), esc_html( $rating ) );
									?>
								</div>
								<div class="rating-bar">
									<div class="rating-bar-fill" style="width: <?php echo esc_attr( $percentage ); ?>%"></div>
								</div>
								<div class="rating-count"><?php echo esc_html( $rating_count ); ?></div>
							</div>
							<?php
						}
						?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( have_comments() ) : ?>
		<div class="reviews-list">
			<ol class="commentlist">
				<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'aqualuxe_woocommerce_review', 'style' => 'ol', 'short_ping' => true ) ) ); ?>
			</ol>

			<?php
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="woocommerce-pagination">';
				paginate_comments_links(
					apply_filters(
						'woocommerce_comment_pagination_args',
						array(
							'prev_text' => '<i class="fas fa-chevron-left"></i>',
							'next_text' => '<i class="fas fa-chevron-right"></i>',
							'type'      => 'list',
						)
					)
				);
				echo '</nav>';
			endif;
			?>
		</div>
	<?php else : ?>
		<div class="no-reviews">
			<div class="no-reviews-icon">
				<i class="fas fa-comment-alt"></i>
			</div>
			<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'aqualuxe' ); ?></p>
			<p class="no-reviews-message"><?php esc_html_e( 'Be the first to review this product and share your experience with others.', 'aqualuxe' ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
		<div id="review_form_wrapper">
			<div id="review_form">
				<?php
				$commenter    = wp_get_current_commenter();
				$comment_form = array(
					/* translators: %s is product title */
					'title_reply'         => have_comments() ? esc_html__( 'Add a review', 'aqualuxe' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'aqualuxe' ), get_the_title() ),
					/* translators: %s is product title */
					'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'aqualuxe' ),
					'title_reply_before'  => '<h3 id="reply-title" class="comment-reply-title">',
					'title_reply_after'   => '</h3>',
					'comment_notes_after' => '',
					'label_submit'        => esc_html__( 'Submit Review', 'aqualuxe' ),
					'logged_in_as'        => '',
					'comment_field'       => '',
				);

				$name_email_required = (bool) get_option( 'require_name_email', 1 );
				$fields              = array(
					'author' => array(
						'label'    => __( 'Name', 'aqualuxe' ),
						'type'     => 'text',
						'value'    => $commenter['comment_author'],
						'required' => $name_email_required,
					),
					'email'  => array(
						'label'    => __( 'Email', 'aqualuxe' ),
						'type'     => 'email',
						'value'    => $commenter['comment_author_email'],
						'required' => $name_email_required,
					),
				);

				$comment_form['fields'] = array();

				foreach ( $fields as $key => $field ) {
					$field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';
					$field_html .= '<label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] );

					if ( $field['required'] ) {
						$field_html .= '&nbsp;<span class="required">*</span>';
					}

					$field_html .= '</label><input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';

					$comment_form['fields'][ $key ] = $field_html;
				}

				$account_page_url = wc_get_page_permalink( 'myaccount' );
				if ( $account_page_url ) {
					/* translators: %s opening and closing link tags respectively */
					$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'aqualuxe' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
				}

				if ( wc_review_ratings_enabled() ) {
					$comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'aqualuxe' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label><select name="rating" id="rating" required>
						<option value="">' . esc_html__( 'Rate&hellip;', 'aqualuxe' ) . '</option>
						<option value="5">' . esc_html__( 'Perfect', 'aqualuxe' ) . '</option>
						<option value="4">' . esc_html__( 'Good', 'aqualuxe' ) . '</option>
						<option value="3">' . esc_html__( 'Average', 'aqualuxe' ) . '</option>
						<option value="2">' . esc_html__( 'Not that bad', 'aqualuxe' ) . '</option>
						<option value="1">' . esc_html__( 'Very poor', 'aqualuxe' ) . '</option>
					</select></div>';
				}

				$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'aqualuxe' ) . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></p>';

				comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
				?>
			</div>
		</div>
	<?php else : ?>
		<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'aqualuxe' ); ?></p>
	<?php endif; ?>

	<div class="clear"></div>
</div>