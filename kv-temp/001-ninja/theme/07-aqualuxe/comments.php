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

<div id="comments" class="comments-area mt-12 pt-8 border-t border-gray-200 dark:border-dark-700">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title text-2xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-6">
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
			<p class="no-comments mt-6 p-4 bg-gray-100 dark:bg-dark-800 text-gray-700 dark:text-gray-300 rounded-lg"><?php esc_html_e( 'Comments are closed.', 'aqualuxe' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().

	// Custom comment form
	$commenter     = wp_get_current_commenter();
	$req           = get_option( 'require_name_email' );
	$html_req      = ( $req ? " required='required'" : '' );
	$consent       = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
	
	$fields = array(
		'author'  => '<div class="comment-form-author mb-4">
						<label for="author" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . esc_html__( 'Name', 'aqualuxe' ) . ( $req ? ' <span class="required text-red-600">*</span>' : '' ) . '</label>
						<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $html_req . ' class="w-full px-4 py-2 border border-gray-300 dark:border-dark-600 rounded-lg bg-white dark:bg-dark-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-300" />
					</div>',
		'email'   => '<div class="comment-form-email mb-4">
						<label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . esc_html__( 'Email', 'aqualuxe' ) . ( $req ? ' <span class="required text-red-600">*</span>' : '' ) . '</label>
						<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $html_req . ' class="w-full px-4 py-2 border border-gray-300 dark:border-dark-600 rounded-lg bg-white dark:bg-dark-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-300" />
					</div>',
		'url'     => '<div class="comment-form-url mb-4">
						<label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . esc_html__( 'Website', 'aqualuxe' ) . '</label>
						<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" class="w-full px-4 py-2 border border-gray-300 dark:border-dark-600 rounded-lg bg-white dark:bg-dark-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-300" />
					</div>',
		'cookies' => '<div class="comment-form-cookies-consent flex items-start mb-4">
						<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' class="mt-1 h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-dark-600 rounded transition-colors duration-300" />
						<label for="wp-comment-cookies-consent" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">' . esc_html__( 'Save my name, email, and website in this browser for the next time I comment.', 'aqualuxe' ) . '</label>
					</div>',
	);

	$comments_args = array(
		'fields'               => $fields,
		'comment_field'        => '<div class="comment-form-comment mb-4">
									<label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . esc_html__( 'Comment', 'aqualuxe' ) . ' <span class="required text-red-600">*</span></label>
									<textarea id="comment" name="comment" cols="45" rows="5" required="required" class="w-full px-4 py-2 border border-gray-300 dark:border-dark-600 rounded-lg bg-white dark:bg-dark-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-300"></textarea>
								</div>',
		'class_form'           => 'comment-form space-y-4',
		'title_reply'          => esc_html__( 'Leave a Comment', 'aqualuxe' ),
		'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title text-xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-4">',
		'title_reply_after'    => '</h3>',
		'class_submit'         => 'submit bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-dark-900',
		'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
		'comment_notes_before' => '<p class="comment-notes text-sm text-gray-600 dark:text-gray-400 mb-4"><span id="email-notes">' . esc_html__( 'Your email address will not be published.', 'aqualuxe' ) . '</span> <span class="required-field-message">' . esc_html__( 'Required fields are marked', 'aqualuxe' ) . ' <span class="required text-red-600">*</span></span></p>',
	);

	comment_form( $comments_args );
	?>

</div><!-- #comments -->