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
 * @since 1.0.0
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
        // Fire the comments before hook
        do_action( 'aqualuxe_comments_before' );
        ?>

        <ol class="comment-list">
            <?php
            /**
             * Functions hooked into aqualuxe_comments action
             *
             * @hooked aqualuxe_comments_list - 10
             * @hooked aqualuxe_comments_pagination - 20
             */
            do_action( 'aqualuxe_comments' );
            ?>
        </ol><!-- .comment-list -->

        <?php
        // Fire the comments after hook
        do_action( 'aqualuxe_comments_after' );

    endif; // Check for have_comments().

    // If comments are closed and there are comments, let's leave a little note, shall we?
    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
        ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'aqualuxe' ); ?></p>
        <?php
    endif;

    comment_form();
    ?>

</div><!-- #comments -->