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

<div id="comments" class="comments-area mt-12 pt-8 border-t border-gray-200">

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

		<ol class="comment-list">
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
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'aqualuxe' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().

	// Custom comment form
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$consent = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
	
	$fields = array(
		'author' => '<div class="comment-form-author mb-4"><label for="author" class="block text-sm font-medium text-gray-700 mb-1">' . __( 'Name', 'aqualuxe' ) . ( $req ? ' <span class="required text-red-500">*</span>' : '' ) . '</label><input id="author" name="author" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>',
		'email'  => '<div class="comment-form-email mb-4"><label for="email" class="block text-sm font-medium text-gray-700 mb-1">' . __( 'Email', 'aqualuxe' ) . ( $req ? ' <span class="required text-red-500">*</span>' : '' ) . '</label><input id="email" name="email" type="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></div>',
		'url'    => '<div class="comment-form-url mb-4"><label for="url" class="block text-sm font-medium text-gray-700 mb-1">' . __( 'Website', 'aqualuxe' ) . '</label><input id="url" name="url" type="url" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div>',
		'cookies' => '<div class="comment-form-cookies-consent mb-4 flex items-start"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' class="mt-1 mr-2" /><label for="wp-comment-cookies-consent" class="text-sm text-gray-600">' . __( 'Save my name, email, and website in this browser for the next time I comment.', 'aqualuxe' ) . '</label></div>',
	);
	
	$comment_field = '<div class="comment-form-comment mb-4"><label for="comment" class="block text-sm font-medium text-gray-700 mb-1">' . _x( 'Comment', 'noun', 'aqualuxe' ) . ' <span class="required text-red-500">*</span></label><textarea id="comment" name="comment" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" rows="6" aria-required="true"></textarea></div>';
	
	$comment_form_args = array(
		'fields'               => $fields,
		'comment_field'        => $comment_field,
		'class_form'           => 'comment-form',
		'class_submit'         => 'submit bg-primary hover:bg-primary-dark text-white font-medium py-2 px-6 rounded-md transition-colors duration-200',
		'title_reply'          => __( 'Leave a Comment', 'aqualuxe' ),
		'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title text-xl font-bold mb-4">',
		'title_reply_after'    => '</h3>',
		'cancel_reply_before'  => '<span class="cancel-reply ml-2">',
		'cancel_reply_after'   => '</span>',
		'cancel_reply_link'    => __( 'Cancel Reply', 'aqualuxe' ),
		'label_submit'         => __( 'Post Comment', 'aqualuxe' ),
		'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
		'comment_notes_before' => '<p class="comment-notes text-sm text-gray-600 mb-4">' . __( 'Your email address will not be published.', 'aqualuxe' ) . ( $req ? ' ' . __( 'Required fields are marked', 'aqualuxe' ) . ' <span class="required text-red-500">*</span>' : '' ) . '</p>',
	);
	
	comment_form( $comment_form_args );
	?>

</div><!-- #comments -->