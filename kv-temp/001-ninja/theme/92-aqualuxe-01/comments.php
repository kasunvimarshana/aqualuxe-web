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

<div id="comments" class="comments-area mt-12 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title text-2xl font-bold text-gray-900 dark:text-white mb-6">
			<?php
			$aqualuxe_comment_count = get_comments_number();
			if ( '1' === $aqualuxe_comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'aqualuxe' ),
					'<span class="font-semibold">' . wp_kses_post( get_the_title() ) . '</span>'
				);
			} else {
				printf( 
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $aqualuxe_comment_count, 'comments title', 'aqualuxe' ) ),
					number_format_i18n( $aqualuxe_comment_count ),
					'<span class="font-semibold">' . wp_kses_post( get_the_title() ) . '</span>'
				);
			}
			?>
		</h2><!-- .comments-title -->

		<?php the_comments_navigation(); ?>

		<ol class="comment-list space-y-6">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 64,
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
			<p class="no-comments mt-6 text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Comments are closed.', 'aqualuxe' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().

	comment_form(
		array(
			'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title text-xl font-bold text-gray-900 dark:text-white mb-4">',
			'title_reply_after'  => '</h3>',
			'class_form'         => 'comment-form space-y-4',
			'class_submit'       => 'submit bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out',
			'label_submit'       => esc_html__( 'Post Comment', 'aqualuxe' ),
		)
	);
	?>

</div><!-- #comments -->
