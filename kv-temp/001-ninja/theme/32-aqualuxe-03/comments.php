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

<div id="comments" class="comments-area mt-12 bg-white dark:bg-dark-700 rounded-lg shadow-soft p-8">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title text-2xl font-serif font-medium mb-6">
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

		<ol class="comment-list space-y-6 mb-6">
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
			<p class="no-comments p-4 bg-gray-100 dark:bg-dark-600 rounded-md text-center"><?php esc_html_e( 'Comments are closed.', 'aqualuxe' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().

	// Custom comment form
	$aqualuxe_comment_form_args = array(
		'title_reply'          => esc_html__( 'Leave a comment', 'aqualuxe' ),
		'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title text-xl font-serif font-medium mb-4">',
		'title_reply_after'    => '</h3>',
		'class_form'           => 'comment-form space-y-4',
		'comment_notes_before' => '<p class="comment-notes text-sm text-dark-500 dark:text-dark-300 mb-4">' . esc_html__( 'Your email address will not be published. Required fields are marked *', 'aqualuxe' ) . '</p>',
		'comment_field'        => '<div class="comment-form-comment"><label for="comment" class="block text-sm font-medium text-dark-700 dark:text-dark-200 mb-1">' . esc_html__( 'Comment', 'aqualuxe' ) . '</label><textarea id="comment" name="comment" class="block w-full rounded-md border-gray-300 dark:border-dark-600 dark:bg-dark-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" rows="5" required></textarea></div>',
		'fields'               => array(
			'author' => '<div class="comment-form-author"><label for="author" class="block text-sm font-medium text-dark-700 dark:text-dark-200 mb-1">' . esc_html__( 'Name', 'aqualuxe' ) . ' <span class="required">*</span></label><input id="author" name="author" type="text" class="block w-full rounded-md border-gray-300 dark:border-dark-600 dark:bg-dark-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" required></div>',
			'email'  => '<div class="comment-form-email"><label for="email" class="block text-sm font-medium text-dark-700 dark:text-dark-200 mb-1">' . esc_html__( 'Email', 'aqualuxe' ) . ' <span class="required">*</span></label><input id="email" name="email" type="email" class="block w-full rounded-md border-gray-300 dark:border-dark-600 dark:bg-dark-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" required></div>',
			'url'    => '<div class="comment-form-url"><label for="url" class="block text-sm font-medium text-dark-700 dark:text-dark-200 mb-1">' . esc_html__( 'Website', 'aqualuxe' ) . '</label><input id="url" name="url" type="url" class="block w-full rounded-md border-gray-300 dark:border-dark-600 dark:bg-dark-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"></div>',
		),
		'class_submit'         => 'btn btn-primary',
		'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
		'submit_field'         => '<div class="form-submit">%1$s %2$s</div>',
	);

	comment_form( $aqualuxe_comment_form_args );
	?>

</div><!-- #comments -->