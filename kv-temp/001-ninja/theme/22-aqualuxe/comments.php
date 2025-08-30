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
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title text-2xl font-serif font-bold mb-6">
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

		<ol class="comment-list list-none p-0">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 60,
					'callback' => 'aqualuxe_comment_callback',
				)
			);
			?>
		</ol><!-- .comment-list -->

		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<p class="no-comments p-4 bg-gray-100 dark:bg-gray-800 rounded-lg text-center"><?php esc_html_e( 'Comments are closed.', 'aqualuxe' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().

	// Custom comment form
	$aqualuxe_comment_form_args = array(
		'title_reply' => esc_html__( 'Leave a comment', 'aqualuxe' ),
		'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title text-2xl font-serif font-bold mb-6">',
		'title_reply_after' => '</h3>',
		'class_form' => 'comment-form space-y-4',
		'comment_notes_before' => '<p class="comment-notes text-sm text-gray-600 dark:text-gray-400 mb-4">' . esc_html__( 'Your email address will not be published. Required fields are marked *', 'aqualuxe' ) . '</p>',
		'comment_field' => '<div class="comment-form-comment">
			<label for="comment" class="form-label">' . esc_html__( 'Comment', 'aqualuxe' ) . ' <span class="required">*</span></label>
			<textarea id="comment" name="comment" class="form-input" rows="5" required></textarea>
		</div>',
		'fields' => array(
			'author' => '<div class="comment-form-author">
				<label for="author" class="form-label">' . esc_html__( 'Name', 'aqualuxe' ) . ' <span class="required">*</span></label>
				<input id="author" name="author" type="text" class="form-input" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" required />
			</div>',
			'email' => '<div class="comment-form-email">
				<label for="email" class="form-label">' . esc_html__( 'Email', 'aqualuxe' ) . ' <span class="required">*</span></label>
				<input id="email" name="email" type="email" class="form-input" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" required />
			</div>',
			'url' => '<div class="comment-form-url">
				<label for="url" class="form-label">' . esc_html__( 'Website', 'aqualuxe' ) . '</label>
				<input id="url" name="url" type="url" class="form-input" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />
			</div>',
			'cookies' => '<div class="comment-form-cookies-consent flex items-center">
				<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" ' . ( empty( $commenter['comment_author_email'] ) ? '' : 'checked="checked"' ) . ' />
				<label for="wp-comment-cookies-consent" class="ml-2 text-sm">' . esc_html__( 'Save my name, email, and website in this browser for the next time I comment.', 'aqualuxe' ) . '</label>
			</div>',
		),
		'class_submit' => 'btn-primary',
		'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
		'submit_field' => '<div class="form-submit">%1$s %2$s</div>',
	);

	comment_form( $aqualuxe_comment_form_args );
	?>

</div><!-- #comments -->