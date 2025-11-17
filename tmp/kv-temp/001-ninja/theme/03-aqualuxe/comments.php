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
            <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'aqualuxe' ); ?></p>
            <?php
        endif;

    endif; // Check for have_comments().

    // Custom comment form
    $aqualuxe_commenter = wp_get_current_commenter();
    $aqualuxe_comment_form_args = array(
        'title_reply'          => esc_html__( 'Leave a Comment', 'aqualuxe' ),
        'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'    => '</h3>',
        'class_form'           => 'comment-form',
        'class_submit'         => 'submit btn btn-primary',
        'comment_notes_before' => '<p class="comment-notes">' . esc_html__( 'Your email address will not be published. Required fields are marked *', 'aqualuxe' ) . '</p>',
        'comment_field'        => '<div class="comment-form-comment form-group"><label for="comment">' . esc_html__( 'Comment', 'aqualuxe' ) . '</label><textarea id="comment" class="form-control" name="comment" cols="45" rows="8" aria-required="true" required="required"></textarea></div>',
        'fields'               => array(
            'author' => '<div class="comment-form-author form-group"><label for="author">' . esc_html__( 'Name', 'aqualuxe' ) . ' <span class="required">*</span></label> <input id="author" class="form-control" name="author" type="text" value="' . esc_attr( $aqualuxe_commenter['comment_author'] ) . '" size="30" maxlength="245" required="required" /></div>',
            'email'  => '<div class="comment-form-email form-group"><label for="email">' . esc_html__( 'Email', 'aqualuxe' ) . ' <span class="required">*</span></label> <input id="email" class="form-control" name="email" type="email" value="' . esc_attr( $aqualuxe_commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes" required="required" /></div>',
            'url'    => '<div class="comment-form-url form-group"><label for="url">' . esc_html__( 'Website', 'aqualuxe' ) . '</label> <input id="url" class="form-control" name="url" type="url" value="' . esc_attr( $aqualuxe_commenter['comment_author_url'] ) . '" size="30" maxlength="200" /></div>',
            'cookies' => '<div class="comment-form-cookies-consent form-group"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" ' . ( empty( $aqualuxe_commenter['comment_author_email'] ) ? '' : 'checked="checked"' ) . ' /> <label for="wp-comment-cookies-consent">' . esc_html__( 'Save my name, email, and website in this browser for the next time I comment.', 'aqualuxe' ) . '</label></div>',
        ),
    );

    comment_form( $aqualuxe_comment_form_args );
    ?>

</div><!-- #comments -->