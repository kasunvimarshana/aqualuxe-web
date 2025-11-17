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

	$aqualuxe_comment_fields = array(
		'author' => sprintf(
			'<div class="comment-form-author">%s</div>',
			sprintf(
				'<input id="author" name="author" type="text" placeholder="%s" value="%s" size="30" maxlength="245" required />',
				esc_attr__( 'Name*', 'aqualuxe' ),
				esc_attr( isset( $commenter['comment_author'] ) ? $commenter['comment_author'] : '' )
			)
		),
		'email'  => sprintf(
			'<div class="comment-form-email">%s</div>',
			sprintf(
				'<input id="email" name="email" type="email" placeholder="%s" value="%s" size="30" maxlength="100" aria-describedby="email-notes" required />',
				esc_attr__( 'Email*', 'aqualuxe' ),
				esc_attr( isset( $commenter['comment_author_email'] ) ? $commenter['comment_author_email'] : '' )
			)
		),
		'url'    => sprintf(
			'<div class="comment-form-url">%s</div>',
			sprintf(
				'<input id="url" name="url" type="url" placeholder="%s" value="%s" size="30" maxlength="200" />',
				esc_attr__( 'Website', 'aqualuxe' ),
				esc_attr( isset( $commenter['comment_author_url'] ) ? $commenter['comment_author_url'] : '' )
			)
		),
	);

	$aqualuxe_comment_form_args = array(
		'fields'               => $aqualuxe_comment_fields,
		'comment_field'        => sprintf(
			'<div class="comment-form-comment">%s</div>',
			sprintf(
				'<textarea id="comment" name="comment" placeholder="%s" cols="45" rows="8" maxlength="65525" required></textarea>',
				esc_attr__( 'Comment*', 'aqualuxe' )
			)
		),
		'class_form'           => 'comment-form',
		'class_submit'         => 'submit btn btn-primary',
		'title_reply'          => esc_html__( 'Leave a Comment', 'aqualuxe' ),
		'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'aqualuxe' ),
		'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
		'title_reply_after'    => '</h3>',
		'cancel_reply_before'  => '<span class="cancel-reply">',
		'cancel_reply_after'   => '</span>',
		'cancel_reply_link'    => esc_html__( 'Cancel Reply', 'aqualuxe' ),
		'label_submit'         => esc_html__( 'Post Comment', 'aqualuxe' ),
		'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
		'submit_field'         => '<div class="form-submit">%1$s %2$s</div>',
	);

	comment_form( $aqualuxe_comment_form_args );
	?>

</div><!-- #comments -->