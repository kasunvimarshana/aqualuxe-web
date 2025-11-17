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

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get theme options
$options = get_option('aqualuxe_options', array());

// Comments options
$comments_style = isset($options['comments_style']) ? $options['comments_style'] : 'standard';

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area comments-style-<?php echo esc_attr($comments_style); ?>">

    <?php
    // You can start editing here -- including this comment!
    if (have_comments()) :
        ?>
        <h2 class="comments-title">
            <?php
            $aqualuxe_comment_count = get_comments_number();
            if ('1' === $aqualuxe_comment_count) {
                printf(
                    /* translators: 1: title. */
                    esc_html__('One comment on &ldquo;%1$s&rdquo;', 'aqualuxe'),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            } else {
                printf( 
                    /* translators: 1: comment count number, 2: title. */
                    esc_html(_nx('%1$s comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', $aqualuxe_comment_count, 'comments title', 'aqualuxe')),
                    number_format_i18n($aqualuxe_comment_count),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            }
            ?>
        </h2><!-- .comments-title -->

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through? ?>
            <nav id="comment-nav-above" class="navigation comment-navigation">
                <h2 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'aqualuxe'); ?></h2>
                <div class="nav-links">
                    <div class="nav-previous"><?php previous_comments_link(esc_html__('Older Comments', 'aqualuxe')); ?></div>
                    <div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments', 'aqualuxe')); ?></div>
                </div><!-- .nav-links -->
            </nav><!-- #comment-nav-above -->
        <?php endif; // Check for comment navigation. ?>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 60,
                'callback'    => 'aqualuxe_comment_callback',
            ));
            ?>
        </ol><!-- .comment-list -->

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through? ?>
            <nav id="comment-nav-below" class="navigation comment-navigation">
                <h2 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'aqualuxe'); ?></h2>
                <div class="nav-links">
                    <div class="nav-previous"><?php previous_comments_link(esc_html__('Older Comments', 'aqualuxe')); ?></div>
                    <div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments', 'aqualuxe')); ?></div>
                </div><!-- .nav-links -->
            </nav><!-- #comment-nav-below -->
        <?php endif; // Check for comment navigation. ?>

        <?php
        // If comments are closed and there are comments, let's leave a little note, shall we?
        if (!comments_open()) :
            ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'aqualuxe'); ?></p>
            <?php
        endif;

    endif; // Check for have_comments().

    // Get comment form args
    $comment_form_args = array(
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'  => '</h3>',
        'class_submit'       => 'submit button button-primary',
    );

    // Add custom fields based on theme options
    if (isset($options['comment_rating']) && $options['comment_rating']) {
        $comment_form_args['comment_field'] = '<div class="comment-form-rating">
            <label for="rating">' . esc_html__('Rating', 'aqualuxe') . '</label>
            <select name="rating" id="rating" required>
                <option value="">' . esc_html__('Rate&hellip;', 'aqualuxe') . '</option>
                <option value="5">' . esc_html__('Perfect', 'aqualuxe') . '</option>
                <option value="4">' . esc_html__('Good', 'aqualuxe') . '</option>
                <option value="3">' . esc_html__('Average', 'aqualuxe') . '</option>
                <option value="2">' . esc_html__('Not that bad', 'aqualuxe') . '</option>
                <option value="1">' . esc_html__('Very poor', 'aqualuxe') . '</option>
            </select>
        </div>';
    }

    // Add the comment form
    comment_form($comment_form_args);
    ?>

</div><!-- #comments -->