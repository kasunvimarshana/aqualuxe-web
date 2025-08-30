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
		<h2 class="comments-title">
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
		'author' => '<div class="comment-form-author form-group"><label for="author">' . __( 'Name', 'aqualuxe' ) . 
					( $req ? ' <span class="required">*</span>' : '' ) . '</label>' .
					'<input id="author" class="form-control" name="author" type="text" value="' . 
					esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>',
					
		'email'  => '<div class="comment-form-email form-group"><label for="email">' . __( 'Email', 'aqualuxe' ) . 
					( $req ? ' <span class="required">*</span>' : '' ) . '</label>' .
					'<input id="email" class="form-control" name="email" type="email" value="' . 
					esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></div>',
					
		'url'    => '<div class="comment-form-url form-group"><label for="url">' . __( 'Website', 'aqualuxe' ) . '</label>' .
					'<input id="url" class="form-control" name="url" type="url" value="' . 
					esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div>',
					
		'cookies' => '<div class="comment-form-cookies-consent form-group"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' .
					'<label for="wp-comment-cookies-consent">' . __( 'Save my name, email, and website in this browser for the next time I comment.', 'aqualuxe' ) . '</label></div>',
	);

	$comments_args = array(
		'fields'               => $fields,
		'comment_field'        => '<div class="comment-form-comment form-group"><label for="comment">' . _x( 'Comment', 'noun', 'aqualuxe' ) . 
								' <span class="required">*</span></label><textarea id="comment" class="form-control" name="comment" cols="45" rows="8" aria-required="true"></textarea></div>',
		'class_form'           => 'comment-form',
		'class_submit'         => 'submit btn btn-primary',
		'title_reply'          => __( 'Leave a Comment', 'aqualuxe' ),
		'title_reply_to'       => __( 'Leave a Reply to %s', 'aqualuxe' ),
		'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
		'title_reply_after'    => '</h3>',
		'cancel_reply_before'  => '<span class="cancel-reply">',
		'cancel_reply_after'   => '</span>',
		'cancel_reply_link'    => __( 'Cancel Reply', 'aqualuxe' ),
		'label_submit'         => __( 'Post Comment', 'aqualuxe' ),
		'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
		'submit_field'         => '<div class="form-submit">%1$s %2$s</div>',
	);

	comment_form( $comments_args );
	?>

</div><!-- #comments -->