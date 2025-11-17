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

<div id="comments" class="comments-area bg-white dark:bg-dark-800 p-6 sm:p-8 rounded-lg shadow-sm border border-gray-100 dark:border-dark-700 mt-8">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title text-2xl font-serif font-bold text-dark-800 dark:text-white mb-6">
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
			<p class="no-comments mt-6 p-4 bg-gray-100 dark:bg-dark-700 text-gray-600 dark:text-gray-300 rounded-md">
				<?php esc_html_e( 'Comments are closed.', 'aqualuxe' ); ?>
			</p>
			<?php
		endif;

	endif; // Check for have_comments().

	// Custom comment form
	$aqualuxe_commenter = wp_get_current_commenter();
	$aqualuxe_consent   = empty( $aqualuxe_commenter['comment_author_email'] ) ? '' : ' checked="checked"';
	
	$aqualuxe_fields = array(
		'author' => sprintf(
			'<div class="comment-form-author mb-4">
				<label for="author" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">%1$s%2$s</label>
				<input id="author" name="author" type="text" value="%3$s" size="30" maxlength="245" class="w-full px-4 py-2 border border-gray-300 dark:border-dark-600 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-dark-700 dark:text-white" %4$s />
			</div>',
			esc_html__( 'Name', 'aqualuxe' ),
			( get_option( 'require_name_email' ) ? ' <span class="required text-red-500">*</span>' : '' ),
			esc_attr( $aqualuxe_commenter['comment_author'] ),
			( get_option( 'require_name_email' ) ? 'required' : '' )
		),
		'email'  => sprintf(
			'<div class="comment-form-email mb-4">
				<label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">%1$s%2$s</label>
				<input id="email" name="email" type="email" value="%3$s" size="30" maxlength="100" aria-describedby="email-notes" class="w-full px-4 py-2 border border-gray-300 dark:border-dark-600 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-dark-700 dark:text-white" %4$s />
			</div>',
			esc_html__( 'Email', 'aqualuxe' ),
			( get_option( 'require_name_email' ) ? ' <span class="required text-red-500">*</span>' : '' ),
			esc_attr( $aqualuxe_commenter['comment_author_email'] ),
			( get_option( 'require_name_email' ) ? 'required' : '' )
		),
		'url'    => sprintf(
			'<div class="comment-form-url mb-4">
				<label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">%1$s</label>
				<input id="url" name="url" type="url" value="%2$s" size="30" maxlength="200" class="w-full px-4 py-2 border border-gray-300 dark:border-dark-600 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-dark-700 dark:text-white" />
			</div>',
			esc_html__( 'Website', 'aqualuxe' ),
			esc_attr( $aqualuxe_commenter['comment_author_url'] )
		),
		'cookies' => sprintf(
			'<div class="comment-form-cookies-consent flex items-start mb-4">
				<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" %1$s class="mt-1 h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-dark-600 rounded dark:bg-dark-700" />
				<label for="wp-comment-cookies-consent" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">%2$s</label>
			</div>',
			$aqualuxe_consent,
			esc_html__( 'Save my name, email, and website in this browser for the next time I comment.', 'aqualuxe' )
		),
	);
	
	$aqualuxe_comment_form_args = array(
		'title_reply'          => esc_html__( 'Leave a comment', 'aqualuxe' ),
		'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title text-xl font-serif font-bold text-dark-800 dark:text-white mb-4">',
		'title_reply_after'    => '</h3>',
		'class_form'           => 'comment-form',
		'comment_notes_before' => sprintf(
			'<p class="comment-notes mb-4 text-sm text-gray-600 dark:text-gray-400">%s <span id="email-notes">%s</span></p>',
			esc_html__( 'Your email address will not be published.', 'aqualuxe' ),
			esc_html__( 'Required fields are marked *', 'aqualuxe' )
		),
		'comment_field'        => sprintf(
			'<div class="comment-form-comment mb-4">
				<label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">%1$s <span class="required text-red-500">*</span></label>
				<textarea id="comment" name="comment" cols="45" rows="5" class="w-full px-4 py-2 border border-gray-300 dark:border-dark-600 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-dark-700 dark:text-white" required></textarea>
			</div>',
			esc_html__( 'Comment', 'aqualuxe' )
		),
		'fields'               => $aqualuxe_fields,
		'class_submit'         => 'bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-6 rounded-md transition-colors duration-200',
		'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
		'submit_field'         => '<div class="form-submit">%1$s %2$s</div>',
	);
	
	comment_form( $aqualuxe_comment_form_args );
	?>

</div><!-- #comments -->