<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

/*
 * If the current post is protected by a password and
 * the visitor has not entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title text-2xl font-serif font-bold text-dark-900 dark:text-white mb-6">
			<?php
			$aqualuxe_comment_count = get_comments_number();
			if ( '1' === $aqualuxe_comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html__( 'One comment on &ldquo;%1$s&rdquo;', 'aqualuxe' ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			} else {
				printf( 
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', $aqualuxe_comment_count, 'comments title', 'aqualuxe' ) ),
					number_format_i18n( $aqualuxe_comment_count ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			}
			?>
		</h2><!-- .comments-title -->

		<?php the_comments_navigation(); ?>

		<ol class="comment-list divide-y divide-gray-200 dark:divide-dark-700">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 60,
					'callback'   => 'aqualuxe_comment_callback',
				)
			);
			?>
		</ol><!-- .comment-list -->

		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<p class="no-comments bg-dark-100 dark:bg-dark-800 text-dark-700 dark:text-dark-300 p-4 rounded-lg text-center mt-8"><?php esc_html_e( 'Comments are closed.', 'aqualuxe' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().

	// Custom comment form
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$consent = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
	
	$fields = array(
		'author' => '<div class="comment-form-author mb-4">
						<label for="author" class="form-label">' . __( 'Name', 'aqualuxe' ) . ( $req ? ' <span class="required text-red-600">*</span>' : '' ) . '</label>
						<input id="author" name="author" type="text" class="form-input" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $aria_req . ' />
					</div>',
		'email'  => '<div class="comment-form-email mb-4">
						<label for="email" class="form-label">' . __( 'Email', 'aqualuxe' ) . ( $req ? ' <span class="required text-red-600">*</span>' : '' ) . '</label>
						<input id="email" name="email" type="email" class="form-input" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . ' />
					</div>',
		'url'    => '<div class="comment-form-url mb-4">
						<label for="url" class="form-label">' . __( 'Website', 'aqualuxe' ) . '</label>
						<input id="url" name="url" type="url" class="form-input" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" />
					</div>',
		'cookies' => '<div class="comment-form-cookies-consent mb-4 flex items-center">
						<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" class="mr-2" value="yes"' . $consent . ' />
						<label for="wp-comment-cookies-consent" class="text-sm text-dark-600 dark:text-dark-400">' . __( 'Save my name, email, and website in this browser for the next time I comment.', 'aqualuxe' ) . '</label>
					</div>',
	);
	
	$comments_args = array(
		'fields'               => $fields,
		'comment_field'        => '<div class="comment-form-comment mb-4">
									<label for="comment" class="form-label">' . _x( 'Comment', 'noun', 'aqualuxe' ) . ' <span class="required text-red-600">*</span></label>
									<textarea id="comment" name="comment" class="form-input" rows="6" aria-required="true"></textarea>
								</div>',
		'class_form'           => 'comment-form bg-white dark:bg-dark-800 p-6 rounded-lg shadow-sm mt-8',
		'title_reply'          => __( 'Leave a Comment', 'aqualuxe' ),
		'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title text-xl font-serif font-bold text-dark-900 dark:text-white mb-4">',
		'title_reply_after'    => '</h3>',
		'class_submit'         => 'btn btn-primary',
		'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
		'submit_field'         => '<div class="form-submit mt-6">%1$s %2$s</div>',
	);
	
	comment_form( $comments_args );
	?>

</div><!-- #comments -->