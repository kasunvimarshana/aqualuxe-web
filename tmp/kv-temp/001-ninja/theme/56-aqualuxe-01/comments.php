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

<div id="comments" class="comments-area bg-white dark:bg-dark-700 rounded-lg shadow-soft overflow-hidden p-6 mb-8">

	<?php
	/**
	 * Hook: aqualuxe_comments_before.
	 */
	do_action( 'aqualuxe_comments_before' );
	?>

	<?php
	/**
	 * Hook: aqualuxe_comments_list.
	 *
	 * @hooked aqualuxe_comments_list - 10
	 */
	do_action( 'aqualuxe_comments_list' );
	?>

	<?php
	/**
	 * Hook: aqualuxe_comments_navigation.
	 *
	 * @hooked aqualuxe_comments_navigation - 10
	 */
	do_action( 'aqualuxe_comments_navigation' );
	?>

	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'aqualuxe' ); ?></p>
		<?php
	endif;
	?>

	<?php
	/**
	 * Hook: aqualuxe_comments_form.
	 *
	 * @hooked aqualuxe_comments_form - 10
	 */
	do_action( 'aqualuxe_comments_form' );
	?>

	<?php
	/**
	 * Hook: aqualuxe_comments_after.
	 */
	do_action( 'aqualuxe_comments_after' );
	?>

</div><!-- #comments -->