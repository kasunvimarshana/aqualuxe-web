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
		<h2 class="comments-title text-2xl font-bold mb-6">
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

		<ol class="comment-list space-y-6">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 60,
					'callback'    => 'aqualuxe_comment_callback',
				)
			);
			?>
		</ol><!-- .comment-list -->

		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<p class="no-comments mt-6 p-4 bg-gray-100 dark:bg-gray-800 rounded-md text-center"><?php esc_html_e( 'Comments are closed.', 'aqualuxe' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().

	// Custom comment form
	$commenter = wp_get_current_commenter();
	$consent = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
	
	$fields = array(
		'author' => sprintf(
			'<div class="comment-form-author mb-4">%s</div>',
			sprintf(
				'<input id="author" name="author" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-gray-100" placeholder="%s" value="%s" size="30" maxlength="245" %s />',
				esc_attr__( 'Name*', 'aqualuxe' ),
				esc_attr( $commenter['comment_author'] ),
				( get_option( 'require_name_email' ) ? ' required' : '' )
			)
		),
		'email' => sprintf(
			'<div class="comment-form-email mb-4">%s</div>',
			sprintf(
				'<input id="email" name="email" type="email" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-gray-100" placeholder="%s" value="%s" size="30" maxlength="100" aria-describedby="email-notes" %s />',
				esc_attr__( 'Email*', 'aqualuxe' ),
				esc_attr( $commenter['comment_author_email'] ),
				( get_option( 'require_name_email' ) ? ' required' : '' )
			)
		),
		'url' => sprintf(
			'<div class="comment-form-url mb-4">%s</div>',
			sprintf(
				'<input id="url" name="url" type="url" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-gray-100" placeholder="%s" value="%s" size="30" maxlength="200" />',
				esc_attr__( 'Website', 'aqualuxe' ),
				esc_attr( $commenter['comment_author_url'] )
			)
		),
		'cookies' => sprintf(
			'<div class="comment-form-cookies-consent flex items-center mb-4">%s %s</div>',
			sprintf(
				'<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" class="mr-2" value="yes"%s />',
				$consent
			),
			sprintf(
				'<label for="wp-comment-cookies-consent" class="text-sm text-gray-600 dark:text-gray-400">%s</label>',
				esc_html__( 'Save my name, email, and website in this browser for the next time I comment.', 'aqualuxe' )
			)
		),
	);
	
	$comment_field = sprintf(
		'<div class="comment-form-comment mb-4">%s</div>',
		sprintf(
			'<textarea id="comment" name="comment" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-gray-100" placeholder="%s" rows="8" maxlength="65525" required></textarea>',
			esc_attr__( 'Comment*', 'aqualuxe' )
		)
	);
	
	$submit_button = sprintf(
		'<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
		esc_attr( 'submit' ),
		esc_attr( 'submit' ),
		esc_attr( 'px-6 py-3 bg-primary hover:bg-primary-dark text-white font-medium rounded-md transition-colors cursor-pointer' ),
		esc_attr__( 'Post Comment', 'aqualuxe' )
	);
	
	comment_form(
		array(
			'fields'               => $fields,
			'comment_field'        => $comment_field,
			'submit_button'        => $submit_button,
			'class_form'           => 'comment-form bg-white dark:bg-gray-900 p-6 rounded-lg shadow-sm',
			'title_reply'          => esc_html__( 'Leave a Comment', 'aqualuxe' ),
			'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title text-xl font-bold mb-4">',
			'title_reply_after'    => '</h3>',
			'cancel_reply_before'  => '<span class="cancel-reply ml-2 text-sm">',
			'cancel_reply_after'   => '</span>',
			'cancel_reply_link'    => esc_html__( 'Cancel Reply', 'aqualuxe' ),
			'comment_notes_before' => '<p class="comment-notes text-sm text-gray-600 dark:text-gray-400 mb-4"><span id="email-notes">' . esc_html__( 'Your email address will not be published.', 'aqualuxe' ) . '</span> ' . esc_html__( 'Required fields are marked *', 'aqualuxe' ) . '</p>',
		)
	);
	?>

</div><!-- #comments -->